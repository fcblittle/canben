<?php

namespace Module\Fund\Controller\Official;

use Application\Controller\Front;

class SalaryApi extends Front
{
    public function __construct()
    {
        parent::__construct();
    }

    public function doAllocate($data)
    {
        $data = $_POST;

        $model = $this->model(':Salary');

        if (empty($data['id'])) {
            $this->response->json(array(
                'code'    => 'ALLOCATE.MISSING_NECCESSARY_PARAMS',
                'message' => '缺少必要的参数',
            ));
        }

        // 获取分配项
        $item = $model->getItem(array(
            'id' => $data['id']
        ));
        if ($item === false) {
            $this->response->json(array(
                'code'    => 'ALLOCATE.SQL_ERROR',
                'message' => '数据库查询失败',
            ));
        }

        // 发放工资 && 修改状态
        $result = $model->pay4Salary($item);
        if ($result === false) {
            $this->response->json(array(
                'code'   => 'SYS.SQL_ERROR',
            ));
        }

        $this->response->json(array(
            'code' => 'OK'
        ));
    }
}