<?php
/**
 * 餐车每日结算
 * 
 * @package statistics 
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller\Statistics;

class DailyStatistics extends Statistics
{
    /**
     * @var array $allocation 利润分配 <fields: (merchantDailyIncome-商户收入, salary-工资, stage-所属阶段, [compensation-补回金额])>
     */
    private $allocation = array();

    /**
     * 构造函数
     * 
     * @property-read int $diner_id 餐车id
     * @property-read string $date 日期
     * 
     * @return void|json
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 每日统计
     * 
     * @property-read int $diner_id 餐车id
     * @property-read string $date 日期
     * 
     * @return json
     * - int code 状态码 
     *   <value: ['OK', '*.*']>
     *   <description-value: 'OK'  - '成功'>
     *   <description-value: '*_*' - '异常状态'>
     * - string message 消息
     */
    public function _default($args = array())
    {
        $this->diner_id = empty($this->diner_id) ? $args['diner_id'] : $this->diner_id;
        $this->date     = empty($this->date) ? $args['date'] : $this->date;

        // 每日统计时间
        if (! $this->checkStatisticTime()) {
            return array(
                'code'    => 'SYS.INVALID_STATISTIC_TIME',
                'message' => '未到达 统计时间'
            );
        }


        // 是否已统计
        $result = $this->model->statisticItems(array(
            'table' => '`foodcar_diner_daily_yield`',
            'field' => array('diner_id' => $this->diner_id),
            'dateInterval' => $this->getDateInterval($this->date),
        ));
        if (! empty($result)) {
            return array(
                'code'    => 'SYS.GET_STATISTICS_DONE',
                'message' => '已完成今日统计'
            );
        }

        // 每日销售额、利润统计
        $result = $this->countDailySales();
        if ($result['code'] !== 'OK') {
            return $result;
        }

        // 利润分配
        $result = $this->allocateProfit();
        if ($result['code'] !== 'OK') {
            return $result;
        }


        // 扣除商户"补回利润"部分资金 && 插入变动数据到表
        $result = $this->model->setDailyStatistic(array(
            'diner_id' => $this->diner_id,
            'amount'   => $this->amount,
            'profit'   => $this->profit,
            'allocation' => $this->allocation,
            'timeRecord' => $this->getDateInterval($this->date, 'DailyRecord'),
            'created'  => REQUEST_TIME
        ));
        if ($result['code'] !== 'OK') {
            return $result;
        }

        return array('code' => 'OK');
    }

    /**
     * 每日销售额、利润统计
     * 
     * @property-read int $diner_id 餐车id
     * @property-read string $date 日期
     * 
     * @return array
     */
    private function countDailySales()
    {
        $dateInterval = $this->getDateInterval($this->date);

        // 线下订单总额、利润、成本
        $data = $this->model->getOrderOfflineStatistic(array(
            'diner_id' => $this->diner_id,
            'dateInterval' => $dateInterval
        ));
        if ($data === false) {
            return array(
                'code'    => 'SALES.ERROR_GET_ORDER_OFFLINE',
                'message' => '获取线下订单失败'
            );
        }
        $this->amount += $data['sum'];
        $this->profit += $data['profit'];

        // 线上订单利润
        $data = $this->model->getOrderOnlineStatistic(array(
            'diner_id'     => $this->diner_id,
            'dateInterval' => $dateInterval
        ));
        if ($data === false) {
            return array(
                'code'    => 'SALES.ERROR_GET_ORDER_ONLINE',
                'message' => '获取线上订单失败'
            );
        }
        $this->amount += $data['sum'];
        $this->profit += $data['profit'];

        return array('code' => 'OK');
    }

    /**
     * 利润分配
     * 
     * @property-read int $diner_id 餐车id
     * @property-read string $date 日期
     * 
     * @property-write string $allocation 分配
     * 
     * @return array
     */
    private function allocateProfit()
    {
        $this->allocation['sales'] = $this->amount;

        // 获取月统计
        $monthlyStatistic = $this->model->getMonthlyStatistic(array(
                                'diner_id'      => $this->diner_id,
                                'dateInterval' => $this->getDateInterval($this->date, 'Month')
                            ));
        // 获取原来 利润阶段
        $todayBeforeStage = $this->getStage($monthlyStatistic->amount);
        if ($todayBeforeStage === false) {
            return array(
                'code'    => 'PROFIT.INVALID_DATA',
                'message' => '无效的月销售额'
            );
        }
        $this->allocation['stage'] = $currentStage = $this->getStage($monthlyStatistic->amount + $this->amount);
        $this->allocation['rate']  = $this->stage[$currentStage]['rate'];

        // 利润分配
        // 日统计
        $this->allocation['merchantDailyIncome'] = $this->amount - $this->profit * $this->stage[$currentStage]['rate'];

        $this->allocation['salary'] = $this->profit * $this->stage[$currentStage]['rate'];
        // 月统计
        $this->allocation['monthlySales']  = $monthlyStatistic->amount + $this->allocation['sales']; 

        $this->allocation['monthlySalary'] = ($monthlyStatistic->profit + $this->profit) * $this->stage[$currentStage]['rate'];
        $this->allocation['merchantMonthlyIncome'] = ($this->allocation['monthlySales'] - $this->allocation['monthlySalary']);
        // 利润补回
        $this->allocation['compensation'] = $this->getStageCompensation(array(
                                                'prev' => $todayBeforeStage,
                                                'cur'  => $currentStage,
                                                'monthlyProfit' => $monthlyStatistic->profit
                                            ));
        return array('code' => 'OK');
    }

    /**
     * 获取阶段利润补回
     * 
     * @param array $args
     * - int prev 前阶段
     * - int cur  目前阶段
     * - float monthlyProfit 月利润
     * 
     * @return float $compensationAmount 利润补回金额
     */
    private function getStageCompensation($args)
    {
        return $args['monthlyProfit'] * ($this->stage[$args['cur']]['rate'] - $this->stage[$args['prev']]['rate']);
    }

    private function checkStatisticTime()
    {
        return date('H', time()) >= 22;
    }

    /**
     * 权限验证
     * 
     * @return bool
     */
    protected function permissionCheck()
    {
        // todo: 权限验证

        return true;
    }
}