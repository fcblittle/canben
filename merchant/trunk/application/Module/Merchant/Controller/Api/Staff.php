<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 菜品相关API
 */
class Staff extends AppApi {

    private $model = null;

    public function init() {
        $this->model = $this->model(':Staff');
    }
    
    /**
     * 获取菜品
     */
    public function getItem($args = array()) {
        $id = (int) $args['id'];
        $fields = $args['fields'] ?: '*';
        if (! $id) {
            return $this->export(array(
                'code' => 400, 
                'message' => 'Missing ID'
            ));
        }
        $item = $this->model->getItemById($id, 0, $fields);
        $this->formatItem($item);
        
        return $this->export($item);
    }

    /**
     * 获取店员
     */
    public function getItemById($args)
    {
        $id = (int) $args['id'];
        $fields = $args['fields'] ?: '*';
        if (! $id) {
            return $this->export(array(
                'code' => 400, 
                'message' => 'Missing ID'
            ));
        }
        $item = $this->model->getItem($id, $fields);
        $this->formatItem($item);
        
        return $this->export($item);
    }
    
    /**
     * 获取列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'    => $args['page'] ?: 0,
            'limit'   => $args['limit'] ?: 15
        );
        $args['uid'] = $this->user->id;
        $result = $this->model->getItemList($args);

        // 若为manager则获得该餐车的店员
        if ($this->user->type === "manager") {
            $args['uid'] = $this->user->dinerId;
            $result = $this->model->getItemListByDinerId($args);
        }
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        if ($result['total']) {
            foreach ($result['list'] as & $v) {
                $this->formatItem($v);
            }
        }
        return $this->export(array('content' => $result));
    }

    /**
     * 获取多个item
     * @param array $args
     */
    public  function getItems($args = array()) {
        if (! is_array($args['id'])) {
            $id = rtrim(trim($args['id']), ',');
            $id = explode(',', $id);
        }
        if (! $id) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $items = $this->model->getItems($id, $this->user->id);
        if ($items['total']) {
            foreach ($items['list'] as & $v) {
                $this->formatItem($v);
            }
        }

        return $this->export(array('content' => $items));
    }

    /**
     * 获取全部
     */
    public function getAll($args = array()) {
        $items = $this->model->getAll(array('uid' => $this->user->id));

        return $this->export(array('content' => $items));
    }

    /**
     * 总数
     */
    public function total($args = array()) {
        if ($this->user->type === 'merchant') {
            $result = $this->model->getTotal($this->user->id);
        } else {
            //为manager
            $result = $this->model->getTotalByDinerId($this->user->dinerId);
        }
        

        return $this->export(array('content' => $result));
    }
    
