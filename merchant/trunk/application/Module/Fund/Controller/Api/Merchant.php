<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class Merchant extends AppApi
{
    public function init()
    {
        $this->model = $this->model(':Merchant');
    }

    public function getItemList($args)
    {
        $params = array_merge(array(
            'merchantId'  => $this->user->id,
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
            $params['date'] = $this->getFormatDate($params['date']);
        }

        $result = $this->model->getItemVariationList($params);
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
     * 获取当日区间时间戳
     */
    private function getFormatDate($date)
    {
        list($year, $month, $day) = explode('-', $date);

        return array(
            'min' => mktime(0, 0, 0 , $month, $day, $year),
            'max' => mktime(0, 0, 0 , $month, $day + 1, $year)
        );
    }
}