<?php
/**
 * 统计模型
 * 
 * @package statistics
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Model;

use System\Model;

class Statistic extends Fund
{
    // 订单相关表
    private $tableOnline = '`foodcar_order`';
    private $tableOffline = '`foodcar_order_offline`';
    private $tableOnlineOrderDetail = '`foodcar_food_order`';
    private $tableDishRevision = '`foodcar_official_dish_revision`';
    // 统计相关表
    private $tableDailyYield = '`foodcar_diner_daily_yield`';
    private $tableDeduction  = '`foodcar_diner_deduction`';
    private $tableDeductionType  = '`foodcar_diner_deduction_type`';
    private $tableMonthlyStatistic = '`foodcar_diner_monthly_statistics`';
    // 餐车表
    private $tableDiner = '`foodcar_diner`';

    private $variation = array(
        'dailyIncome' => array(
            'variationTypeId' => 17,
            'merchant' => array(
                'accountType' => 'account',
                'accountTo'   => 'diner',
                'roleAccountType' => 'merchant',
                'roleAccountTo'   => 'diner',
            ),
        ),
        'profitCompensation' => array(
            'variationTypeId' => 18,
            'merchant' => array(
                'accountType' => 'account',
                'accountTo'   => 'diner',
                'roleAccountType' => 'merchant',
                'roleAccountTo'   => 'diner',
            ),
        ),
        'deduction' => array(
            'items' => array(
                'hostedManageDeduction' => array(
                    'variationTypeId' => 22,
                    'amount'          => 500,
                ),
            ),
            'merchant' => array(
                'accountType' => 'account',
                'accountTo'   => 'official',
                'roleAccountType' => 'merchant',
                'roleAccountTo'   => 'official',
            ),
        )
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取餐车线下订单 统计
     * 
     * @param array $args
     * - int diner_id <rules: required>
     * - array dateInterval <rules: required>
     * 
     * - string fields <default: '*'>
     */
    public function getOrderOfflineStatistic($args)
    {
        $binds = array();
        if (empty($args['diner_id']) || empty($args['dateInterval'])) {
            return false;
        }
        $params = array_merge(array(
            'fields' => '*'
        ), $args);

        $cond = " offline.diner_id = ?";
        $binds[] = $params['diner_id'];
        $cond .= " AND offline.transfered BETWEEN ? AND ?";
        $binds[] = $params['dateInterval']['beginning'];
        $binds[] = $params['dateInterval']['end'];

        $sql = "SELECT offline.*, dishRevision.supply_price, dishRevision.sale_price 
                FROM {$this->tableOffline} AS offline
                LEFT JOIN {$this->tableDishRevision} AS dishRevision
                ON offline.dish_reversion_id = dishRevision.id
                WHERE $cond";
        $result = $this->db->fetchAll($sql, $binds);

        return $this->getDailyStatistics($result);
    }

    /**
     * 获取线上订单 统计
     * 
     * @param array $args
     * - int diner_id <rules: required>
     * - int dateInterval <rules: required>
     * 
     * @return array $result
     * - sum    float 每日线上销售总额
     * - profit float 每日线上销售利润
     */
    public function getOrderOnlineStatistic($args)
    {
        $binds = array();
        if (empty($args['diner_id']) || empty($args['dateInterval'])) {
            return false;
        }
        $params = array_merge(array(
            'fields' => '*'
        ), $args);

        $cond = " forder.store_id = ?";
        $binds[] = $params['diner_id'];
        $cond .= " AND (forder.status = 3 OR forder.status = 5)";
        $cond .= " AND forder.insert_time BETWEEN ? AND ?";
        $binds[] = $params['dateInterval']['beginning'];
        $binds[] = $params['dateInterval']['end'];

        $sql = "SELECT food.id,food.unit_price AS sale_price, food.supply_price,food.num AS count
                FROM {$this->tableOnline} AS forder
                LEFT JOIN {$this->tableOnlineOrderDetail} AS food
                ON forder.id = food.order_id
                WHERE $cond";
        $result = $this->db->fetchAll($sql, $binds);
        
        return $this->getDailyStatistics($result);
    }

    /**
     * 记录每日销售统计
     * 对补回利润进行处理
     * 
     * @param array $args
     * - int diner_id 餐车id <rules: required>
     * - float amount 日销售额 <rules: required>
     * - float profit 日利润 <rules: required>
     * - array allocation 日分配 <rules: required>
     *   - int stage 利润分配阶段
     *   - float merchantIncome 商户所得(商户日收入)
     *   - float salary 餐车经营者(店长,店小二)所得(工资)
     *   - float compensation 利润补回
     * - timestamp timeRecord 统计时间 <rules: required>
     * - timestamp created 添加时间 <rules: required>
     * 
     * @uses $this->doCompensation to do compensating
     * 
     * @return array $result
     * - int code 状态码
     * - string message 消息
     */
    public function setDailyStatistic($args)
    {
        $this->db->beginTransaction();

        // 查找商户id
        $dinerInfo = $this->getDinerInfo($args['diner_id'], 'merchant_id, role');
        if ($dinerInfo === false) {
            return false;
        }

        // 商户收益
        $merchantIncome = ($dinerInfo->role == 2) 
                            ? $args['allocation']['merchantDailyIncome']
                            : $args['allocation']['sales'];
        $result = $this->doAllocateProfit(array(
            'diner_id'    => $args['diner_id'],
            'merchant_id' => $dinerInfo->merchant_id,
            'income'      => $merchantIncome,
            'created'     => $args['timeRecord'],
        ));
        if ($result === false) {
            $this->db->rollback();
            return array(
                'code'    => 'RECORD.ERROR_ALLOCATE',
                'message' => '商户利润分配失败'
            );
        }

        // 利润补回
        if ($dinerInfo->role == 2 && $args['allocation']['compensation'] > 0) {
            $result = $this->doCompensate(array(
                'diner_id' => $args['diner_id'],
                'merchant_id' => $dinerInfo->merchant_id,
                'compensationAmount' => $args['allocation']['compensation'],
                'created'  => $args['timeRecord'],
            ));
            if ($result === false) {
                $this->db->rollback();
                return array(
                    'code'    => 'RECORD.ERROR_COMPENSATE',
                    'message' => '补回利润失败'
                );
            }
        }

        // 插入记录
        $sql = "INSERT INTO {$this->tableDailyYield}(diner_id, amount, profit, allocation, timeRecord, created) 
                VALUES(?, ?, ?, ?, ?, ?)";
        $result = $this->db->execute($sql, array(
            $args['diner_id'], $args['amount'], $args['profit'], 
            json_encode($args['allocation']), $args['timeRecord'], $args['created']
        ));
        if ($result === false) {
            $this->db->rollback();
            return array(
                'code'    => 'RECORD.ERROR_ADD_DAILY_RECORD',
                'message' => '添加日统计数据失败'
            );
        }

        $this->db->commit();
        return array(
            'code'    => 'OK'
        );
    }

    /**
     * 商户收益结算
     * 
     * @param array $args
     * - int diner_id 餐车id <rules: required>
     * - int merchant_id 商户id <rules: required>
     * - float income 收入 <rules: required>
     * - float created 记录时间 <rules: required>
     * 
     * @property-read array $variation
     * 
     * @return bool
     */
    private function doAllocateProfit($args)
    {
        $result = $this->updateBalance(array(
            'role' => $this->variation['dailyIncome']['merchant']['roleAccountType'],
            'uid'  => $args['merchant_id'],
            'accountType' => $this->variation['dailyIncome']['merchant']['accountType'],
            'amount'      => $args['income']
        ));
        if ($result === false) {
            return false;
        }

        $result = $this->addVariation(array(
            'role' => $this->variation['dailyIncome']['merchant']['roleAccountType'],
            'uid'  => $args['merchant_id'],
            'diner_id'    => $args['diner_id'],
            'variationType' => $this->variation['dailyIncome']['variationTypeId'],
            'accountType'   => $this->variation['dailyIncome']['merchant']['accountType'],
            'accountTo'     => $this->variation['dailyIncome']['merchant']['accountTo'],
            'roleAccountType' => $this->variation['dailyIncome']['merchant']['roleAccountType'],
            'roleAccountTo' => $this->variation['dailyIncome']['merchant']['roleAccountTo'],
            'amount'        => $args['income'],
            'created'       => $args['created']
        ));
        if ($result === false) {
            return false;
        }

        return true;
    }

    /**
     * 利润补回
     * 
     * @param array $args
     * - int diner_id 餐车id <rules: required>
     * - int merchant_id 商户id <rules: required>
     * - float compensationAmount 补回金额 <rules: required>
     * - int created 记录时间 <rules: required>
     * 
     * @property-read array $variation
     *
     * @return bool
     */
    private function doCompensate($args)
    {
        return $this->doTransfer(array(
            'role' => $this->variation['profitCompensation']['merchant']['roleAccountType'],
            'uid'           => $args['merchant_id'],
            'diner_id'      => $args['diner_id'],
            'variationType' => $this->variation['profitCompensation']['variationTypeId'],
            'accountType'   => $this->variation['profitCompensation']['merchant']['accountType'],
            'accountTo'     => $this->variation['profitCompensation']['merchant']['accountTo'],
            'roleAccountType' => $this->variation['profitCompensation']['merchant']['roleAccountType'],
            'roleAccountTo' => $this->variation['profitCompensation']['merchant']['roleAccountTo'],
            'amount'        => $args['compensationAmount'],
            'created'       => $args['created']
        ), '-');
    }

    /**
     * 月结算
     * 扣除 商户管理扣项
     * 添加记录
     * 
     * @param array $args 
     * - int diner_id 餐车id <required>
     * - float salary 应发工资 <required>
     * - float deduction 经营扣项 <required>
     * - int timeRecord 记录时间  <required>
     * - int created 添加时间  <required>
     * 
     * @return array
     */
    public function setMonthlyStatistics($args)
    {
        $dinerInfo = $this->getDinerInfo($args['diner_id'], 'merchant_id');

        $this->db->beginTransaction();
        // 商户扣项
        $result = $this->doMerchantDeducte(array(
            'diner_id' => $args['diner_id'],
            'merchant_id' => $dinerInfo->merchant_id,
            'created'  => $args['timeRecord']
        ));
        if ($result === false) {
            $this->db->rollback();
            return array(
                'code'    => 'DEDUCTE.ERROR_DEDUCTE_MERCHANT',
                'message' => '扣除商户 月扣项错误'
            );
        }

        // 添加记录
        $fields = $placeholder = $binds = array();
        foreach ($args as $key => $value) {
            $fields[]  = $key;
            $placeholder[] = '?';
            $binds[]   = $value;
        }
        $tableFields = implode(',', $fields);
        $tablePlaceholder = implode(',', $placeholder);

        $sql = "INSERT INTO {$this->tableMonthlyStatistic}($tableFields) 
                VALUES({$tablePlaceholder})";

        $result = $this->db->execute($sql, $binds);
        if ($result === false) {
            $this->db->rollback();
            return array(
                'code'    => 'MONTHLY.ERROR_STATISTIC_RECORD',
                'message' => '插入月统计记录失败'
            );
        }

        $this->db->commit();

        return array('code' => 'OK');
    }

    /**
     * 商户扣项
     * 
     * @param array $args
     * - int diner_id 餐车id <rules: required>
     * - int merchant_id 商户id <rules: required>
     * 
     * @property-read array $variation
     * 
     * @return bool
     */
    private function doMerchantDeducte($args)
    {
        if (empty($this->variation['deduction']['items'])) {
            return true;
        }

        // 扣项
        foreach ($this->variation['deduction']['items'] as $item) {
            $result = $this->updateBalance(array(
                'role' => $this->variation['deduction']['merchant']['roleAccountType'],
                'uid'  => $args['merchant_id'],
                'accountType' => $this->variation['deduction']['merchant']['accountType'],
                'amount'      => $item['amount'],
            ), '-');
            if ($result === false) {
                return false;
            }

            $result = $this->addVariation(array(
                'role' => $this->variation['deduction']['merchant']['roleAccountType'],
                'uid'  => $args['merchant_id'],
                'diner_id'      => $args['diner_id'],
                'variationType' => $item['variationTypeId'],
                'accountType'   => $this->variation['deduction']['merchant']['accountType'],
                'accountTo'     => $this->variation['deduction']['merchant']['accountTo'],
                'roleAccountType' => $this->variation['deduction']['merchant']['roleAccountType'],
                'roleAccountTo'   => $this->variation['deduction']['merchant']['roleAccountTo'],
                'amount'          => $item['amount'],
                'created'         => $args['created'],
            ), -1);
            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * 检查是否已进行该操作
     * 
     * @param array $args
     * - string table <required>
     * - array  field <required>
     * - array dateInterval <key: beginning,end|required>
     * 
     * @return bool
     */
    public function statisticItems($args)
    {
        $conds = '';
        $binds = array();

        if (empty($args['table']) 
            || empty($args['field'])
            || empty($args['dateInterval'])
        ) {
            return false;
        }

        foreach ($args['field'] as $key => $v) {
            $conds .= " AND $key = ?";
            $binds[] = $v;
        }

        $conds .= ' AND timeRecord BETWEEN ? AND ?';
        $binds[] = $args['dateInterval']['beginning'];
        $binds[] = $args['dateInterval']['end'];

        $sql = "SELECT * 
                FROM {$args['table']}
                WHERE 1 $conds";

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取订单统计
     * 
     * @param array $orderList 订单列表
     * @return array $statistic
     * - sum 销售总额
     * - profit 利润
     */
    private function getDailyStatistics($orderList)
    {
        $statistic = array(
            'sum'    => 0,
            'profit' => 0
        );

        if (! empty($orderList)) {
            foreach ($orderList as $item) {
                $statistic['sum']    += $item->count * $item->sale_price;
                $statistic['profit'] += $item->count * ($item->sale_price - $item->supply_price);
            }
        }

        return $statistic;
    }

    /**
     * 获取月订单统计
     * 
     * @param array $args
     * - array dateInterval 日期区间(当月) <rules: required>
     * - int diner_id 餐车id <rules: required>
     * 
     * @return object $statistic 统计数组
     * - float amount 月总销售额
     * - float profit 月总利润
     */
    public function getMonthlyStatistic($args)
    {
        if (empty($args['dateInterval'])
            || empty($args['diner_id'])) {
            return false;
        }

        $binds = array();

        $conds .= " timeRecord BETWEEN ? AND ?";
        $binds[] = $args['dateInterval']['beginning'];
        $binds[] = $args['dateInterval']['end'];
        $conds .= " AND diner_id = ?";
        $binds[] = $args['diner_id'];

        $sql = "SELECT SUM(amount) AS amount, SUM(profit) AS profit
                FROM {$this->tableDailyYield}
                WHERE $conds";

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取月扣项
     * 
     * @param array $args
     * - int diner_id 餐车id <rules: required>
     * - array dateInterval 月份 <rules: required>
     * - int category 扣项类别 <value: 0-经营扣项；1-管理扣项>
     * 
     * @return bool|array
     */
    public function getDeductions($args)
    {
        if (empty($args['diner_id']) 
            || empty($args['dateInterval'])) {
            return false;
        }

        $conds = '';
        $binds = array();

        $conds .= " deduction.diner_id = ?";
        $binds[] = $args['diner_id']; 
        $conds .= " AND deduction.timeRecord BETWEEN ? AND ?";
        $binds[] = $args['dateInterval']['beginning'];
        $binds[] = $args['dateInterval']['end'];
        if (isset($args['category'])) {
            $conds .= " AND type.category = ?";
            $binds[] = $args['category'];
        }

        $sql = "SELECT deduction.*, type.name 
                FROM {$this->tableDeduction} AS deduction
                LEFT JOIN {$this->tableDeductionType} AS type 
                ON deduction.typeId = type.id 
                WHERE $conds";

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 获取餐车所属商户id
     * 
     * @param int $diner_id 餐车id
     * @param string $fields 字段<default: '*'>
     * @return bool|object $dinerInfo 餐车信息
     */
    private function getDinerInfo($diner_id, $fields = '*')
    {
        $sql = "SELECT {$fields} 
                FROM {$this->tableDiner}
                WHERE id = ?";

        return $this->db->fetch($sql, array($diner_id));
    }
}