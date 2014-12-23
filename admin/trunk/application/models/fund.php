<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 资金模型
 * 
 * @author kami
 * @package Fund
 */
class Fund extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 新增资金表数据
     */
    public function add($args)
    {
        $params = array_merge(array(
            'uid'     => null,
            'wallet'  => 0,
            'account' => 0
        ), $args);

        if (empty($params['uid'])) {
            return false;
        }

        $table = $params['role'] == 1 ? '`foodcar_merchant_fund`' : '`foodcar_staff_fund`';
        $field = $params['role'] == 1 ? 'merchant_id' : 'staff_id';
        $data[$field] = $params['uid'];
        $data['wallet']  = $params['wallet'];
        $data['account'] = $params['account'];

        return $this->db->insert($table, $data);
    }
}