<?php
/**
 * 餐车统计 抽象类
 * 
 * @package statistics 
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller\Statistics;

use Application\Controller\Front;

abstract class Statistics extends Front
{
    /**
     * @var int $diner_id 餐车id
     * @var float $amount 月/日 总销售额
     * @var float $profit 月/日 总利润
     * @var string $date  统计日期  <format: yyyy-mm-dd>
     * @var array $stage  餐车利润分配阶段 <fields: (rate-利润分配比, tippingPoint-临界点)>
     */
    protected $diner_id = null;
    protected $amount = 0;
    protected $profit = 0;
    protected $date = '';
    protected $stage = array(
        0 => array('tippingPoint' => 0),
        1 => array(
            'rate' => 0.3,
            'tippingPoint' => 35000
        ),
        2 => array(
            'rate' => 0.4,
            'tippingPoint' => 40000
        ),
        3 => array(
            'rate' => 0.5,
            'tippingPoint' => 45000
        ),
        4 => array('rate' => 0.6)
    );

    /**
     * @todo   权限验证
     * @return bool
     */
    abstract protected function permissionCheck();

    /**
     * 构造函数
     * 
     * @param method post
     * 
     * @property-write int    $diner_id
     * @property-write string $date
     */
    public function __construct()
    {
        parent::__construct();

        // todo: 权限验证
        if (! $this->permissionCheck()) {
            $this->export(array(
                'code'    => 'PERMISSION.DENY',
                'message' => '您无此权限'
            ));
        }

        $this->model = $this->model(':Statistic');

        // 参数处理
        /*if (empty($_POST['diner_id'])) {
            $this->export(array(
                'code'    => 'SYS.INVALID_PARAMS',
                'message' => '缺少必要的参数'
            ));
        }*/
        $this->diner_id = $_POST['diner_id'] ?: '';
        $this->date     = $_POST['date'] ?: '';
    }

    /**
     * 获取利润阶段
     * 
     * @param float $monthlySales 总销售额
     * @return int|array $key 利润阶段
     */
    protected function getStage($monthlySales)
    {
        $keys = array_keys($this->stage);

        foreach ($this->stage as $key => $item) {
            if ($key == 0) {
                continue;
            }
            if ($key == end($keys) 
                && $monthlySales >= $this->stage[$key - 1]['tippingPoint']) {
                return $key;
            }
            if ($monthlySales >= $this->stage[$key - 1]['tippingPoint'] 
                && $monthlySales < $this->stage[$key]['tippingPoint']) {
                return $key;
            }
        }

        return false;
    }

    /**
     * 获取日期区间
     * 
     * @param  string $date <format: yyyy-mm-dd>
     * @param  string $intervalType <value: enum('Day', 'Month', 'DailyRecord')>
     * 
     * @return string|array $dateInterval
     */
    protected function getDateInterval($date, $intervalType = 'Day')
    {
        $date = ! empty($date) ? $date : date('Y-m-d');
        list($year, $month, $day) = explode('-', $date);

        if ($intervalType === 'Day') {
            return array(
                'beginning' => mktime(0, 0, 0, $month, $day, $year),
                'end'       => mktime(0, 0, 0, $month, $day + 1, $year)
            );   
        }

        if ($intervalType === 'Month') {
            return array(
                'beginning' => mktime(0, 0, 0, $month, 1, $year),
                'end'       => mktime(0, 0, 0, $month + 1, 1, $year)
            );
        }

        if ($intervalType === 'DailyRecord') {
            return mktime(22, 0, 0, $month, $day, $year);
        }

        if ($intervalType === 'MonthRecord') {
            return mktime(22, 0, 0, $month + 1, 0, $year);
        }
    }

    protected function export($data)
    {
        $this->response->json($data);
    }
}