    /**
     * 添加
     */
    public function add($args = array()) {
        $data = $args['data'] ?: $_POST;
        if (addslashes($data['pass']) !== addslashes($data['passV'])) {
            return $this->export(array(
                'code' => 250,
                'message' => "两次输入密码不一致"
            ));
        }
        $data = array(
            'merchant_id' => $this->user->type === "manager"?$this->model->getItemById($this->user->dinerId, 0, "merchant_id")->merchant_id:$this->user->id,
            'username'    => $data['username'],
            'realname'    => $data['realname'],
            'pass'        => $data['pass'],
            'role'        => $data['role'] ?: 2,
            'diner_id'    => $data['diner_id']?:$this->user->dinerId,
            'status'      => $data['status'] ?: 0,
            'created'     => REQUEST_TIME
        );
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $this->formatData($data);

        //判断是否要替换manager
        $diner_id = $data['diner_id'];
        $uid = $this->user->id;
        // var_dump($this->user);die;
        if ($data['role'] == '1') {
            // echo '为manager';
            if ($this->user->type === 'manager') {
                return $this->export(array('code' => 500));
            }
            // 搜索该餐车是否有管理员
            $manager = $this->model->getDinerManager($diner_id);
            if ($manager === false) {
                //没有manager
                $result = $this->model->add($data);
            } else {
                //先删除该管理员，再添加管理员
                $this->model->update($manager->id, $uid, array('status' => -1));

                $result = $this->model->add($data);
            }
            // 为foodcar_staff_fund表添加一条记录
            if ($result) {
                $sql = "INSERT INTO `foodcar_staff_fund` (`staff_id`, `wallet`, `account`) VALUES ({$result}, 0, 0)";
                $this->db->execute($sql);
            }


        } else {
            //添加店小二
            // echo '为店小二';
            $result = $this->model->add($data);
            // 为foodcar_staff_fund表添加一条记录
            if ($result) {
                $sql = "INSERT INTO `foodcar_staff_fund` (`staff_id`, `wallet`, `account`) VALUES ({$result}, 0, 0)";
                $this->db->execute($sql);
            }
        }

        
        if ($result === false) {
            $err = $this->db->sth->errorInfo();
            return $this->export(array(
                'code' => 500,
                'message' => $err[2]
            ));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 更新
     */
    public function update($args = array()) {
        $id = (int) $args['id'];
        if (! $id) {
            return $this->export(array(
                'code' => 400, 
                'message' => 'Missing ID'
            ));
        }
        $data = $args['data'] ?: $_POST;
        if (addslashes($data['pass']) !== addslashes($data['passV'])) {
            return $this->export(array(
                'code' => 250,
                'message' => "两次输入密码不一致"
            ));
        }
        unset($data['passV']);
        $errors = $this->validate($data, $id);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $this->formatData($data);
        
        //判断是否要替换manager
        $diner_id = $data['diner_id'];
        $uid = $this->user->id;
        if ($data['role'] == '1') {
            // echo '为manager';
            // 搜索该餐车是否有管理员
            $manager = $this->model->getDinerManager($diner_id);
            if ($manager === false) {
                //没有manager
                $result = $this->model->update($id, $this->user->type === "manager"?$this->model->getItemById($this->user->dinerId, 0, "merchant_id")->merchant_id:$this->user->id, $data);
            } else {
                //先删除该管理员，再添加管理员
                $data = array_merge((array) $manager, $data);
                
                $this->model->delete($data['id'], $uid);
                $this->model->update($manager->id, $uid, array('status' => -1));
                
                $result = $this->model->add($data);
            }
            

        } else {
            $result = $this->model->update($id, $this->user->type === "manager"?$this->model->getItemById($this->user->dinerId, 0, "merchant_id")->merchant_id:$this->user->id, $data);

        }
        
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 删除
     */
    public function delete($args = array()) {
        $id = $args['id'];
        if (empty($id)) {
            return $this->export(array(
                'code'    => 'STAFF.EMPTY_PARAMS',
                'message' => '参数不能为空'
            ));
        }
        $result = $this->model->del($id);
        
        return $this->export($result);
    }

    /**
     * 格式化数据
     *
     * @param $data
     * @return mixed
     */
    private function formatData(& $data) {
        if (isset($data['pass']) && empty($data['pass'])) {
            unset($data['pass']);
        }
        if ($data['pass']) {
            $hash = $this->com('System:Crypt\Hash');
            $data['salt'] = $hash->randomString(6);
            $data['pass'] = $hash->password($data['pass'], $data['salt']);
        }

        return $data;
    }
    
    /**
     * 验证
     */
    private function validate($data, $id = 0) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $validator->registerValidator(
            'uniqueUsername',
            array($this, 'validateUsername')
        );
        $rules = array(
            'username' => array(
                'name' => '用户名',
                'value' => $data['username'],
                'rules' => array(
                    'required' => array(),
                    'phone' => array(),
                    'maxLength' => array('value' => 11, 'fullWidth' => true),
                    'uniqueUsername' => array('id' => $id)
                )
            ),
            'realname' => array(
                'name' => '姓名',
                'value' => $data['realname'],
                'rules' => array(
                    'required' => array(),
                    'maxLength' => array('value' => 10, 'fullWidth' => true)
                )
            ),
            /*
            'phone' => array(
                'name' => '手机号',
                'value' => $data['phone'],
                'rules' => array(
                    'required' => array(),
                    'phone' => array()
                )
            ),
            */
        );
        if (! $id || $data['pass']) {
            $rules['pass'] = array(
                'name' => '密码',
                'value' => $data['pass'],
                'rules' => array(
                    'required' => array(),
                    'minLength' => array('value' => 6, 'fullWidth' => true),
                    'maxLength' => array('value' => 20, 'fullWidth' => true)
                )
            );
        }
        if (isset($data['diner_id'])) {
            $rules['diner_id'] = array(
                'name' => '餐车id',
                'value' => $data['diner_id'],
                'rules' => array(
                    'required' => array(),
                    'int' => array()
                )
            );
        }
        $validator->validate($rules);
        
        return $validator->getErrors();
    }

    /**
     * 格式化item
     *
     * @param $item
     * @return mixed
     */
    private function formatItem(& $item) {
        if ($item->cate_id) {
            $item->cate_id = explode(',', $item->cate_id);
        }
    }

    /**
     * 验证用户名
     * @param $field
     * @param $params
     */
    public function validateUsername($field, $params) {
        $params = array_merge(array(
            'message' => '用户名已存在'
        ), $params);
        $modelMerchant = $this->model(':Merchant');
        $staff = $this->model->getItemByName($field['value'], 'id');
        $merchant = $modelMerchant->getItemByName($field['value'], 'id');
        $match = $params['id']
            ? ! (($staff && $staff->id != $params['id'])
                || ($merchant && $merchant->id != $params['id']))
            : ! ($staff || $merchant);

        return array(
            'match' => $match,
            'message' => $match ? 'ok' : $params['message']
        );
    }
}