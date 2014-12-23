<?php

namespace Module\Fund\Controller;

class Salary extends Fund
{
    private $dinerInfo = array();

    public function __construct()
    {
        parent::__construct();

        if(! $this->checkPermission()) {
            $this->message->set('您无此模块权限！', 'error');
            $this->response->redirect("/fund/{$this->user->type}/index");
        }

        $this->model = $this->model(':Salary');
        $this->view->active = "fund/salary/index";

        // 餐车信息
        $dinerInfo = $this->getDinerInfo();

        // 员工信息
        if ($this->user->type === 'manager') {
            $dinerStaff = $this->getDinerStaff($this->user->dinerId);
            if ($dinerStaff === false) {
                $this->message->set('获取餐车员工信息错误', 'error');
                $this->response->redirect('/fund/manager/index');
            }
            $this->view->dinerStaff = $dinerStaff;
        }

        $this->view->dinerInfo = $this->dinerInfo;
        $this->view->styles[] = 'module/fund/css/salary.css';
    }

    /**
     * 详情页
     */
    public function index()
    {
        // var_dump($this->user);die;
        $conds = $this->getConditions();

        $pager = $this->com('System:Pager/Pager');

        $result = $this->model->getItemList($conds);
        if ($result === false) {
            $this->message->set('查询数据库失败', 'error');
            $this->response->redirect('/fund/salary/index');
        }
        $conds['pager']['total'] = $result['total'];

        $list = $this->formatData($result['list']);

        $this->view->pager = $pager->render($conds['pager']);
        $this->view->list = $list;
        $this->view->render(':salary.index');
    }

    /**
     * 获取查询条件
     * 
     * @param method get
     * @property-read array $dinerInfo 餐车信息
     */
    protected function getConditions()
    {
        $conds = array();

        $conds['pager'] = array(
            'page'  => ! empty($_GET['page']) ? $_GET['page'] : 0,
            'limit' => ! empty($_GET['limit']) ? $_GET['limit'] : 20
        );

        $date['end'] = ! empty($_GET['date']['end']) ? $_GET['date']['end'] : date('Y-m');
        $conds['date']['beginning'] = ! empty($_GET['date']['beginning']) 
                                        ? $this->getDateBeginning($_GET['date']['beginning'])
                                        : 0;
        $conds['date']['end']       = ! empty($_GET['date']['end']) 
                                        ? $this->getDateEnd($_GET['date']['end'])
                                        : $this->getDateEnd(date('Y-m'));

        /*var_dump(date('Y-m-d H:i:s', $conds['date']['beginning']));
        var_dump(date('Y-m-d H:i:s', $conds['date']['end']));
        die;*/

        foreach ($this->dinerInfo as $item) {
            $conds['dinerIds'][] = $item->id;
        }

        if (! empty($_GET['status']) 
            && is_numeric($_GET['status'])) {
            $conds['status'] = ($_GET['status'] == 1) ? 'IS' : 'IS NOT';
        }

        return $conds;
    }

    /**
     * 获取用户关联餐车信息
     * 
     * @param void
     * @property-read array $user 用户信息
     * @property-write array $dinerInfo 用户信息
     * 
     * @return array|object
     */
    protected function getDinerInfo()
    {
        $result = $this->model->getDinerRelatedInfo(array(
            'fields'      => 'diner.id, diner.diner_name, staff.realname',
            'uid'         => $this->user->id,
            'accountType' => $this->user->type,
            'diner_id'    => ! empty($this->user->dinerId) ? $this->user->dinerId : null
        ));
        if ($result === false) {
            $this->message->set('获取餐车信息失败！', 'error'); 
            $this->response->redirect('/');
        }
        
        $this->dinerInfo = $result;
    }

    /**
     * 获取餐车员工信息(经营者)
     *
     * @param int $diner_id 餐车id
     * @return bool|array $dinerStaff
     */
    protected function getDinerStaff($diner_id)
    {
        $result = $this->model->getDinerStaff(array(
            'fields'   => 'id, diner_id, role, username, realname',
            'diner_id' => $diner_id
        ));
        if ($result === false) {
            return false;
        }

        $dinerStaff = array();
        foreach ($result as $item) {
            if ($item->role == 1) {
                $dinerStaff[1][] = $item;
            } else if ($item->role == 2) {
                $dinerStaff[2][] = $item;
            }
        }

        return $dinerStaff;
    }

    /**
     * 分配明细
     * 
     * @param  method post
     * @return json
     */
    public function allocationList()
    {
        if (empty($_POST['diner_id'])
            || empty($_POST['date'])) {
            echo json_encode(array(
                'code'    => 'SYS.INVAILD_INPUT',
                'message' => '无效的参数！'
            ));
            exit();
        }

        $diner_id = $_POST['diner_id'];

        // 餐车 用户关联
        $diner = $this->getRelation();
        if ($diner === false 
            || ! array_key_exists($diner_id, $diner)) {
            echo json_encode(array(
                'code'    => 'SYS.PERMISSION_DENY',
                'message' => '您无此权限！'
            ));
            exit();
        }

        $result = $this->model->getItem(array(
            'diner_id'     => $diner_id,
            'dateInterval' => array(
                'beginning' => $this->getDateBeginning($_POST['date']),
                'end'       => $this->getDateEnd($_POST['date']),
            )
        ));
        if ($result === false) {
            echo json_encode(array(
                'code'    => 'ALLOCATION.ERROR_GET_ALLOCATION_LIST',
                'message' => '获取工资分配失败，请稍后重试',
            ));
            exit();
        }

        echo json_encode(array(
            'code'    => 'OK',
            'content' => array(
                'diner'      => $diner[$diner_id],
                'allocation' => json_decode($result->allocation)
            )
        ));
    }

