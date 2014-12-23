<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#">订货管理</a> <a href="#" class="current">分装清单</a>
        </div>
        <h1>分装清单</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>清单列表</h5>
                    </div>
                    <div class="widget-content">
                        <div class="widget-box">
                            <form class="form-search widget-content" action="<?=site_url('admini/order/packing') ?>" style="overflow: hidden">
                                <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                                    <tr>
                                        <td><select style="width: 120px" name="kitchen">
                                                <option value="0">所有厨房</option>
                                                <?php foreach ($kitchens as $v): ?>
                                                    <option value="<?=$v->id ?>" <?php if (isset($_GET['kitchen']) && $_GET['kitchen'] == $v->id):?>selected<?php endif ?>><?=$v->name ?></option>
                                                <?php endforeach ?>
                                            </select></td>
                                        <td><select style="width: 120px" name="area">
                                                <option value="0">所有区域</option>
                                                <?php foreach ($areas as $v): ?>
                                                    <option value="<?=$v->id ?>" <?php if (isset($_GET['area']) && $_GET['area'] == $v->id):?>selected<?php endif ?>><?=$v->area ?></option>
                                                <?php endforeach ?>
                                            </select></td>
                                        <td width="100">
                                            <input type="text" name="paid_start" data-date="<?php echo isset( $_GET['paid_start']) ? $_GET['paid_start'] : date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['paid_start']) ? $_GET['paid_start'] : date('Y-m-d') ?>" class="datepicker span11">
                                        </td>
                                        <td><label class="control-label">至</label></td>
                                        <td width="100">
                                            <input type="text" name="paid_end" data-date="<?php echo isset($_GET['paid_end']) ? $_GET['paid_end'] : date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['paid_end']) ? $_GET['paid_end'] : date('Y-m-d') ?>" class="datepicker span11">
                                        </td>
                                        <td><input type="submit" class="btn btn-primary" value="查询"/></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>餐车</th>
                                <th>区域</th>
                                <th>厨房</th>
                                <th>小计(元)</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total = 0; ?>
                            <?php if (! empty($diners)): ?>
                            <?php foreach ($diners as $item): ?>
                                <?php foreach ($kitchens as $v1): ?>
                                <?php if (isset($_GET['kitchen']) && $_GET['kitchen'] && $_GET['kitchen'] != $v1->id): continue; endif ?>
                                <?php
                                    $sum = 0;
                                    foreach ($dinerMaterials[$item->id] as $k => $v):
                                        $material = $materials[$k];
                                        if ($material->kitchen_id != $v1->id): continue; endif;
                                        $sum += $material->price * $v;
                                    endforeach;
                                    $total += $sum;
                                ?>
                            <tr class="gradeA" style="background: #FFFBF1">
                                <td><?=$item->diner_name ?></td>
                                <td><?=isset($areas[$item->area]) ? $areas[$item->area]->area : '' ?></td>
                                <td><?=$v1->name ?></td>
                                <td><?=number_format($sum, 2) ?></td>
                                <td align="center" style="text-align: center">
                                    --
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>产品</td>
                                            <td>规格</td>
                                            <td>单价（元）</td>
                                            <td>数量</td>
                                            <td>单位</td>
                                            <td style="width: 30%">备注</td>
                                        </tr>
                                        <?php foreach ($dinerMaterials[$item->id] as $k => $v): ?>
                                        <?php $material = $materials[$k] ?>
                                        <?php if ($material->kitchen_id != $v1->id): continue; endif ?>
                                        <tr>
                                            <td><?=$material->name ?></td>
                                            <td><?=$material->spec ?></td>
                                            <td><?=$material->price ?></td>
                                            <td><?=$v ?></td>
                                            <td><?=$material->unit ?></td>
                                            <td><?=$material->remark ?></td>
                                        </tr>
                                        <?php endforeach ?>
                                        </thead>
                                    </table>
                                </td>
                            </tr>
                                <?php endforeach ?>
                            <?php endforeach ?>
                            <?php endif ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div>
                    总金额： <span class="price"><?=number_format($total, 2) ?> 元</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
