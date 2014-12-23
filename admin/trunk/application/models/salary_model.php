<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 工资模型
 */

class Salary_model extends CI_model
{
    private $table = '`foodcar_diner_monthly_statistics`';
    private $tableDiner = '`foodcar_diner`';
    private $tableMerchant = '`foodcar_merchant`';

    public function __construct()
    {
        parent::__construct();

        // 
    }

    /**
     * 根据id 或 日期 获取值
     * 
     * @param array $args
     * @return array $result
     */
    public function getItem($args)
    {
        $conds = '';
        $binds = array();

        if (! empty($args['id'])) {
            $conds .= " AND id = ?";
            $binds[] = $args['id'];
        }
        if (! empty($args['dateInterval'])) {
            $conds .= " AND timeRecord BETWEEN ? AND ?";
            $binds[] = $args['dateInterval']['beginning'];
            $binds[] = $args['dateInterval']['end'];
        }

        $sql = "SELECT * FROM {$this->table} WHERE 1 $conds";

        return $this->db->query($sql, $binds)->result();
    }

    /**
     * 获取工资列表
     *
     * @param array $args
     * #搜索条件
     *  - int status 工资发放状态 <enum: (0：未发放,1：已发放)>
     *  - array merchant 商户表字段条件(电话号码，商户名称...etc)
     *  - array diner    餐车表字段条件(餐车号，餐车名...etc)
     *  - array dateInterval 时间区间
     * #结果过滤 
     *  - string fields 搜索字段
     *  - string orderby 排序
     * #分页
     *  - array pager 分页项 <rules: required>
     * 
     * @return bool|array $result
     */
    public function get_item_list($args = array())
    {
        $conds = $limitStr = '';
        $binds = array();

        // var_dump($args);die;

        $params = array_merge(array(
            'fields'  => '*',
            'orderby' => 'statistic.timeRecord DESC, diner.id DESC',
            'status'  => null,
            'merchant'=> array(),
            'diner'   => array(),
            'dateInterval' => array()
        ), $args);

        // 搜索条件
        if (! empty($params['status'])) {
            $conds .= " AND statistic.status = ?";
            $binds[] = $params['status'];
        }
        if (! empty($params['dateInterval'])) {
            $conds .= " AND statistic.timeRecord BETWEEN ? AND ?";
            $binds[] = $params['dateInterval']['beginning'];
            $binds[] = $params['dateInterval']['end'];
        }
        if (! empty($params['merchant'])) {
            foreach ($params['merchant'] as $key => $value) {
                $conds .= " AND merchant.$key LIKE ?";
                $binds[] = '%' . $value . '%';
            }
        }
        if (! empty($params['diner'])) {
            foreach ($params['diner'] as $key => $value) {
                $conds .= " AND diner.$key LIKE ?";
                $binds[] = '%' . $value . '%';
            }
        }

        // 分页字符串拼接
        $limitStr = " LIMIT " . ($params['pager']['page'] )* $params['pager']['limit'] . ", " . $params['pager']['limit'];

        $sql = "SELECT {$params['fields']}
                FROM {$this->table} AS statistic
                LEFT JOIN {$this->tableDiner} AS diner
                ON statistic.diner_id = diner.id
                LEFT JOIN {$this->tableMerchant} AS merchant
                ON diner.merchant_id = merchant.id
                WHERE 1 $conds
                ORDER BY {$params['orderby']}";

        $query = $this->db->query($sql . $limitStr, $binds);
        $cQuery = $this->db->query($sql, $binds);

        return array(
            'total' => $cQuery->num_rows(),
            'list'  => $query->result(),
        );
    }
}