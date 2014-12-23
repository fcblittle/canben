<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<style media="print">
    body{ background: #fff;}
    #header { display: none;}
    #sidebar { display: none;}
    #search { display: none;}
    #user-nav { display: none;}
    #footer,.filter{ display: none;}
    #content { padding: 0px; margin: 0px;}
    .btn { display: none;}
    hr{ display: none;}
    td {padding: 3px !important;}
</style>
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
                <div class="widget-box filter">
                    <form class="form-search widget-content" action="<?=site_url('admini/order/packing') ?>" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td><select style="width: 120px" name="area">
                                        <option value="0">请选择区域</option>
                                        <?php foreach ($areas as $v): ?>
                                            <option value="<?=$v->id ?>" <?php if (isset($_GET['area']) && $_GET['area'] == $v->id):?>selected<?php endif ?>><?=$v->area ?></option>
                                        <?php endforeach ?>
                                    </select></td>
                                <td><select style="width: 120px" name="kitchen">
                                        <option value="0">所有厨房</option>
                                        <?php foreach ($kitchens as $v): ?>
                                            <option value="<?=$v->id ?>" <?php if (isset($_GET['kitchen']) && $_GET['kitchen'] == $v->id):?>selected<?php endif ?>><?=$v->name ?></option>
                                        <?php endforeach ?>
                                    </select></td>
                                <td width="100">
                                    <input type="text" name="start" data-date="<?php echo isset( $_GET['start']) ? $_GET['start'] : date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['start']) ? $_GET['start'] : date('Y-m-d') ?>" class="datepicker span11">
                                </td>
                                <td><label class="control-label">至</label></td>
                                <td width="100">
                                    <input type="text" name="end" data-date="<?php echo isset($_GET['end']) ? $_GET['end'] : date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['end']) ? $_GET['end'] : date('Y-m-d') ?>" class="datepicker span11">
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-primary" value="查询"/>&nbsp;
                                    <a href="javascript:;" class="btn btn-info" id="print">打印</a>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box"  id="list">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>清单列表</h5>
                    </div>
                    <div class="widget-content">
                        <table class="table table-bordered" id="data-list">
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
                            <?php
                            foreach ($diners as $item):
                                foreach ($kitchens as $v1):
                                    if (isset($_GET['kitchen']) && $_GET['kitchen'] && $_GET['kitchen'] != $v1->id):
                                        continue;
                                    endif;
                                    $sum = 0;
                                    foreach ($dinerMaterials[$item->id] as $k => $v):
                                        $material = $materials[$k];
                                        if ($material->kitchen_id != $v1->id):
                                            continue;
                                        endif;
                                        $sum += $material->price * $v;
                                    endforeach;
                                    $total += $sum;
                                    if ($sum == 0): continue; endif;
                            ?>
                            <tr class="gradeA" style="background: #FFFBF1">
                                <td><span class="badge badge-info"><?=$item->diner_name ?></span></td>
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
                                            <th>产品</th>
                                            <th>规格</th>
                                            <th>单价（元）</th>
                                            <th>数量</th>
                                            <th>单位</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($dinerMaterials[$item->id] as $k => $v):
                                            $material = $materials[$k];
                                            if ($material->kitchen_id != $v1->id):
                                                continue;
                                            endif;
                                        ?>
                                        <tr>
                                            <td><strong><?=$material->name ?></strong></td>
                                            <td><?=$material->spec ?></td>
                                            <td><?=$material->price ?></td>
                                            <td><?=$v ?></td>
                                            <td><?=$material->unit ?></td>
                                        </tr>
                                        <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php
                                    endforeach;
                                endforeach;
                            endif;
                            ?>
                            </tbody>
                        </table>
                        <div>
                            总金额： <span class="price"><?=number_format($total, 2) ?> 元</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
<script>
    $(function () {
        $("#print").on("click", function() {
            window.print();
        });
    });
</script>
</body>
</html>
