<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 扣项模型
 * 
 * @author fcblittle @kami
 * @package deduction
 */
class Shipping_model extends CI_Model 
{
    private $table = '`foodcar_diner_shipping`';
    private $tableDiner = '`foodcar_diner`';
    private $tableStaff = '`biz_staff`';
    private $tableArea = '`foodcar_diner_area`';
    private $tableCity = '`foodcar_diner_city`';

    public function __construct()
    {
        parent::__construct();
    }

    public function getItemList($args = array())
    {
        $params = array_merge(array(
            'fields'  => 'shipping.*, diner.diner_name, diner.merchant_name, staff.realname, city.id AS city_id, city.name AS city',
            'orderby' => 'ORDER BY shipping.shipping_time DESC, shipping.created DESC',
            'dinerId' => array(),
            'pager'   => array('page' => 0, 'limit' => 20),
        ), $args);

        $conds = '';
        $binds = array();

        if (! empty($params['dinerId'])) {
            $tmp = implode(', ', $params['dinerId']);
            $conds .= " AND shipping.diner_id IN ({$tmp})";
        }
        if (! empty($params['merchant'])) {
            $conds .= " AND diner.merchant_name LIKE ?";
            $binds[] = "%{$params['merchant']}%";
        }
        if (! empty($params['city'])) {
            $ids = implode(',', $params['city']);
            $conds .= " AND city.id IN ($ids)";
        }
        if (! empty($params['dateInterval'])) {
            $conds .= " AND shipping.shipping_time BETWEEN ? AND ?";
            $binds[] = $params['dateInterval']['beginning'];
            $binds[] = $params['dateInterval']['end'];
        }

        $conds .= " AND staff.role = 1"; 

        // 分页字符串拼接
        $limitStr = " LIMIT " . ($params['pager']['page'] )* $params['pager']['limit'] . ", " . $params['pager']['limit'];

        $sql = "SELECT {$params['fields']}
                FROM {$this->table} AS shipping
                LEFT JOIN {$this->tableDiner} AS diner
                ON shipping.diner_id = diner.id
                LEFT JOIN {$this->tableStaff} AS staff
                ON diner.id = staff.diner_id
                LEFT JOIN {$this->tableArea} AS area
                ON diner.area = area.id
                LEFT JOIN {$this->tableCity} AS city
                ON area.city_id = city.id
                WHERE 1 $conds 
                {$params['orderby']}";
        // var_dump($sql);die;
        return array(
            'total' => $this->db->query($sql, $binds)->num_rows(),
            'data'  => $this->db->query($sql . $limitStr, $binds)->result(),
        );
    }

    public function insert($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }
}