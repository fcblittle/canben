<?php

namespace Module\Fund\Model;

use System\Model;

class Salary extends Fund
{
    public function __construct()
    {
        parent::__construct();

        $this->table = '`foodcar_diner_monthly_statistics`';
        $this->tableDiner = '`foodcar_diner`';
        $this->tableStaff = '`biz_staff`';
        $this->tableDeduction = '`foodcar_diner_deduction`';
        $this->tableDeductionType = '`foodcar_diner_deduction_type`';
    }

    /**
     * 获取月工资列表
     * 
     * @param array $args
     * - array dinerIds 餐车id <rules: required>
     * - array date 日期区间 <rules: required>
     *   - int beginning 开始时间
     *   - int end 结束时间
     * - int status  状态 <value: (0, 1)>
     *
     * - array pager 分页项 <rules: required>
     * 
     * - string fields 字段 <default: '*'>
     * - string orderby 排序 <default: 'timeRecord DESC'>
     * 
     * @return bool|array
     */
    public function getItemList($args)
    {
        if (empty($args['date'])
            || empty($args['pager'])) {
            return false;
        }

        $params = array_merge(array(
            'fields'  => '*',
            'orderby' => 'timeRecord DESC',
            'status'  => null,
            'pager'   => array(),
            'date'    => array(),
        ), $args);

        $conds = '';
        $binds = array();

        $ids = implode(',', $args['dinerIds']);
        $conds = " diner_id IN ($ids)";

        $conds .= " AND timeRecord BETWEEN ? AND ?";
        $binds[] = $args['date']['beginning'];
        $binds[] = $args['date']['end'];

        if (! empty($params['status'])) {
            $conds .= " AND allocation {$params['status']} null";
        }

        $sql = "SELECT {$params['fields']}
                FROM {$this->table}
                WHERE $conds
                ORDER BY {$params['orderby']}";

        $sqlc = "SELECT COUNT(*) AS total
                FROM {$this->table}
                WHERE $conds";

        return array(
            'list'  => $this->db->pagerQuery($sql, $params['pager'], $binds),
            'total' => $this->db->fetch($sqlc, $binds)->total
        );
    }

    /**
     * 获取单条记录
     * 
     * @param array $args
     * - int id id 
     * - int diner_id 餐车id
     * - int timeRecord 记录时间
     * @return bool|array $result
     */
    public function getItem($args = array())
    {
        $params = array_merge(array(
            'fields' => '*',
            'id'       => null,
            'diner_id' => null,
            'dateInterval' => array(),
        ), $args);

        $conds = '';
        $binds = array();

        if (! empty($params['id'])) {
            $conds .= " AND id = ?";
            $binds[] = $params['id'];
        }
        if (! empty($params['diner_id'])) {
            $conds .= " AND diner_id = ?";
            $binds[] = $params['diner_id'];
        }
        if (! empty($params['dateInterval'])) {
            $conds .= " AND timeRecord BETWEEN ? AND ?";
            $binds[] = $params['dateInterval']['beginning'];
            $binds[] = $params['dateInterval']['end'];
        }

        $sql = "SELECT {$params['fields']} 
                FROM {$this->table} 
                WHERE 1 $conds";

        return $this->db->fetch($sql, $binds);
    }

    /**
     * 获取餐车
     * 
     * @param array $args
     * - int uid 用户id <rules: required>
     * - string accountType 用户类型 <rules: required>
     * - int diner_id 餐车id
     * 
     * - string fields 所需字段 <default: '*'>
     * @return bool|array dinerIds
     */
    public function getDinerRelatedInfo($args)
    {
        $args['fields'] = ! empty($args['fields']) ? $args['fields'] : '*';

        $conds = '';
        $binds = array();

        if ($args['accountType'] === 'manager' 
            && ! empty($args['diner_id'])) {
            $conds  .= " AND diner.id = ?";
            $binds[] = $args['diner_id'];
        }

        if ($args['accountType'] === 'merchant'
            && ! empty($args['uid'])) {
            $conds  .= " AND diner.merchant_id = ?"; 
            $binds[] = $args['uid'];
            $conds  .= " AND store_stauts = 1";
        }
        $sql = "SELECT {$args['fields']} 
                FROM {$this->tableDiner} AS diner
                LEFT JOIN {$this->tableStaff} AS staff
                ON diner.id = staff.diner_id AND staff.role = 1
                WHERE 1 $conds";

        $result = $this->db->fetchAll($sql, $binds);
        if ($result === false) {
            return false;
        }

        $diner = array();
        foreach ($result as $item) {
            $diner[$item->id] = $item;
        }

        return $diner;
    }

