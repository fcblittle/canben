<?php

namespace Application\Component\Util;

class Tree {
    
    /**
     * 数据
     * @var array
     */
    private $data = array();
    
    /**
     * 选项
     * @var array
     */
    private $options = array(
        'primaryKey' => 'id',
        'parentKey'  => 'parentId',
        'childrenKey' => 'children'
    );
    
    public function __construct($data = array()) {
        $this->setData($data);
    }
    
    /**
     * 设置数据
     * 
     * @param array $data 数据
     */
    public function setData($data = array()) {
        if (! empty($data)) {
            $this->data = $data;
        }
        return $this;
    }
    
    /**
     * 设置选项
     * 
     * @param array $options 选项
     */
    public function setOptions($options = array()) {
        $this->options = merge_options($this->options, $options);
        return $this;
    }
    
    /**
     * 获取树形列表
     * 
     * @param mixed $parent 父节点
     * @return array 树形列表
     */
    public function get($parent = '') {
        $tree = array();
        foreach ($this->data as $k => $v) {
            foreach ($this->data as $k1 => $v1) {
                if ($v1->{$this->options['primaryKey']} == $v->{$this->options['parentKey']}) {
                    $v1->{$this->options['childrenKey']}[] = $v;
                }
            }
        }
        foreach ($this->data as $k => $v) {
            if ($v->{$this->options['parentKey']} == $parent) {
                $tree[] = $v;
            } else { 
                unset($this->data[$k]);
            }
            
        }
        return $tree;
    }
    
    /**
     * 获取扁平列表
     * 
     * @param mixed $parent 父节点
     * @return array
     */
    public function getPlain($parent = '') {
        $tree = $this->get($parent);
        $list = $this->getDescendant($tree);

        return $list;
    }
    
    /**
     * 递归获取后代
     * 
     * @param array $data 源数据
     * @return array
     */
    private function getDescendant($data) {
        $list = array();
        foreach ($data as $k => $v) {
            $list[$v->{$this->options['primaryKey']}] = $v;
            if ($v->children) {
                $list = array_merge($list, $this->getDescendant($v->children));
                unset($v->children);
            }
        }
        return $list;
    }
}
