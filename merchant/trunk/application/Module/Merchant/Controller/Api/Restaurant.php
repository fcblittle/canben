<?php 

namespace Module\Merchant\Controller\Api;

use Application\Controller\AppApi;

/**
 * 餐厅相关API
 */
class Restaurant extends AppApi {
    
    public function init() {
        $this->model = $this->model(':Restaurant');
    }
    
    /**
     * 获取餐厅数据
     */
    public function getItem($args = array()) {
        $data = $this->model->getItem($args);
        $this->formatItem($data);

        return $this->export($data);
    }

    /**
     * 添加餐厅
     *
     * 返回代码
     *   200: ok
     *   500: sql错误
     *   001: 没有这个餐厅
     *   002: 资料审核中
     *
     * @param array $args
     * @return mixed
     */
    public function add($args = array()) {
        $formData = $args['data'] ?: $_POST;
        if (! $formData) {
            return $this->export(array('code' => 400));
        }
        $data = $this->prepareFormData($formData);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        $data['merchant_id'] = $this->user->id;
        $data['into_time'] = REQUEST_TIME;
        $id = $this->model->add($data);
        if ($id !== false) {
            return $this->export(array('content' => $id));
        } else {
            $error = $this->db->sth->errorInfo();
            return $this->export(array(
                'code' => 500,
                'message' => 'MYSQL错误：' . $error[2]
            ));
        }
    }
    
    /**
     * 更新餐厅数据
     *
     * 返回代码
     *   200: ok
     *   500: sql错误
     *   001: 没有这个餐厅
     *   002: 资料审核中
     */
    public function update($args = array()) {
        $id = $this->user->id;
        $formData = $args['data'] ?: $_POST;
        if (! $formData) {
            return $this->export(array('code' => 400));
        }
        $item = $this->model->getItem(array(
            'merchant_id' => $this->user->id,
            'fields' => $args['fields'] ?: 'store_stauts'
        ));
        if (! $item) {
            return $this->export(array(
                'code' => 1,
                'message' => '没有这个餐厅'
            ));
        }
        if ($item->store_stauts == 3) {
            return $this->export(array(
                'code' => 2,
                'message' => '资料审核中，请耐心等待…'
            ));
        }
        $data = $this->prepareFormData($formData);
        $errors = $this->validate($data);
        if ($errors) {
            return $this->export(array('code' => 400, 'errors' => $errors));
        }
        if ($this->model->update($id, $data) !== false) {
            // TODO:删除旧图片
            return $this->export();
        } else {
            $error = $this->db->sth->errorInfo();
            return $this->export(array(
                'code' => 500,
                'message' => 'MYSQL错误：' . $error[2]
            ));
        }
    }

    /**
     * 准备数据
     * @param $data
     * @return array
     */
    private function prepareFormData($formData) {
        $data = array(
            // 餐厅名
            'store_name'          => $formData['store_name'],
            // 电话
            'store_tel'           => $formData['store_tel'],
            // 地址
            'address'             => $formData['address'],
            // 经度
            'pointx'              => $formData['pointx'],
            // 纬度
            'pointy'              => $formData['pointy'],
            // 营业开始
            'store_hours_start'   => $formData['store_hours_start'],
            // 营业结束
            'store_hours_end'     => $formData['store_hours_end'],
            // 商家特色
            'store_feature'       => $formData['store_feature'],
            // 餐厅菜系
            'foodclass'           => $formData['foodclass'],
            // 描述
            'description'         => $formData['description'],
            // 餐厅氛围
            'store_atmosphere'    => $formData['store_atmosphere'],
            // 是否送餐
            'delivery_yorn'       => (int) $formData['delivery_yorn'],
            // 配送范围和条件
            'condition_and_range' => $formData['condition_and_range'],
            // 人均消费
            'per_capita'          => (int) $formData['per_capita'],
            // 审核中
            'store_stauts'        => 0
        );
        // logo
        if ($formData['store_logo']) {
            $data['store_logo'] = $formData['store_logo'];
        }
        if ($formData['store_feature']) {
            $data['store_feature'] = implode(',', $formData['store_feature']);
        }
        if ($formData['foodclass']) {
            $data['foodclass'] = implode(',', $formData['foodclass']);
        }
        // images
        if ($formData['store_images']) {
            $data['store_images'] = $formData['store_images'];
        }
        return $data;
    }

    /**
     * 验证
     * @param $data
     * @return mixed
     */
    private function validate($data) {
        $validator = $this->com('System:Validator\Validator');
        $validator->setOptions(array('breakOnError' => false));
        // 验证规则
        $rules = array(
            'store_name' => array(
                'name' => '餐厅名称',
                'value' => $data['store_name'],
                'rules' => array(
                    'required' => array(),
                    'minLength' => array('value' => 2, 'fullWidth' => true),
                    'maxLength' => array('value' => 10, 'fullWidth' => true)
                )
            ),
            'store_tel' => array(
                'name' => '餐厅电话',
                'value' => $data['store_tel'],
                'rules' => array(
                    'required' => array(),
                    'phone'      => array()
                )
            ),
            'store_logo' => array(
                'name' => '餐厅logo',
                'value' => $data['store_tel'],
                'rules' => array(
                    'required' => array()
                )
            ),
            'address' => array(
                'name' => '餐厅地址',
                'value' => $data['address'],
                'rules' => array(
                    'required' => array(),
                    'minLength' => array('value' => 2, 'fullWidth' => true),
                    'maxLength' => array('value' => 100, 'fullWidth' => true)
                )
            ),
            'pointx' => array(
                'name' => '经度',
                'value' => $data['pointx'],
                'rules' => array(
                    'required' => array(),
                    'number'   => array(),
                    'maxLength' => array('value' => 360, 'fullWidth' => true)
                )
            ),
            'store_hours_start' => array(
                'name' => '餐厅开始营业时间',
                'value' => $data['store_hours_start'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
            'store_hours_end' => array(
                'name' => '餐厅结束营业时间',
                'value' => $data['store_hours_end'],
                'rules' => array(
                    'required' => array(),
                    'time'     => array('format' => 'G:i')
                )
            ),
        );
        $validator->validate($rules);
        return $validator->getErrors();
    }
    
    /**
     * 审核
     */
    public function sensor() {
        $id = (int) $args['id'];
        if (! $id) {
            return $this->export(array(
                'code'    => 400, 
                'message' => 'Missing ID'
            ));
        }
        $result = $this->model->update($id, array('sensor_status' => 1));
        
        return $this->export($result);
    }

    /**
     * 格式化item
     *
     * @param $item
     * @return mixed
     */
    private function formatItem(& $item) {
        if ($item->store_feature) {
            $item->store_feature = explode(',', $item->store_feature);
        }
        if ($item->foodclass) {
            $item->foodclass = explode(',', $item->foodclass);
        }
    }

}