<?php

namespace Module\Fund\Controller\Api;

use Application\Controller\AppApi;

class AllocateSalary extends AppApi
{
    private $date = '';
    private $amount = 0;
    private $dinerStaff = array();

    public function init()
    {
        if (! $this->checkPermission()) {
            $this->export(array(
                'code'    => 'SYS.PERMISSION_DENY',
                'message' => '您无此权限！'
            ));
        }
        
        $this->model = $this->model(':Salary');

        $this->dinerStaff = $this->model->getDinerStaff(array(
            'fields'   => 'id, realname, role',
            'diner_id' => $this->user->dinerId
        ));
    }

    /**
     * 工资分配
     * 
     * @param  method post
     * @return json
     */
    public function allocate()
    {
        // var_dump($_POST);die;

        $data = $this->getAllocation();
        if ($data === false) {
            return $this->export(array(
                'code'    => "SYS.INVAILD_INPUT",
                'message' => '错误的输入'
            ));
        }

        // 是否已发放 && 可分配工资 && 已分配
        $result = $this->checkAlloction();
        if ($result['check'] === false) {
            return $this->export(array(
                'code'    => 'SYS.SALARY_ALLOCATION_DONE',
                'message' => $result['message']
            ));
        }
        
        // 发放工资(分配记录 && 转账)
        $result = $this->model->allocate($this->dinerStaff, $result['id'], $this->user->dinerId);
        if ($result === false) {
            return $this->export(array(
                'code'    => 'ALLOCATION.ALLOCATE_ERROR',
                'message' => '利润分配失败，请稍后重试'
            ));
        }

        return $this->export(array(
            'code' => 'OK'
        ));
    }

    /**
     * 获取分配数据
     * 
     * @param method post
     * - string date 日期 <format: yyyy-mm>
     * - int allocation 用户分配 
     */
    private function getAllocation()
    {
        if ($this->user->type !== 'manager' 
            || empty($_POST['date']) ) {
            return false;
        }
        $this->date = $_POST['date'];
        unset($_POST['date']);

        // 获取分配项
        foreach ($this->dinerStaff as $key => $item) {
            if (array_key_exists($item->id, $_POST)) {
                $this->dinerStaff[$key]->salary = is_numeric($_POST[$item->id]) 
                                                    ? $_POST[$item->id]
                                                    : 0;

                $this->amount += $this->dinerStaff[$key]->salary;
            } else {
                $this->dinerStaff[$key]->salary = 0;
            }
        }

        return true;
    }

    /**
     * 验证是否已发放 或 已分配
     * 
     * @property-read string $date
     *
     * @return bool|array $result
     */
    protected function checkAlloction()
    {
        $result = $this->model->getItem(array(
            'diner_id'     => $this->user->dinerId,
            'dateInterval' => $this->getDateInterval(),
        ));

        // 验证余额
        if($result->salary - $result->deduction < $this->amount) {
            return array(
                'check'   => false,
                'message' => '可分配工资不足'
            );
        }

        // 是否分配 or 是否发放
        if (empty($result) || ! empty($result->allocation)) {
            return array(
                'check'   => false,
                'message' => '工资已分配！'
            );
        }

        return array(
            'id'      => $result->id
        );
    }

    /**
     * 获取日期区间
     * 
     * @property-read string $date
     * @return array $dateInterval
     */
    private function getDateInterval()
    {
        $year = $month = '';
        list($year, $month) = explode('-', $this->date);

        return array(
            'beginning' => mktime(0, 0, 0, $month, 1, $year),
            'end'       => mktime(0, 0, 0, $month + 1, 1, $year)
        );
    }

    private function checkPermission()
    {
        return ! empty($this->user) && ($this->user->type === 'manager');
    }
}