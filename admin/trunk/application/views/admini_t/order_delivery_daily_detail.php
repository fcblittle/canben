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
    #footer { display: none;}
    #content { padding: 0px; margin: 0px;}
    .btn { display: none;}
    hr{ display: none;}
    td {padding: 3px !important;}
</style>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#">订货管理</a> <a href="#" class="current">厨房清单</a>
        </div>
        <h1>联合食通餐饮管理有限公司 - 加工订单</h1>
    </div>
    <div class="container-fluid">
        <h4>厨房：<?=$kitchen->name ?></h4>
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="#" onclick="javascript:window.print();" class="btn btn-primary">打印订单</a>
                <div class="widget-box">
                    <?php
                    $year = substr($item->date, 0, 4);
                    $month = substr($item->date, 4, 2);
                    $day = substr($item->date, 6, 2);
                    ?>
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>日期：<?=$year . '年' . $month . '月' . $day . '日' ?></h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td>产品</td>
                                <td>规格</td>
                                <td>单价（元）</td>
                                <td>数量</td>
                                <td>单位</td>
                                <td>厨房</td>
                                <td style="width: 20%">备注</td>
                            </tr>
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td style="text-align: center" colspan="7"><?=$cat->name ?></td>
                            </tr>
                            <?php foreach ($item->data as $k => $v): ?>
                            <?php if ($v['category_id'] == $cat->id): ?>
                            <tr>
                                <td><?=$v['name'] ?></td>
                                <td><?=$v['spec'] ?></td>
                                <td><?=$v['price'] ?></td>
                                <td><?=$v['quantity'] ?></td>
                                <td><?=$v['unit'] ?></td>
                                <td><?=$v['kitchen_name'] ?></td>
                                <td><?=$v['remark'] ?></td>
                            </tr>
                            <?php
                                    unset($item->data[$k]);
                                    endif;
                            ?>
                            <?php endforeach ?>
                            <?php endforeach ?>
                            <tr>
                                <td style="text-align: center" colspan="6">其它</td>
                            </tr>
                            <?php if ($item->data): foreach ($item->data as $k => $v): ?>
                            <tr>
                                <td><?=$v['name'] ?></td>
                                <td><?=$v['spec'] ?></td>
                                <td><?=$v['price'] ?></td>
                                <td><?=$v['quantity'] ?></td>
                                <td><?=$v['unit'] ?></td>
                                <td><?=$v['remark'] ?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                总金额： <span class="price"><?=number_format($item->total, 2)?></span> 元
            </div>
        </div>
    </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
