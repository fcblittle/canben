<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#">订货管理</a> <a href="#" class="current">商户清单</a>
        </div>
        <h1>商户清单</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <form method="get" class="widget-content" style="overflow: hidden">
                    <table width="100%" class="controls table-nav controls-row span12">
                        <td><label class="control-label ">商户ID</label></td>
                        <td width="100" ><input type="text" name="merchant_id" value="<?=isset($_GET['merchant_id']) ? $_GET['merchant_id'] : '' ?>"></td>
                        <td width="100">
                            <input type="text" name="start" data-date-format="yyyy-mm-dd" value="<?=isset($_GET['start']) ? $_GET['start'] : date('Y-m-d') ?>" class="datepicker span11">
                        </td>
                        <td><label class="control-label">至</label></td>
                        <td width="100">
                            <input type="text" name="end" data-date-format="yyyy-mm-dd" value="<?=isset($_GET['start']) ? $_GET['end'] : date('Y-m-d') ?>" class="datepicker span11">
                        </td>
                        <td><input type="submit" class="btn btn-primary" value="查询"/> <a href="javascript:;" class="btn btn-info" id="print">打印</a></td>
                    </table>
                    </form>
                </div>
                <div class="widget-box" id="list">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5><?=isset($merchant) ? $merchant->merchant_name : '商户分装清单' ?></h5>
                    </div>
                    <div class="widget-content">
                        <table class="table table-bordered" id="list">
                            <thead>
                            <tr>
                                <th>餐车</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total = 0; ?>
                            <?php if (! empty($diners)): ?>
                                <?php
                                foreach ($diners as $item):
                                    if (empty($dinerMaterials[$item->id])) continue;
                                    ?>
                                    <tr class="gradeA" style="background: #FFFBF1">
                                        <td><span class="badge badge-info"><?=$item->diner_name ?></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="nopadding">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>产品</th>
                                                    <th>规格</th>
                                                    <th>数量</th>
                                                    <th>单位</th>
                                                    <th>单价(元)</th>
                                                </tr>
                                                <?php
                                                foreach ($dinerMaterials[$item->id] as $k => $v):
                                                    $material = $materials[$k];
                                                    $total += $material->price * $v;
                                                ?>
                                                    <tr>
                                                        <td><strong><?=$material->name ?></strong></td>
                                                        <td><?=$material->spec ?></td>
                                                        <td><?=$v ?></td>
                                                        <td><?=$material->unit ?></td>
                                                        <td><?=$material->price ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                                </thead>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                            </tbody>
                        </table>
                        总金额： <span class="price"><?=number_format($total, 2) ?> 元</span>
                    </div>
                </div>
                <div>
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
        $("#list").printArea();
    });
});
</script>
</body>
</html>
