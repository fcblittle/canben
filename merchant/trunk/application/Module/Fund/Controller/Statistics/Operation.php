<?php
/**
 * 每日操作
 * 
 * @package operate 
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller\Statistics;

use Application\Controller\Front;

class Operation extends Front
{
    public function __construct()
    {
        parent::__construct();
    }

    public function doStatistic()
    {
        $modelDiningcar = $this->model('Module\Merchant:Diningcar');
        // 获取所有餐车
        $diners = $modelDiningcar->getAll(array(
            'fields' => 'id, diner_name',
            'status' => 1
        ));

        // 记录时间
        $date = date('Y-m-d', time());

        // 日统计
        $this->dailyStatistic($diners, $date);

        // 月统计
        if ($this->checkMonthlyStatistic($date)) {
            $this->monthlyStatistic($diners, $date);
        }

        echo 'OK';
        die();
    }

    private function dailyStatistic($diners, $date)
    {
        $failureDailyStatisticDinerItems = $succeedDailyStatisticDinerItems = array();

        foreach ($diners as $item) {
            // 循环各餐车统计，日/月 统计
            // 保存统计失败餐车id
            $result = $this->call('fund/statistics/dailyStatistics', array(
                'diner_id' => $item->id,
                'date'     => $date
            ));
            if ($result['code'] === 'OK') {
                $succeedDailyStatisticDinerItems[] = $item;
            } else {
                $failureDailyStatisticDinerItems[] = $item;
            }
        }

        // 统计失败餐车再次统计
        if (! empty($failureDailyStatisticDinerItems)) {
            foreach ($failureDailyStatisticDinerItems as $key => $failureItem) {

                $result = $this->call('fund/statistics/dailyStatistics', array(
                    'diner_id' => $failureItem->id,
                    'date'     => $date
                ));
                if ($result['code'] === 'OK') {
                    $succeedDailyStatisticDinerItems[] = $failureItem;
                    unset($failureDailyStatisticDinerItems[$key]);
                } else {
                    $failureItem->errCode = $result['code'];
                    $failureItem->errMsg  = $result['message'];

                    $failureDailyStatisticDinerItems[] = $failureItem;
                }
            }
        }

        // log
        $this->statisticsLog(array(
            'period' => 'day',
            'status' => 1,
            'data'   => $succeedDailyStatisticDinerItems,
            'date' => $date
        ));
        if (! empty($failureDailyStatisticDinerItems)) {
            $this->statisticsLog(array(
                'period' => 'day',
                'status' => -1,
                'data'   => $failureDailyStatisticDinerItems,
                'date'   => $date
            ));
        }
    }

    private function monthlyStatistic($diners, $date)
    {
        $failureMonthlyStatisticDinerItems = $succeedMonthlyStatisticDinerItems = array();

        foreach ($diners as $item) {
            // 循环各餐车统计，日/月 统计
            // 保存统计失败餐车id
            $result = $this->call('fund/statistics/monthlyStatistics', array(
                'diner_id' => $item->id,
                'date'     => $date
            ));
            if ($result['code'] === 'OK') {
                $succeedMonthlyStatisticDinerItems[] = $item;
            } else {
                $failureMonthlyStatisticDinerItems[] = $item;
            }
        }

        // 统计失败餐车再次统计
        if (! empty($failureMonthlyStatisticDinerItems)) {
            foreach ($failureMonthlyStatisticDinerItems as $key => $failureItem) {

                $result = $this->call('fund/statistics/dailyStatistics', array(
                    'diner_id' => $failureItem->id,
                    'date'     => $date
                ));
                if ($result['code'] === 'OK' || $result['code'] === 'SYS.GET_STATISTICS_DONE') {
                    $succeedMonthlyStatisticDinerItems[] = $failureItem;
                    unset($failureMonthlyStatisticDinerItems[$key]);
                } else {
                    $failureItem->errCode = $result['code'];
                    $failureItem->errMsg  = $result['message'];

                    $failureMonthlyStatisticDinerItems[] = $failureItem;
                }
            }
        }

        // log
        $this->statisticsLog(array(
            'period' => 'month',
            'status' => 1,
            'data'   => $succeedMonthlyStatisticDinerItems,
            'date' => $date
        ));
        if (! empty($failureDailyStatisticDinerItems)) {
            $this->statisticsLog(array(
                'period' => 'month',
                'status' => -1,
                'data'   => $failureMonthlyStatisticDinerItems,
                'date'   => $date
            ));
        }
    }

    private function checkMonthlyStatistic($date)
    {
        return $this->getTimestamp($date) == $this->getTimestamp($date, true);
    }

    private function statisticsLog($args)
    {
        $table = '`foodcar_diner_statistics_log`';

        $data = array(
            'period' => $args['period'],
            'status' => $args['status'],
            'log'    => json_encode($args['data']),
            'timeRecord' => $this->getTimestamp($args['date']),
        );

        return $this->db->insert($table, $data);
    }

    private function getTimestamp($date, $end = false)
    {
        list($year, $month, $day) = explode('-', $date);

        return $end 
                ? mktime(22, 0, 0, $month + 1, 0, $year) 
                : mktime(22, 0, 0, $month, $day, $year);
    }
}