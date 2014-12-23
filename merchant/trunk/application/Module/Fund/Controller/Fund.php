<?php
/**
 * 资金管理抽象类
 * 
 * @abstract
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller;

use Application\Controller\Account;

abstract class Fund extends Account
{
    public function __construct()
    {
        parent::__construct();

        $this->view->active = "fund/{$this->user->type}/index";
    }

    /**
     * 列表页
     */
    abstract public function index();

    /**
     * 初始化表单数据
     * 
     * @
     */
    protected function prepareFormData()
    {
        $type = (empty($_GET['type']) || $_GET['type'] == 'wallet') ? 'wallet' : 'account';
        // 获取资金变动类型
        $variationType = $this->call(
            'api/fund/fund/getVariationType',
            array('interval' => 1)
        );
        if ($variationType['code'] != 200) {
            $this->error('数据库查询失败！');
        }

        // 获取统计数据
        $frequency = ! empty($_GET['date']) ? 'Day' : 'Month';
        $statistics = $this->call(
            'api/fund/fund/getStatistics',
            array(
                'accountType' => $type,
                'frequency' => $frequency,
                'date'      => ! empty($_GET['date']) ? $_GET['date'] : null
            )
        );

        $this->view->variationType = $variationType['content'];
        $this->view->statistics = array(
            'frequency' => $frequency,
            'data'     => $statistics['content']
        );
    }

    /**
     * 获取转账类型
     */
    /*protected function getVariationType()
    {
        $result = $this->call(
            'api/fund/fund/getVariationType',
            array('interval' => 1)
        );

        if ($result['code'] != 200) {
            $this->error('数据库查询失败！');
        }

        return $result['content'];
    }*/

    /**
     * 数据处理
     */
    public function getFormatData($variationList)
    {
        $temp = array();
        foreach ($variationList as $key => $item) {
            $item->accountType = ($item->accountType === 'wallet') ? '我的钱包' : '经营账户';
            $item->created = date('Y-m-d H:i', $item->created);

            $temp[] = $item;
        }

        return $temp;
    }
}