<?php
/**
 * 商户资金管理控制器
 * 
 * @author kami <huany63@gmail.com>
 */
namespace Module\Fund\Controller;

class Merchant extends Fund
{
    public function __construct()
    {
        parent::__construct();

        if (! $this->checkMerchantPermission()) {
            $this->message->set('您无此权限！', 'error');
            $this->response->redirect('/fund/manager/index');
        }
    }

    /**
     * 列表页
     */
    public function index()
    {
        $this->prepareFormData();
        $pager = $this->com('System:Pager/Pager');

        $pagerParam = array(
            'page'  => $_GET['page'] ?: 0,
            'limit' => $_GET['limit'] ?: 10
        );

        $result = $this->call(
            'api/fund/fund/getItemList',
            array(
                'role'        => $this->user->type,
                'merchantId'  => $this->user->id,
                'pager'       => $pagerParam,
                'date'        => $_GET['date'],
                'accountType' => $_GET['type'] ?: 'wallet'
            )   
        );
        if ($result['code'] != 200) {
            $this->error('数据库查询失败！');
        }

        $variationList = $this->getFormatData($result['content']['list']);

        $pagerParam['total'] = $result['content']['total'];

        $this->view->styles[] = 'module/fund/css/merchant.css';
        $this->view->account = array(
            'name' => '商户',
            'role' => 'merchant'
        );
        $this->view->variation = $variationList;
        $this->view->pager = $pager->render($pagerParam);
        $this->view->render(':fund.index');
    }
}