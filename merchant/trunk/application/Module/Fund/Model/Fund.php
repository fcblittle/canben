<?php

namespace Module\Fund\Model;

use System\Model;

class Fund extends Model
{
    private $tableVariationType = '';
    private $tableConsume       = '';
    private $tableConsumeError  = '';
    private $tableWithMerchantDrawal  = '';

    // 资金账户类型
    private $accountType = array('wallet', 'account');

    // 所属账号角色
    private $accountRole = array('merchant', 'manager');

    public function __construct()
    {
        parent::__construct();

        $this->tableVariationType = '`foodcar_fund_variation_type`';
        $this->tableConsume       = '`foodcar_unionpay_consume`';
        $this->tableConsumeError  = '`foodcar_unionpay_consume_err_record`';
        $this->tableWithMerchantDrawal  = '`foodcar_merchant_withdrawals`';
    }

    /**
     * 获取表相关
     * 
     * @param string $role 角色
     * 
     * @return array/bool
     */
    private function getTableParams($role)
    {
        $params = array();

        if ($role === 'merchant') {
            $params['table'] = '`foodcar_merchant_fund`';
            $params['tableVariation'] = '`foodcar_merchant_fund_variation`';
            $params['field'] = 'merchant_id';
        } else if ($role === 'manager') {
            $params['table'] = '`foodcar_staff_fund`';
            $params['tableVariation'] = '`foodcar_staff_fund_variation`';
            $params['field'] = 'staff_id';
        } /*else if ($role === 'diner') {
            $params['table'] = '`foodcar_diner_fund`';
            $params['tableVariation'] = '`foodcar_diner_fund_variation`';
            $params['field'] = 'diner_id';
        } */else {
            return false;
        }

        return $params;
    }

