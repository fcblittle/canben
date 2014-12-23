<?php 

namespace Module\Common\Controller;

use Application\Controller\Front;

class Update extends Front {

    public function orderDetailMaterialRevisons() {
        $sql = "SELECT a.id,a.dish_id,a.dish_revision_id,b.material "
            . " FROM `foodcar_merchant_order_detail` a"
            . " LEFT JOIN `foodcar_official_dish_revision` b "
            . "     ON a.dish_revision_id = b.id"
            . " WHERE a.material = ''";
        $r = $this->db->fetchAll($sql);
        foreach ($r as & $v) {
            $v->material = json_decode($v->material);
            if (! $v->material) continue;
            $a = array();
            foreach ($v->material as $v1) {
                $a[$v1[0]] = $v1[0];
            }
            $binds = array(json_encode($a), $v->id);
            $sql = "UPDATE `foodcar_merchant_order_detail`"
                . " SET material = ?"
                . " WHERE id = ?";
            $result[] = $this->db->execute($sql, $binds);
        }
        print_r($result);
    }

    public function orderDetailMaterialRevisions_06051() {
        $sql = "SELECT a.id,a.dish_id,a.dish_revision_id,b.material "
            . " FROM `foodcar_merchant_order_detail` a"
            . " LEFT JOIN `foodcar_official_dish_revision` b "
            . "     ON a.dish_revision_id = b.id";
        $r = $this->db->fetchAll($sql);
        foreach ($r as & $v) {
            $v->material = json_decode($v->material);
            if (! $v->material) continue;
            $i = 0;
            foreach ($v->material as $k => & $v1) {
                if ($v1 == 0) {
                    $i++;
                    $v->material[$k] = $v1[0];
                }
            }
            if ($i == 0) continue;
            $binds = array(json_encode($v->material), $v->id);
            $sql = "UPDATE `foodcar_merchant_order_detail`"
                . " SET material = ?"
                . " WHERE id = ?";
            $result[] = $this->db->execute($sql, $binds);
        }
        print_r($result);
    }
}