    /**
     * 获取餐车员工信息
     * 
     * @param array $args 
     * @return bool|array
     */
    public function getDinerStaff($args)
    {
        $params = array_merge(array(
            'fields'   => '*',
            'diner_id' => null,
        ), $args);

        $sql = "SELECT {$params['fields']} 
                FROM {$this->tableStaff} 
                WHERE diner_id = ? 
                AND status = 1";

        return $this->db->fetchAll($sql, array($params['diner_id']));
    }

    /**
     * 工资分配
     * 添加分配记录
     * 转账
     * 
     * @param array $args
     * @return bool
     */
    public function allocate($args, $id, $diner_id)
    {
        // var_dump($id);die;
        $this->db->beginTransaction();
        $allocation = array();

        // 转账
        foreach ($args as $item) {
            /*$result = $this->doTransfer(array(
                'role'            => 'manager',
                'uid'             => $item->id,
                'diner_id'        => $diner_id,
                'variationType'   => 19,
                'accountType'     => 'account',
                'accountTo'       => 'official',
                'roleAccountType' => 'manager',
                'roleAccountTo'   => 'official',
                'amount'          => $item->salary,
                'created'         => REQUEST_TIME
            ), '+');
            if ($result === false) {
                $this->db->rollback();
                return false;
            }*/

            $allocation[] = (array) $item;
        }

        // 添加记录
        $sql = "UPDATE {$this->table} 
                SET allocation = ? 
                WHERE id = ?";
        $result = $this->db->execute($sql, array(json_encode($allocation), $id));
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        return $this->db->commit();
    }

    /**
     * 获取扣项明细
     * 
     * @param array $args
     * - int diner_id 餐车id <rules: required>
     * - array dateInterval <rules: required>
     * @param array $args
     * @return array
     */
    public function getDeductions($args)
    {
        $args['fields'] = ! empty($args['fields']) ? $args['fields'] : '*';

        $conds = '';
        $binds = array();

        $conds .= ' diner_id = ?';
        $binds[] = $args['diner_id'];

        $conds .= ' AND timeRecord BETWEEN ? AND ?';
        $binds[] = $args['dateInterval']['beginning'];
        $binds[] = $args['dateInterval']['end'];

        $sql = "SELECT {$args['fields']} 
                FROM {$this->tableDeduction} AS deduction
                LEFT JOIN {$this->tableDeductionType} AS type
                ON deduction.typeId = type.id
                WHERE $conds";

        return $this->db->fetchAll($sql, $binds);
    }

    /**
     * 工资发放
     */
    public function pay4Salary($data)
    {
        if (empty($data)) {
            return array(
                'code'    => 'ALLOCATE.MISSING_ALLOCATION',
                'message' => '缺少必要的参数'
            );
        }
        $allocation = json_decode($data->allocation);

        $this->db->beginTransaction();

        foreach ($allocation as $item) {
            $result = $this->doTransfer(array(
                'role'            => 'manager',
                'uid'             => $item->id,
                'diner_id'        => $data->diner_id,
                'variationType'   => 19,
                'accountType'     => 'account',
                'accountTo'       => 'official',
                'roleAccountType' => 'manager',
                'roleAccountTo'   => 'official',
                'amount'          => $item->salary,
                'created'         => $data->timeRecord
            ), '+');
            if ($result === false) {
                $this->db->rollback();
                return false;
            }
        }

        $result = $this->db->update(
            $this->table, 
            array('status' => 1), 
            array('id = ' . $data->id)
        );
        if ($result === false) {
            $this->db->rollback();
            return false;
        }

        return $this->db->commit();
    }
}