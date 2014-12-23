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
            <a href="#">订货管理</a> <a href="#" class="current">加工订单</a>
        </div>
        <h1>加工订单</h1>
    </div>
    <div class="container-fluid">
        
        <hr>
        <div class="row-fluid">
            <div class="widget-box">
                <form method="get" class="form-inline widget-content">
                    <input type="text" data-date-format="yyyy-mm-dd" class="datepicker"  name="start" value="<?php echo $dateInterval['start'] ?: '';?>" style="margin-left: 10px;" placeholder="<?php echo $_GET['start'] ?:'选择日期';?>">

                    <input type="submit" class="btn btn-info" value="查询" style="margin-left: 10px;">
                </form>
            </div>
                <div class="widget-box">
                    
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>日清单列表</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>产品</th>
                                <th>规格</th>
                                <th>单价（元）</th>
                                <th>数量</th>
                                <th>单位</th>
                                <th>金额</th>
                            </tr>
                            
                            <?php if ($list): foreach ($list as $k => $v): ?>
                            <tr>
                                <td><?=$v->name ?></td>
                                <td><?=$v->spec ?></td>
                                <td><?=$v->price ?></td>
                                <td><?=$v->quantity ?></td>
                                <td><?=$v->unit ?></td>
                                <td><?=number_format($v->amount,2)?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                            </thead>
                        </table>
                    </div>
                </div>
            <!-- </div> -->
            <div>
                总金额： <span class="price"><?=number_format($sum, 2)?></span> 元
                <div class="pager"><?=$pager ?></div>
            </div>
        </div>
    </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
