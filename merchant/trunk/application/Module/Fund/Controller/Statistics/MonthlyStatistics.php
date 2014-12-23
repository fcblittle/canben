<?php
/**
 * 餐车每月结算
 * 
 * @package statistics 
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller\Statistics;

class MonthlyStatistics extends Statistics
{
    /**
     * @var float $salary 应发工资
     * @var float $effectiveSalary 实发工资
     * @var float $manageDeductionAmount 经营扣项
     */
    private $salary = 0;
    // private $effectiveSalary = 0;
    private $manageDeductionAmount = 0;

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
     * 每月统计
     * 
     * @property-read int $diner_id 餐车id
     * @property-read string $date 日期
     * @property-write float $effectiveSalary 实发工资
     * 
     * @return json
     * - int code 状态码 
     *   <value: ['OK', '*.*']>
     *   <description-value: 'OK'  - '成功'>
     *   <description-value: '*_*' - '异常状态'>
     * - string message 消息
     */
    public function _default($args)
    {
        $this->diner_id = empty($this->diner_id) ? $args['diner_id'] : $this->diner_id;
        $this->date     = empty($this->date) ? $args['date'] : $this->date;

        $this->checkStatus();

        // 月统计
        $result = $this->getMonthlyStatistics();
        if ($result['code'] !== 'OK') {
            return $result;
        }

        // 月扣项
        $result = $this->getManageDeductions();
        if ($result['code'] !== 'OK') {
            return $result;
        }

        // 月结算
        $result = $this->model->setMonthlyStatistics(array(
            'diner_id' => $this->diner_id,
            'salary'   => $this->salary,
            'deduction'=> $this->manageDeductionAmount,
            'timeRecord' => $this->getDateInterval($this->date, 'MonthRecord'),
            'created'  => REQUEST_TIME
        ));
        if ($result['code'] !== 'OK') {
            return $result;
        }

        return array('code' => 'OK');
    }

    /**
     * 获取月统计信息
     * 
     * @property-read int $diner_id 餐车id
     * @property-read string $date 日期
     * @property-write float $salary 应发工资
     * 
     * @return array
     */
    private function getMonthlyStatistics()
    {
        // 月销售额、利润
        $data = $this->model->getMonthlyStatistic(array(
            'diner_id' => $this->diner_id,
            'dateInterval' => $this->getDateInterval($this->date, 'Month')
        ));
        if ($data === false) {
            return array(
                'code' => 'STATISTICS.ERROR_GET_MONTHLY_STATISTICS',
                'message' => '获取月统计信息失败'
            );
        }

        $curStage = $this->getStage($data->amount);
        if ($curStage === false) {
            return array(
                'code'    => 'PROFIT.INVALID_DATA',
                'message' => '无效的月销售额'
            );
        }
        $this->salary = $data->profit * $this->stage[$curStage]['rate'];

        return array('code' => 'OK');
    }

    /**
     * 获取月扣项 经营扣项
     * 
     * @property-read $diner_id 餐车id
     * @property-read $date     月份
     * @property-write $manageDeductionAmount 经营扣项
     * 
     * @return array
     */
    private function getManageDeductions()
    {
        $list = $this->model->getDeductions(array(
            'diner_id' => $this->diner_id,
            'dateInterval' => $this->getDateInterval($this->date, 'Month'),
            'category' => 0
        ));
        if ($list === false) {
            return array(
                'code'    => 'DEDUCTIONS.ERROR_GET_MANAGE_DEDUCTIONS',
                'message' => '获取经营扣项失败'
            );
        }

        if (! empty($list)) {
            foreach ($list as $item) {
                if ($item->amount < 0) {
                    return array(
                        'code'    => 'DEDUCTIONS.ERROR_MANAGE_DEDUCTIONS_VALUE',
                        'message' => '错误的经营扣项值，请检查所有经营扣项'
                    );
                }
                $this->manageDeductionAmount += $item->amount;
            }
        }

        return array('code' => 'OK');
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

    /**
     * 是否到达月底
     * 
     * @property-read $date 日期
     */
    private function checkMonthEnd()
    {
        $dayEnd   = $this->getDateInterval($this->date, 'DailyRecord');
        $monthEnd = $this->getDateInterval($this->date, 'MonthRecord');

        return ($dayEnd == $monthEnd);
    }

    /**
     * 验证订单状态
     */
    private function checkStatus()
    {
        // 判断今日是否为月底
        if (! $this->checkMonthEnd()) {
            return array(
                'code'    => 'SYS.DAILY_STATISTIC_NOT_BEGINNING',
                'message' => '无法开始统计，请在月底开始统计'
            );
        }

        // 今日订单是否统计
        $result = $this->model->statisticItems(array(
            'table' => '`foodcar_diner_daily_yield`',
            'field' => array('diner_id' => $this->diner_id),
            'dateInterval' => $this->getDateInterval($this->date)
        ));
        if (empty($result)) {
            return array(
                'code'    => 'SYS.DAILY_STATISTIC_IS_NOT_DONE',
                'message' => '今日统计未完成'
            );
        }

        // 是否已统计
        $result = $this->model->statisticItems(array(
            'table' => '`foodcar_diner_monthly_statistics`',
            'field' => array('diner_id' => $this->diner_id),
            'dateInterval' => $this->getDateInterval($this->date, 'Month')
        ));
        if (! empty($result)) {
            return array(
                'code'    => 'SYS.GET_STATISTICS_DONE',
                'message' => '已完成本月统计'
            );
        }
    }
}