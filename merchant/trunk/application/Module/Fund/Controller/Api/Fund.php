<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;
use Application\Component\UnionPay;
use System\Component\Crypt\Hash;
use System\Component\Http\Request;

class Fund extends AppApi
{
    // 银行相关常量
    const BANK_RECHARGE   = 1;
    const BANK_WITHDRAWAL = 2;

    // 资金变动类型区间长度
    private $variationIntervalLength = 10;

    private $baseUrl;

    public function init()
    {
        $this->baseUrl = $this->request->baseUrl();

        $this->model = $this->model(':Fund');
    }

    /**
     * 获取变动数据
     */
    public function getItemList($args)
    {
        $params = array_merge(array(
            'role'        => $this->user->type,
            'uid'         => $this->user->id,
            // 'diner_id'    => ! empty($this->user->dinerId) ? $this->user->dinerId : 0,
            'accountType' => 'wallet',
            'join'        => array(
                'foreignKey' => 'variationTypeId',
                'table'      => '`foodcar_fund_variation_type` AS vType',
                'alias'      => 'vType',
                'tableKey'   => 'id',
                'fields'     => 'vType.name AS summary'
            )
        ), $args);

        $params['pager'] = array(
            'page'  => ! empty($args['pager']['page']) ? $args['pager']['page'] : 0,
            'limit' => ! empty($args['pager']['limit']) ? $args['pager']['limit'] : 20
        );

        if (! empty($params['date'])) {
            $params['date'] = $this->getDayInterval($params['date']);
        }

        $result = $this->model->getVariationList($params);
        if ($result === false) {
            return $this->export(array(
                'code' => 500
            ));
        }

        return $this->export(array(
            'code'    => 200,
            'content' => $result
        ));
    }

    /**
     * 获取资金变动类型
     * 
     * @param $args
     * -获取资金变动类型
     */
    public function getVariationType($args)
    {
        $params = ! empty($args) ? $args : $_POST;

        if ($params['interval']) {
            $params['interval'] = $this->getFormatInterval($params['interval']);
        }
        $params['fields'] = 'id,name';

        $result = $this->model->getVariationType($params);
        if ($result === false) {
            return $this->export(array(
                'code' => 500
            ));
        }

        $variationType = array();
        foreach ($result as $item) {
            $variationType[$item->id] = $item;
        }

        return $this->export(array(
            'code'    => 200,
            'content' => $variationType
        ));
    }

    /**
     * 获取统计数据
     * 
     * @param array $args
     * - string frequency <value: enum('day', 'month')>
     * - string date <format: yyyy-mm-dd>
     * 
     * @return array $data
     * - income  总收入
     * - outcome 总支出
     * - balance 账户余额
     */
    public function getStatistics($args)
    {
        $frequency = ! empty($args['frequency']) ? $args['frequency'] : 'Month';
        $dateInterval = call_user_func(array($this, 'get' . $frequency . 'Interval'), $args['date']);
        $accountType = $args['accountType'] ?: 'wallet';

        $params = array(
            'uid'      => $this->user->id,
            'role'     => $this->user->type,
            'diner_id' => isset($this->user->dinerId) ? $this->user->dinerId : null,
            'date'     => $dateInterval,
        );

        // 获取余额
        $result = $this->model->getAccountBalance(array(
            'fields' => 'wallet, account',
            'uid'    => $params['uid'],
            'role'   => $params['role'],
        ));
        $data['balance'] = $result->$accountType;

        // 获取收支明细
        $result = $this->model->getVariationList($params);
        if ($result === false) {
            return $this->export(array(
                'code'    => 500,
                'message' => '数据库查询失败！'
            ));
        }
        // 获取区间总收支
        $data['income'] = $data['outcome'] = 0;
        foreach ($result as $item) {
            if ($item->accountType !== $accountType) {
                continue;
            }
            if ($item->amount < 0) {
                $data['outcome'] += (-1) * $item->amount;
            } else {
                $data['income'] += $item->amount;
            }
        } 
        
        return $this->export(array(
            'code' => 200,
            'content' => $data
        ));
    }

