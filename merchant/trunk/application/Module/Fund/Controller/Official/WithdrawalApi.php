<?php 

namespace Module\Fund\Controller\Official;

use Application\Controller\Front;

class WithdrawalApi extends Front
{
    private $itemId;

    public function __construct()
    {
        parent::__construct();

        if (empty($_POST['id'])) {
            return $this->response->json(array(
                'code'    => 'WITHDRAWAL.MISSING_PARAMS',
                'message' => '缺少必要的参数',
            ));
        }
        $this->itemId = $_POST['id'];

        $this->model = $this->model(':Withdrawal');
    }

    /**
     * 提现申请通过
     */
    public function pass()
    {
        $result = $this->model->update(array(
            'status' => 2,
            'accepted_time' => REQUEST_TIME,
        ), $this->itemId);
        if ($result === false) {
            return $this->response->json(array(
                'code'    => 'WithdrawalApi.ERROR_CONFIRM',
                'message' => '确认失败'
            ));
        }

        return $this->response->json(array(
            'code'    => 'OK',
            'message' => '确认成功'
        ));
    }

    /**
     * 提现申请驳回
     */
    public function reject()
    {
        $result = $this->model->update(array(
            'status' => -1,
            'accepted_time' => REQUEST_TIME,
        ), $this->itemId);
        if ($result === false) {
            return $this->response->json(array(
                'code'    => 'WithdrawalApi.ERROR_UPDATE_STATUS',
                'message' => '申请驳回失败'
            ));
        }

        return $this->response->json(array(
            'code'    => 'OK',
            'message' => '申请驳回成功'
        ));
    }
}