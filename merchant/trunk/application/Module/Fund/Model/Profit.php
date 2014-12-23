<?php

namespace Module\Fund\Model;

use System\Model;

class Profit extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_diner_daily_yield`';
       
    }


    /**
     * 取得列表
     */
    public function getItemList($args = array())
    {
        $conds = '';

        $params = array_merge(array(
            'fields'      => '*',
            'order'       => 'timeRecord DESC',
            'dinerIds'    => array(),
            'start'       => 0,
            'end'         => mktime(23, 59, 59, date("n"),  date("j"), date("Y"))
        ), $args);

        if (! empty($params['dinerIds'])) {
            $ids = implode(',', $params['dinerIds']);
            $conds .= " AND diner_id IN ({$ids})";
        }

        $conds .= " AND timeRecord BETWEEN {$params['start']} AND {$params['end']}";

        $sql = "SELECT {$params['fields']}
                FROM {$this->table}
                WHERE 1 {$conds}
                ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(*) AS total
                FROM {$this->table}
                WHERE 1 {$conds}";

        if (! empty($params['pager'])) {
            return array(
                'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
                'total' => $this->db->fetch($sqlc, $binds)->total
            );
        }

        return $this->db->fetchAll($sql);
    }
}