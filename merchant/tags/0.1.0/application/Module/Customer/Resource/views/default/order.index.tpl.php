<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
<?php
    $statuses = array(
        //'0' => '未支付',
        '1' => '完成',
        '2' => '未支付',
        '3' => '已支付',
        '4' => '已退款'
    );
?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="订单列表" class="tip-bottom"><i class=""></i>订单列表</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>订单列表</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form action="" method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">
                                        <select name="store_id" required id="required" class="span1" style="width: 150px">
                                            <option value="-1">所有餐车</option>
                                            <?php foreach ($cars as $v): ?>
                                            <option value="<?=$v->id ?>" <?php if ($_GET['store_id'] == $v->id):?>selected<?php endif ?>><?=$v->diner_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <select name="status" required id="required" class="span1" style="width: 120px">
                                            <option  value="-1" <?php if (! $_GET['status'] || $_GET['status'] == -1): ?>selected<?php endif ?>>所有状态</option>
                                            <?php foreach ($statuses as $k => $v): ?>
                                            <option  value="<?=$k ?>" <?php if ($_GET['status'] == $k): ?>selected<?php endif ?>><?=$v ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <select name="type" required id="required" class="span1" style="width: 120px">
                                            <option  value="orderno" <?php if (! $_GET['type'] || $_GET['type'] == 'orderno'): ?>selected<?php endif ?>>订单号</option>
                                            <option  value="account" <?php if ($_GET['type'] == 'account'): ?>selected<?php endif ?>>注册手机号</option>
                                            <option  value="contactname" <?php if ($_GET['type'] == 'contactname'): ?>selected<?php endif ?>>配送联系人</option>
                                            <option  value="phone" <?php if ($_GET['type'] == 'phone'): ?>selected<?php endif ?>>配送手机号</option>
                                        </select>
                                        <input type="text" name="key" value="<?=$_GET['key']?>" placeholder="请输入要查询的内容…" class="span3 m-wrap">
                                        <button style="margin-left: 1em" class="btn btn-primary" type="submit">查询</button>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>订单编号</th>
                                    <th>客户信息</th>
                                    <th>下单时间</th>
                                    <th>订单类别</th>
                                    <th>订单金额</th>
                                    <th>配送地址</th>
                                    <th>订单状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($list):
                                    foreach ($list as $item):
                                        ?>
                                        <tr>
                                            <td><?php echo($item->orderno)?></td>
                                            <td>
                                                <ul style="list-style: none">
                                                    <li>注册手机号：<?=$users[$item->user_id]->mobile_phone?></li>
                                                    <li>配送联系人：<?=$item->order_person?></li>
                                                    <li>配送手机号：<?=$item->order_person_tel?></li>
                                                </ul></td>
                                            <td style="text-align: center"><?php echo($item->insert_time)?></td>
                                            <td>
                                                <?php
                                                    $c=($item->type==0)?"配送":"自提";
                                                    echo($c);
                                                ?>
                                            </td>
                                            <td><?php echo($item->order_amount)?></td>
                                            <td><?php echo($item->delivery_address)?></td>
                                            <td style="text-align: center"><?=$statuses[$item->status] ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <ul class="dish-list">
                                            <?php
                                                if ($map):
                                                    foreach ($map[$item->id] as $aitem):
                                                        $dish = $dishes[$aitem->food_id];
                                                  ?>
                                                <li style="margin-right: 15px;padding:5px;">
                                                    <img width="80" height="80" alt="User" src="<?=$this->config['common']['officialBaseUrl'] ?>/<?php echo($dish->images[0]);?>"><br>
                                                    <div style="text-align: left">
                                                    <span class="label label-info"><?php echo($dish->food_name);?> </span><span class="label label-important" style="font-weight:bold">x<?php echo($aitem->num);?>份</span><br>
                                                单价：<?php echo($aitem->unit_price);?>元<br>
                                                总价：<?php echo($aitem->count);?>元
                                                    </div></li>
                                            <?php
                                                        endforeach;
                                                    endif;
                                            ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                endif;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php echo($pager)?>
                </div>
            </div>
        </div>
    </div>

    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-timepicker.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/masked.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.uniform.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/select2.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>、
<?php $this->region('Module\Common:foot') ?>