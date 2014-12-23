<?php

namespace Module\Fund\Model;

use System\Model;

class Merchant extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_merchant_fund`';
        $this->tableVariation = '`foodcar_merchant_fund_variation`';
    }

    /**
     * 获取商户资金
     */
    public function getItem($args)
    {
        // 
    }

    /**
     * 获取商户资金变动
     * 
     * @param array $args
     * - int merchantId  商户id
     * - string accountType 账户类型
     * 
     * - string fields 字段
     * - string order  排序方式
     * - string join   外联表
     * 
     * - array pager 分页
     */
    public function getItemVariationList($args)
    {
        $conds = $joinTable = '';
        $binds = array();

        $params = array_merge(array(
            'fields'      => 'v.*',
            'order'       => 'created DESC',
            'join'        => array(),
            'merchantId'  => null,
            'accountType' => null,
            'date'        => null
        ), $args);

        // 多表组合
        if (! empty($params['join'])) {
            $joinTable .= " LEFT JOIN {$params['join']['table']}
                            ON {$params['join']['alias']}.{$params['join']['tableKey']} = v.{$params['join']['foreignKey']}";

            $params['fields'] = empty($params['join']['fields']) ? $params['fields'] : $params['fields'] . ",{$params['join']['fields']}";
        }

        if ($params['merchantId'] != null) {
            $conds .= " AND v.merchant_id=?";
            $binds[] = $params['merchantId'];
        }
        if ($params['accountType'] != null) {
            $conds .= " AND v.accountType=?";
            $binds[] = $params['accountType'];
        }
        if ($params['date'] != null) {
            $conds .= " AND v.created BETWEEN ? AND ?";
            $binds[] = $params['date']['min'];
            $binds[] = $params['date']['max'];
        }

        $sql = "SELECT {$params['fields']}
                FROM {$this->tableVariation} AS v
                {$joinTable}
                WHERE 1 $conds
                ORDER BY {$params['order']}";

        $sqlc = "SELECT COUNT(*) AS total
                FROM {$this->tableVariation} AS v
                {$joinTable}
                WHERE 1 $conds";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->total
        );
    }
}