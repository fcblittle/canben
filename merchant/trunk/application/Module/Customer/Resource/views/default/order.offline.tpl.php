<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

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
                        <div class="widget-title">
                            <ul class="nav nav-tabs">
                                <li><a href="/customer/order/orderOnline?diner_id=<?=$_GET['diner_id']?>&date=<?=$_GET['date'];?>">线上订单</a></li>
                                <li class="active"><a data-toggle="tab" href="#">线下订单</a></li>
                            </ul>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">
                                        <input type="hidden" name="diner_id" value="<?=$_GET['diner_id'];?>">
                                        <input type="text" data-date-format="yyyy-mm-dd" class="datepicker" id="datepicker" name="date" value="<?php echo $_GET['date'] ?: date('Y-m-d');?>" style="margin-left: 10px;">
                                         <input type="submit" class="btn btn-info" value="查询" style="margin-left: 10px;">
                                     </div>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
	                                <tr>
                                        <th>日期</th>
	                                	<th>菜品名称</th>
                                        <th>单价</th>
                                        <th>数量</th>
                                        <th>小计</th>
	                                </tr>
                                </thead>
                                <tbody>
                                <?php if($orderOffline):?>
                                    <?php foreach($orderOffline as $item):?>
                                    <tr>
                                        <td><?=date('Y-m-d', $item->created);?></td>
                                        <td><?=$dishes[$item->dish_reversion_id]->food_name;?></td>
                                        <td><?=$dishes[$item->dish_reversion_id]->sale_price;?></td>
                                        <td><?=$item->count;?></td>
                                        <td><?php echo '￥'.$item->count * $dishes[$item->dish_reversion_id]->sale_price;?></td>
                                    </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
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
    <script src="<?php echo $this->misc('js/bootstrap-datepicker.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/masked.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.uniform.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/select2.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
    <script type="text/javascript">
        $('#datepicker').datepicker('hide');
    </script>
<?php $this->region('Module\Common:foot') ?>