    /**
     * 获取用户资金变动
     * 
     * @param array $args
     * - int    uid  用户id
     * - string role 用户角色
     * - string accountType 账户类型
     * 
     * - string fields 字段
     * - string order  排序方式
     * - string join   外联表
     * 
     * - array pager 分页
     */
    public function getVariationList($args)
    {
        $conds = $joinTable = '';
        $binds = array();

        $params = array_merge(array(
            'fields'      => 'v.*',
            'order'       => 'id DESC',
            'join'        => array(),
            'role'        => null,
            'uid'         => null,
            'diner_id'    => null,
            'accountType' => null,
            'date'        => null
        ), $args);

        $tableParams = $this->getTableParams($params['role']);
        if ($tableParams === false) {
            return false;
        }

        // 多表组合
        if (! empty($params['join'])) {
            $joinTable .= " LEFT JOIN {$params['join']['table']}
                            ON {$params['join']['alias']}.{$params['join']['tableKey']} = v.{$params['join']['foreignKey']}";

            $params['fields'] = empty($params['join']['fields']) ? $params['fields'] : $params['fields'] . ",{$params['join']['fields']}";
        }

        if ($params['uid'] != null) {
            $conds .= " AND v.{$tableParams['field']}=?";
            $binds[] = $params['uid'];
        }
        if (! empty($params['diner_id'])) {
            $conds .= " AND v.diner_id=?";
            $binds[] = $params['diner_id'];
        }
        if ($params['accountType'] != null) {
            $conds .= " AND v.accountType=?";
            $binds[] = $params['accountType'];
        }
        if ($params['date'] != null) {
            $conds .= " AND v.created BETWEEN ? AND ?";
            $binds[] = $params['date']['beginning'];
            $binds[] = $params['date']['end'];
        }

        $sql = "SELECT {$params['fields']}
                FROM {$tableParams['tableVariation']} AS v
                {$joinTable}
                WHERE 1 $conds
                ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(*) AS total
                FROM {$tableParams['tableVariation']} AS v
                {$joinTable}
                WHERE 1 $conds";

        if (! empty($params['pager'])) {
            return array(
                'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
                'total' => $this->db->fetch($sqlc, $binds)->total
            );
        }

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 获取资金变动类型
     * 
     * @param array $args
     * - string fields 要取字段
     * - array  interval 变动类型区间
     * 
     * - int id
     * - string role 账户角色 enum('manager', 'merchant');
     * 
     * - bool multiple 是否多选
     */
    public function getVariationType($args)
    {
        $params = array_merge(array(
            'fields'      => '*',
            'interval'    => array(),
            'id'          => null,
            'role'        => null,
            'multiple'    => true
        ), $args);

        $conds = '';
        $binds = array();
        if (! empty($params['interval'])) {
            $conds .= " AND id BETWEEN ? AND ?";
            $binds[] = $params['interval']['min'];
            $binds[] = $params['interval']['max'];
        }
        if (! empty($params['id'])) {
            $conds .= " AND id = ?";
            $binds[] = $params['id'];
        }
        /*if (! empty($params['accountType'])) {
            $conds .= " AND accountType = ?";
            $binds[] = $params['accountType'];
        }
        if (! empty($params['accountTo'])) {
            $conds .= " AND accountTo = ?";
            $binds[] = $params['accountTo'];
        }*/
        $sql = "SELECT {$params['fields']}
                FROM {$this->tableVariationType}
                WHERE 1 $conds";
        $result = $params['multiple'] ? $this->db->fetchAll($sql, $binds) : $this->db->fetch($sql, $binds);
        $this->formatVariationType($result, $args['role']);

        return $result;
    }

    /**
     * 格式化资金变动类型
     * 
     * @param  mix &$variation 资金变动类型 ()
     * @return mix $variation
     */
    private function formatVariationType(& $variation, $role)
    {
        if (is_array($variation) || ! $variation->$role) {
            return;
        }
        /*list($variation->accountType, $accountTo) = explode('-', $variation->$role);

        if (in_array($accountTo, $this->accountRole)) {
            $variation->roleAccountTo = $accountTo;

            list($variation->accountTo, $to) = explode('-', $variation->$accountTo);
            if ($to != $role) {
                $variation->accountTo = '';
            }
        } else {
            if (in_array($accountTo, $this->accountType)) {
                $variation->roleAccountTo = $role;
            }
            $variation->accountTo = $accountTo;
        }*/
        list($variation->accountType, $variation->accountTo) = explode('-', $variation->$role);

        $variation->roleAccountType = $this->checkAccountRole($variation->accountType, $role);
        $variation->roleAccountTo   = $this->checkAccountRole($variation->accountTo, $role);

        unset($variation->merchant);
        unset($variation->manager);
        unset($variation->diner);
    }

    /**
     * 验证账户角色
     *
     * @param string $accountType 账户类型
     * @param string $role  操作角色
     * @return string $accountRole 账户角色
     */
    private function checkAccountRole($accountType, $role)
    {
        if (! in_array($accountType, $this->accountType)) {
            return $accountType;
        } else {
            return $role;
        }
    }

    /**
     * 转账
     */
    public function transferAccounts($args)
    {
        $this->db->beginTransaction();

        // 转出
        $result = $this->doTransfer($args, '-');
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        // 转入
        $args = $this->doExchangeTransferArgs($args);

        $result = $this->doTransfer($args, '+');
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        return $this->db->commit();
    }

    /**
     * 转账操作
     * 扣除用户账户余额
     * 添加转账记录
     * 
     * @param array $args
     * @return bool
     */
    protected function doTransfer($args, $sign)
    {
        if (! in_array($args['accountType'], $this->accountType)) {
            return true;
        }

        $signArr = array(
            '-' => -1,
            '+' => 1
        );

        // 更新转账金额
        $result = $this->updateBalance(array(
            'role'        => $args['roleAccountType'],
            'uid'         => $args['uid'],
            'accountType' => $args['accountType'],
            'amount'      => $args['amount']
        ), $sign);
        if ($result === false) {
            return false;
        }
        // 添加转账记录
        $result = $this->addVariation(array(
            'role'            => $args['roleAccountType'],
            'uid'             => $args['uid'],
            'diner_id'        => $args['diner_id'],
            'variationType'   => $args['variationType'],
            'accountType'     => $args['accountType'],
            'accountTo'       => $args['accountTo'],
            'roleAccountType' => $args['roleAccountType'],
            'roleAccountTo'   => $args['roleAccountTo'],
            'amount'          => $args['amount'],
            'created'         => $args['created']
        ), $signArr[$sign]);

        return $result;
    }

    /**
     * 交换转账参数
     * 
     * @param array $args
     * @return array $args
     */
    protected function doExchangeTransferArgs($args)
    {
        $tmp = array();

        $tmp['role'] = $args['roleAccountTo'];
        $tmp['accountType'] = $args['accountTo'];
        $tmp['accountTo'] = $args['accountType'];
        $tmp['roleAccountType'] = $args['roleAccountTo'];
        $tmp['roleAccountTo'] = $args['roleAccountType'];

        return array_merge($args, $tmp);
    }

    /**
     * 更新账户余额
     * 
     * @param array $args
     * -role 账户类型 merchant/manager/diner
     * -uid  账户id
     * -accountType 转出账户
     * -amount 变动金额
     * @return bool
     */
    public function updateBalance($args, $addOrSubtract = '+')
    {
        if ($addOrSubtract !== '+' && $addOrSubtract !== '-') {
            return false;
        }

        $params = $conds = $binds = array();

        $table = $this->getTableParams($args['role']);
        if (! empty($args['accountType'])) {
            $params[] = "{$args['accountType']} = {$args['accountType']} {$addOrSubtract} ?";
            $binds[] = $args['amount'];
        }
        $params = implode(',', $params);
        $conds = $table['field'] . '=?';
        $binds[] = $args['uid'];

        $sql = "UPDATE {$table['table']} SET {$params} WHERE $conds";

        return $this->db->execute($sql, $binds);
    }

    /**
     * 添加账户变动记录
     * 
     * @param array $args
     * -role 账户类型 merchant/manager
     * -uid  账户id
     * -variationType 转账类型Id
     * -accountType 转出账户
     * -accountTo   转入账户
     * -roleAccountType 转出账户角色
     * -roleAccountTo   转入账户角色
     * -amount 变动金额
     * @param int $plusOrMinus <value: 1,-1>
     * @param array $multiple 多条记录输入 <format: array(('*' => '*')[, ('*' => '*'), ...])>
     * @return bool
     */
    public function addVariation($args, $plusOrMinus = 1, $multiple = array())
    {
        if (empty($args)) {
            return false;
        }

        $binds = array();

        // 账户类型
        $table = $this->getTableParams($args['role']);
        if ($table === false) {
            return;
        }

        $params[$table['field']] = $args['uid'];
        if (! empty($args['diner_id'])) {
            $params['diner_id'] = $args['diner_id'];
        }
        $params['variationTypeId'] = $args['variationType'];
        $params['accountType'] = $args['accountType'];
        $params['accountTo'] = ($args['accountTo'] == $args['roleAccountTo']) 
                                ? $args['accountTo'] 
                                : $args['roleAccountTo'];
        $params['amount'] = $args['amount'] * $plusOrMinus;
        // 获取余额
        $balance = $this->getAccountBalance(array(
            'uid'  => $args['uid'],
            'role' => $args['roleAccountType'],
            'diner_id' => ! empty($diner_id) ? $diner_id : null
        ));
        $params['balance'] = $balance->$args['accountType'];
        $params['created'] = $args['created'];
        foreach ($params as $k => $v) {
            $params[$k] = is_numeric($v) ? $v : "'$v'";
        }

        $fields = implode(',', array_keys($params));
        $params = implode(',', $params);

        $sql = "INSERT INTO {$table['tableVariation']}({$fields}) VALUES({$params})";

        return $this->db->execute($sql, $binds);
    }

    /**
     * 获取账户余额
     * 
     * @param array $args
     * - int uid 用户id
     * - string role 用户类型 --商户 --经营者  --餐车
     * 
     * - fields default '*'
     * 
     * @return array
     */
    public function getAccountBalance($args)
    {
        $binds = array();

        $params = array_merge(array(
            'fields' => '*'
        ), $args);

        $table = $this->getTableParams($params['role']);
        $conds = " AND {$table['field']} = ?";
        $binds[] = $params['uid'];

        if (! empty($params['diner_id'])) {
            $conds .= " AND diner_id = ?";
            $binds[] = $params['diner_id'];
        }

        $sql = "SELECT {$params['fields']}
                FROM {$table['table']}
                WHERE 1 $conds";
        
        return $this->db->fetch($sql, $binds);
    }

    /*银行操作相关*/
    /**
     * 成功充值返回设置
     * 
     * @param array $data
     * @return bool
     */
    public function setRetStatus($order, $retVal)
    {
        $this->db->beginTransaction();

        // 账户充值
        $orderAmount = $retVal['orderAmount'] / 100;
        $result = $this->doTransfer(array(
            'role'            => $order->role,
            'uid'             => $order->uid,
            // 'diner_id'        => $args['diner_id'],
            'variationType'   => 1,
            'accountType'     => 'wallet',
            'accountTo'       => 'bank',
            'roleAccountType' => $order->role,
            'roleAccountTo'   => 'bank',
            'amount'          => $orderAmount,
            'created'         => $order->time_start
        ), '+');
        if ($result === false) {
            // todo: 记录错误信息
        }

        $variationId = $this->db->lastInsertId();

        // 更改订单表信息
        $result = $this->updateConsumeRecord(array(
            'id'  => $order->id,
            'qid' => $retVal['qid'],
            'amount' => $orderAmount,
            'variation_id' => $variationId,
            'response' => json_encode($retVal),
            'time_finish' => REQUEST_TIME
        ));
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        return $this->db->commit();
    }

    /**
     * 获取银行转账记录
     * 
     * @param array $args
     * @return bool|array $result
     */
    public function getConsumeRecord($args)
    {
        $params = array_merge(array(
            'fields' => '*',
            'id'     => null,
            'qid'     => null,
            'order_num'   => null,
            'time_start'  => null,
            'time_finish' => null,
        ), $args);

        $conds = '';
        $binds = array();
        if (! empty($params['id'])) {
            $conds .= " AND id = ?";
            $binds[] = $params['id'];
        }
        if (! empty($params['qid'])) {
            $conds .= " AND qid = ?";
            $binds[] = $params['qid'];
        }
        if (! empty($params['order_num'])) {
            $conds .= " AND order_num = ?";
            $binds[] = $params['order_num'];
        }
        if (! empty($params['time_start'])) {
            $conds .= " AND time_start BETWEEN ? AND ?";
            $binds[] = $params['time_start']['beginning'];
            $binds[] = $params['time_start']['end'];
        }
        if (! empty($params['time_finish'])) {
            $conds .= " AND time_finish BETWEEN ? AND ?";
            $binds[] = $params['time_finish']['beginning'];
            $binds[] = $params['time_finish']['end'];
        }

        $sql = "SELECT {$params['fields']} 
                FROM {$this->tableConsume}
                WHERE 1 $conds";

        return (! empty($params['id']) || ! empty($params['order_num']))
                ? $this->db->fetch($sql, $binds)
                : $this->db->fetchAll($sql, $binds);
    }

    /**
     * 更新银行转账记录
     * 
     * @param array $data
     * @return bool
     */
    public function updateConsumeRecord($data)
    {
        $sets = $binds = array();

        $id = $data['id'];
        unset($data['id']);

        foreach ($data as $key => $value) {
            $sets[] = $key . '=?';
            $binds[] = $value;
        }
        $setsStr = implode(',', $sets);
        $binds[] = $id;

        $sql = "UPDATE {$this->tableConsume} SET $setsStr WHERE id=?";

        return $this->db->execute($sql, $binds);
    }

    /**
     * 添加银行转账记录
     * 
     * @param array $data
     * @return bool
     */
    public function addConsumeRecord($data)
    {
        return $this->db->insert($this->tableConsume, $data);
    }

    /**
     * 添加交易错误消息
     * 
     * @param array $errInfo
     * @return bool
     */
    public function addConsumeErrorRecord($errInfo)
    {
        return $this->db->insert($this->tableConsumeError, $errInfo);
    }

    /**
     * 插入申请提现记录
     */
    public function addwithdrawalalRecord($data)
    {
        $this->db->beginTransaction();
        // 冻结提现资金
        $result = $this->doTransfer(array(
            'role'            => $data['userType'],
            'uid'             => $data['uid'],
            // 'diner_id'        => $args['diner_id'],
            'variationType'   => 2,
            'accountType'     => 'wallet',
            'accountTo'       => 'bank',
            'roleAccountType' => $data['userType'],
            'roleAccountTo'   => 'bank',
            'amount'          => $data['money'],
            'created'         => $data['submit_time'],
        ), '-');
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        $result = $this->db->insert($this->tableWithMerchantDrawal, $data);
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        return $this->db->commit();
    }
}