    /**
     * 验证餐车与用户是否关联
     * 
     * @param int $diner_id
     * @return array $result
     */
    protected function getRelation($diner_id = null, $return = 'info')
    {
        switch ($return) {
            case 'bool':
                if ($this->user->type === 'manager' 
                    && $this->user->dinerId == $diner_id) { // 经营者
                    $result = true;
                } else if ($this->user->type === 'merchant') { // 商户
                    $result = $this->model->getDinerRelatedInfo(array(
                        'fields' => 'diner.id, diner.merchant_id, diner.diner_name',
                        'uid'    => $this->user->id,
                        'accountType' => $this->user->type
                    ));
                    
                    $result = array_key_exists($diner_id, $result);
                } else 
                    $result = false;
                break;
            case 'info':
                $result = $this->model->getDinerRelatedInfo(array(
                    'fields' => 'diner.id, diner.diner_name',
                    'uid'    => $this->user->id,
                    'accountType' => $this->user->type,
                    'diner_id' => ! empty($this->user->dinerId) ? $this->user->dinerId : null,
                ));
                break;
            default:
                $result = $this->model->getDinerRelatedInfo(array(
                    'fields' => 'diner.id, diner.diner_name',
                    'uid'    => $this->user->id,
                    'accountType' => $this->user->type,
                    'diner_id' => ! empty($this->user->dinerId) ? $this->user->dinerId : null,
                ));
                break;
        }

        return $result;
    }

    /**
     * 获取扣项明细
     * 
     * @param array $args
     * @return array
     */
    public function deductionList($args)
    {
        $args = $_POST;

        if (empty($args['diner_id']) 
            || empty($args['date'])) {
            echo json_encode(array(
                'code'    => 'SYS.INVAILD_INPUT',
                'message' => '错误的输入！'
            ));
            exit();
        }

        // 验证权限
        $diner = $this->getRelation();
        if ($diner === false 
            || ! array_key_exists($args['diner_id'], $diner)) {
            echo json_encode(array(
                'code'    => 'SYS.PERMISSION_DENY',
                'message' => '您无此权限！'
            ));
            exit();
        }

        // 获取扣项明细
        $result = $this->model->getDeductions(array(
            'fields'   => 'deduction.amount, type.name',
            'diner_id' => $args['diner_id'],
            'dateInterval' => array(
                'beginning' => $this->getDateBeginning($args['date']),
                'end'       => $this->getDateEnd($args['date']),
            ),
        ));
        if ($result === false) {
            echo json_encode(array(
                'code'    => 'SYS.SQL_ERROR',
                'message' => '数据库查询失败！'
            ));
            exit();
        }

        echo json_encode(array(
            'code'    => 'OK',
            'content' => array(
                'diner' => $diner[$args['diner_id']],
                'deduction'  => $result,
            ),
        ));
    }

    /**
     * 格式化数据
     */
    private function formatData($list)
    {
        if (empty($list)) {
            return array();
        }
        /*var_dump($this->dinerInfo);die;
        var_dump($list);die;*/

        foreach ($list as $key => $item) {
            $list[$key]->manager = $this->dinerInfo[$item->diner_id]->realname;
            $list[$key]->allocation = $item->allocation;
            $list[$key]->status = empty($item->status) ? '未发放' : '已发放';
            $list[$key]->timeRecord = date('Y-m', $item->timeRecord);
        }

        return $list;
    }

    /**
     * 解析分配项
     */
    /*private function parseAllocation($allocation)
    {
        if (empty($allocation)) {
            return;
        }

        // todo: 解析分配项
        $allocation = json_decode($allocation);die;
        var_dump($allocation);die;
    }*/

    /**
     * 获取开始日期
     * 
     * @param string $date 日期 <rules: required>
     * @return string
     */
    private function getDateBeginning($date)
    {
        if (empty($date)) {
            $this->message->set('错误的时间格式', 'error');
            $this->response->redirect('/fund/salary/index');
        }

        // $monthBeginning = $monthEnd = 0;
        // $yearBeginning  = $yearEnd  = 0;
        $monthBeginning = $yearBeginning = 0;

        list($yearBeginning, $monthBeginning) = explode('-', $date);

        return mktime(0, 0, 0, $monthBeginning, 1 ,$yearBeginning);
    }

    /**
     * 获取结束日期
     * 
     * @param string $date 日期 <rules: required>
     * @return string
     */
    private function getDateEnd($date)
    {
        if (empty($date)) {
            $this->message->set('错误的时间格式', 'error');
            $this->response->redirect('/fund/salary/index');
        }

        $monthEnd = $yearEnd = 0;

        list($yearEnd, $monthEnd) = explode('-', $date);

        return mktime(0, 0, 0, $monthEnd + 1, 1 ,$yearEnd);
    }
}