    /**
     * 转账
     * 
     * @param array $args
     * - int variationType 转账类型 table(foodcar_fund_variation_type) <rules: required>
     * - float amount 转账金额 <rules: required>
     * - int diner_id 餐车id <rules: required(when variationTypeId > 10)>
     * - int uid 用户id
     * - int created 添加时间
     * 
     * - bool ajax 是否ajax传值 <default: false>
     *
     * - string type 用户类型 enum('merchant', 'manager') <invalid>
     * 
     * @return json
     * - int code 状态码
     * - mix content 数据
     */
    public function transferAccounts($args = array('ajax' => false))
    {
        $params = $args['ajax'] || $_POST['ajax'] ? $_POST : $args;
        $params = array_merge($params, array(
            'type'        => $this->user->type,
            'uid'         => $this->user->id,
            'created'     => REQUEST_TIME
        ));

        $params['amount'] = abs($params['amount']);

        $errors = $this->validate($params);
        $messages = array();
        if (! empty($errors)) {
            foreach ($errors as $field) {
                foreach ($field as $rule) {
                    $messages[] = $rule['message'];
                }
            }
            return $this->export(array(
                'code'    => 400,
                'message' => $messages
            ));
        }

        if ($this->user->type === 'manager' 
            && empty($params['diner_id'])) { 
            $params['diner_id'] = $this->user->dinerId;
        }

        $variationType = $this->model->getVariationType(array(
                            'id'       => $params['variationType'],
                            'role'     => $params['type'],
                            'multiple' => false
                        ));
        if (empty($variationType)) {
            return $this->export(array(
                'code'    => 400,
                'message' => '您无此权限！'
            ));
        }
        $params = array_merge($params, array(
            'accountType'     => $variationType->accountType,
            'accountTo'       => $variationType->accountTo,
            'roleAccountTo'   => $variationType->roleAccountTo,
            'roleAccountType' => $variationType->roleAccountType,
        ));

        // 获取账户余额、验证余额是否充足
        if ($params['roleAccountType'] !== 'bank') {
            $accountFund = $this->model->getAccountBalance(array(
                'uid'  => $params['uid'],
                'role' => $params['roleAccountType'] 
                            ? $params['roleAccountType'] 
                            : $this->user->type,
            ));
            if (! $this->checkBalanceAmount(array(
                    'accountType' => $params['accountType'],
                    'accountFund' => $accountFund,
                    'amount'      => $params['amount']
                ))) {
                return $this->export(array(
                    'code'    => 300,
                    'message' => '余额不足！'
                ));
            }
        }

        // 是否银行相关操作
        if ($this->checkBankRelated($params)) {
            // 调用银联接口
            $result = $this->doBankOperation($params);

            return $this->export($result);
        }

        $result = $this->model->transferAccounts($params);
        if ($result === false) {
            return $this->export(array(
                'code'    => 500,
                'message' => '转账失败，请稍后重试！'
            ));
        }

        return $this->export(array(
            'code'    => 200,
            'message' => '转账成功！',
            'content' => $result
        ));
    }

    /**
     * 银行转账
     * 生成记录
     * 
     * @param array $args
     * @return array
     */
    protected function doBankOperation($args)
    {
        switch ($args['variationType']) {
            case self::BANK_RECHARGE:
                return $this->recharge($args); // 充值
                break;
            case self::BANK_WITHDRAWAL:
                return $this->withdrawal($args); // 提现
                break;
            default:
                return array(
                    'code'    => 'BANK.INVALID_VARIATION_TYPE',
                    'message' => '无效的转账类型'
                );
                break;
        }
    }

    /**
     * 充值
     * 
     * @param array $args 
     * - 
     * 
     * @return array $result
     */
    protected function recharge($args)
    {
        $order_num = $this->getOrderNum(self::BANK_RECHARGE);
        // 插入交易记录
        $result = $this->model->addConsumeRecord(array(
            'uid'  => $args['uid'],
            'role' => $args['type'],
            'order_num'    => $order_num,
            'amount'       => $args['amount'],
            'time_start'   => $args['created'],
        ));
        if ($result === false) {
            return array(
                'code'    => 'RECHARGE.ERROR_ADD_CONSUME_RECORD',
                'message' => '插入交易信息失败'
            );
        }

        $cUnionPay = new UnionPay\UnionPayService(array(
                'transType'   => UnionPay\UnionPayConfig::CONSUME,
                'backEndUrl'  => $this->baseUrl . "/fund/checkBack/checkRechargeBack",
                'frontEndUrl' => $this->baseUrl . "/fund/{$args['type']}/index",
                'orderTime'   => date('YmdHis'),
                'orderNumber' => $order_num,
                'orderAmount' => $args['amount'] * 100,
                'orderCurrency' => UnionPay\UnionPayConfig::CURRENCY_CNY,
                'customerIp'  => REQUEST::clientIP(),
            ), UnionPay\UnionPayConfig::FRONT_PAY);

        return array(
            'code'    => 600,
            'url'     => $cUnionPay->api_url,
            'content' => $cUnionPay->get_args()
        );
    }

