<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

class Promotion extends AppApi {

    public function init() {
        $this->model = $this->model(':Promotion');
    }

    /**
     * 获取单个
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
        $item = $this->model->getItemById($id, $this->user->merchant_id, $fields);
        if ($item === false) {
            return $this->export(array('code' => 500));
        }
        $this->formatItem($item);

        return $this->export(array('content' => $item));
    }

    /**
     * 获取列表
     */
    public function getItemList($args = array()) {
        $args['pager'] = array(
            'page'  => $args['page'] ?: 0,
            'limit' => $args['limit'] ?: 15
        );
        $args['uid'] = $this->user->merchant_id;
        $list = $this->model->getItemList($args);
        if ($list === false) {
            return $this->export(array('code' => 500));
        }
        if ($list['list']) {
            foreach ($list['list'] as & $v) {
                $this->formatItem($v);
            }
        }
        
        return $this->export(array('content' => $list));
    }

    /**
     * 获取菜品
     */
    public function getDishes($args = array()) {
        $ids = $args['ids'];
        if (! is_array($ids)) {
            $ids = explode(',', rtrim(trim($ids), ','));
        }
        if (! $ids) {
            return $this->export(array(
                'code' => 400,
                'message' => 'Missing ID'
            ));
        }
        $items = $this->model->getDishes($ids, $this->user->merchant_id);
        if ($items === false) {
            return $this->export(array('code' => 500));
        }

        return $this->export(array('content' => $items));
    }

    /**
     * 格式化item
     * @param $item
     */
    private function formatItem(& $item) {
        if ($item->start) {
            $item->startTimestamp = $item->start;
            $item->start = date('Y-m-d H:i:s', $item->start);
        }
        if ($item->end) {
            $item->endTimestamp = $item->end;
            $item->end   = date('Y-m-d H:i:s', $item->end);
        }
        if ($item->dish) {
            $item->dish = explode(',', $item->dish);
        }
    }

    /**
     * 添加
     */
    public function add($args = array()) {
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $data['created'] = REQUEST_TIME;
        $this->formatData($data);
        $result = $this->model->add($data);
        if ($result === false) {
            return $this->export(array('code' => 500));
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
        $data = $this->prepareData($args);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $this->formatData($data);
        $result = $this->model->update($id, $data);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 删除
     */
    public function delete($args = array()) {
        $id = (int) $args['id'];
        if (! $id) {
            return $this->export(array(
                'code' => 400, 
                'message' => 'Missing ID'
            ));
        }
        $result = $this->model->delete($id, $this->user->merchant_id);
        if ($result === false) {
            return $this->export(array('code' => 500));
        }
        return $this->export(array('content' => $result));
    }
    
    /**
     * 获取表单数据
     */
    private function prepareData($args = array()) {
        $data = $args['data'] ?: $_POST;

        return array(
            'merchant_id' => $this->user->merchant_id,
            'title'    => $data['title'],
            'type'     => (int) $data['type'],
            'discount' => round((float) $data['discount'], 2),
            'dish'     => $data['dish'] ? implode(',', $data['dish']) : '',
            'start'    => trim($data['start']),
            'end'      => trim($data['end']),
            'status'   => (int) $data['status'] ?: 0,
            'updated'  => REQUEST_TIME
        );
    }

    private function formatData(& $data) {
        if ($data['start']) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['start']);
            $data['start'] = $date->getTimestamp();
        }
        if ($data['end']) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['end']);
            $data['end'] = $date->getTimestamp();
        }
    }
    
    /**
     * 验证
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        $rules = array(
            'title' => array(
                'name' => '标题',
                'value' => $data['title'],
                'rules' => array(
                    'required' => array(),
                    'minLength' => array('value' => 2, 'fullWidth' => true),
                    'maxLength' => array('value' => 20, 'fullWidth' => true)
                )
            ),
            'dish' => array(
                'name' => '菜品',
                'value' => $data['dish'],
                'rules' => array(
                    'required' => array(),
                )
            ),
            'start' => array(
                'name' => '开始时间',
                'value' => $data['start'],
                'rules' => array(
                    'required' => array(),
                    'time' => array('format' => 'Y-m-d H:i:s')
                )
            ),
            'end' => array(
                'name' => '结束时间',
                'value' => $data['end'],
                'rules' => array(
                    'required' => array(),
                    'time' => array('format' => 'Y-m-d H:i:s')
                )
            ),
        );
        $validator->validate($rules);
        
        return $validator->getErrors();
    }
}