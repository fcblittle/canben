<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class Profit extends AppApi
{
    public function init()
    {
        $this->model = $this->model(':Profit');
    }

    /**
     * 获取利润数据
     */
    public function getItemList($args)
    {
        $params = array_merge(array(
            'dinerIds'    => ! empty($this->user->dinerId) ? array($this->user->dinerId) : array(),
            'start'       => 0,
            'end'         => mktime(23, 59, 59, date("n"),  date("j"), date("Y"))
        ), $args);

        $params['pager'] = array(
            'page'  => ! empty($args['pager']['page']) ? $args['pager']['page'] : 0,
            'limit' => ! empty($args['pager']['limit']) ? $args['pager']['limit'] : 10
        );

        $result = $this->model->getItemList($params);
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
}