    /**
     * 提现
     */
    protected function withdrawal()
    {
        $data = array(
            'uid'      => $this->user->id,
            'userType' => $this->user->type,
            'bank'     => $_POST['bank'],
            'bank_account'      => $_POST['account'],
            'bank_account_name' => $_POST['name'],
            'mobile'   => $_POST['mobile'],
            'money'    => $_POST['amount'],
            'submit_time' => REQUEST_TIME,
            'status'   => 1
        );

        $errors = $this->validate($data, 'withdrawal');
        if (! empty($errors)) {
            foreach ($errors as $field) {
                foreach ($field as $rule) {
                    $messages[] = $rule['message'];
                }
            }
            return $this->export(array(
                'code'    => 400,
                'message' => $messages
            ));
        }

        $result = $this->model->addwithdrawalalRecord($data);
        if ($result === false) {
            return array(
                'code'    => 'RECHARGE.ERROR_ADD_WITHDRAWAL_RECORD',
                'message' => '申请提现失败'
            );
        }

        return array(
            'code'    => 200,
            'message' => '申请提现成功！'
        );
    }

    /**
     * 生成随机订单号
     * 交易类型+ 时间戳(毫秒) + 三位随机数
     */
    protected function getOrderNum($consumeType)
    {
        list($msec, $sec) = explode(' ', microtime());
        $timestamp = ceil(($sec + $msec) * 1000);

        // 生成随机数
        $random = Hash::randomString(3, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return $consumeType . $timestamp . $random;
    }

    /**
     * 获取类型区间
     * 
     * @param int $interval 区间字段
     * 
     * @return array $intervalArr
     */
    private function getFormatInterval($interval)
    {
        if ($interval < 1) {
            return array();
        }

        return array(
            'min' => ($interval - 1) * $this->variationIntervalLength + 1,
            'max' => $interval * $this->variationIntervalLength
        );
    }

    /**
     * 获取当日区间时间戳
     */
    protected function getDayInterval($date)
    {
        list($year, $month, $day) = explode('-', $date);

        return array(
            'beginning' => mktime(0, 0, 0 , $month, $day, $year),
            'end' => mktime(0, 0, 0 , $month, $day + 1, $year)
        );
    }

    /**
     * 获取当月时间戳
     */
    protected function getMonthInterval()
    {
        return array(
            'beginning' => mktime(0, 0, 0, date('m'), 1, date('Y')),
            'end'       => mktime(0, 0, 0, date('m') + 1, 1, date('Y'))
        );
    }

    /**
     * 验证是否银行相关操作
     */
    private function checkBankRelated($args)
    {
        return ($args['accountType'] == 'bank') || ($args['accountTo'] == 'bank');
    }

    /**
     * 检验余额是否充足
     * 
     * @param array $args
     * -int accountType 账户类型
     * -object accountFund 资金账户
     * -float amount 金额
     * 
     * @return bool
     */
    private function checkBalanceAmount($args)
    {
        extract($args);
        
        return $accountFund->$accountType >= $amount; 
    }

    /**
     * 验证
     */
    public function validate($data, $formType = 'default')
    {
        $validator = $this->com('System:Validator/Validator');
        if ($formType === 'default') {
            $rules = array(
                'variationType' => array(
                    'name'  => '转账摘要',
                    'value' => $data['variationType'],
                    'rules' => array(
                        'required' => array(),
                        'int'      => array()
                    )
                ),
                'amount' => array(
                    'name'  => '金额',
                    'value' => $data['amount'],
                    'rules' => array(
                        'required' => array(),
                        'number'   => array()
                    )
                )
            );
        }
        if ($formType === 'withdrawal') {
            $rules = array(
                'uid'  => array(
                    'name'  => '用户id',
                    'value' => $data['uid'],
                    'rules' => array(
                        'required' => array(),
                    )
                ),
                'userType' => array(
                    'name'  => '用户类型',
                    'value' => $data['userType'],
                    'rules' => array(
                        'required' => array(),
                    )
                ),
                'bank' => array(
                    'name'  => '开户银行',
                    'value' => $data['bank'],
                    'rules' => array(
                        'required' => array(),
                    )
                ),
                'bank_account' => array(
                    'name'  => '银行账号',
                    'value' => $data['bank_account'],
                    'rules' => array(
                        'required'  => array(),
                        'number'    => array(),
                    )
                ),
                'bank_account_name' => array(
                    'name'  => '银行户名',
                    'value' => $data['bank_account_name'],
                    'rules' => array(
                        'required'  => array(),
                    )
                ),
                'mobile' => array(
                    'name'  => '银行预留电话',
                    'value' => $data['mobile'],
                    'rules' => array(
                        'required'  => array(),
                    )
                ),
                'money' => array(
                    'name'  => '提现金额',
                    'value' => $data['money'],
                    'rules' => array(
                        'required'  => array(),
                        'int'       => array(),
                        'great'     => array(
                            'equal'  => false,
                            'value'  => 0,
                            'message'=> '提现金额必须大于0'
                        ),
                    )
                ),
            );
        }

        $validator->validate($rules);

        return $validator->getErrors();
    }
}