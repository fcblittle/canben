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
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>订单统计</h5>
                        </div>
                        <div class="widget-box">
                            <form method="get" class="form-inline widget-content">
                                <select name="diner_id" style="width: 150px;">
                                    <option>所有餐车</option>
                                    <?php if(! empty($diner)):?>
                                    <?php foreach($diner as $item):?>
                                        <option value="<?=$item->id?>" <?php if($item->id == $_GET['diner_id']) echo 'selected';?>><?=$item->diner_name;?></option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                                 <input type="text" data-date-format="yyyy-mm-dd" class="datepicker"  name="date[start]" value="<?php echo $dateInterval['start'] ?: '';?>" style="margin-left: 10px;" placeholder="开始时间">
                                 <input type="text" data-date-format="yyyy-mm-dd" class="datepicker"  name="date[end]" value="<?php echo $dateInterval['end'] ?: '';?>" style="margin-left: 10px;" placeholder="结束时间">
                                 <input type="submit" class="btn btn-info" value="查询" style="margin-left: 10px;">
                            </form>
                        </div>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <thead>
	                                <tr>
	                                	<th>日期</th>
	                                	<th>餐车名称</th>
	                                	<th>经营者</th>
	                                	<th>线上销售额</th>
	                                	<th>线下销售额</th>
	                                	<th>总计</th>
	                                	<th>操作</th>
	                                </tr>
                                </thead>
                                <tbody>
                                <?php if($orderDate):?>
                                    <?php foreach ($orderDate as $date):?>
                                        <?php if(! empty($orderDinerOnline[$date])):?>
                                        <?php foreach($orderDinerOnline[$date] as $id => $amount):?>
                                            <tr>
                                                <td><?=$date;?></td>
                                                <td><?=$diner[$id]->diner_name;?></td>
                                                <td><?=$manager[$id]->realname;?></td>
                                                <td><?=$amount ? '￥' . $amount : 0;?></td>
                                                <td><?=$orderDinerOffline[$date][$id]['amount'] ?'￥' . $orderDinerOffline[$date][$id]['amount']: 0;?></td>
                                                <td>￥<?php echo ($amount + $orderDinerOffline[$date][$id]['amount']);?></td>
                                                <td><a href="/customer/order/orderOnline?diner_id=<?=$id?>&date=<?=$date;?>" class="btn btn-info btn-mini">查看详情</a></td>
                                            </tr>
                                        <?php endforeach;?>
                                        <?php elseif(! empty($orderDinerOffline[$date])):?>
                                        <?php foreach($orderDinerOffline[$date] as $dinerId => $offlineItem):?>
                                            <tr>
                                                <td><?=$date;?></td>
                                                <td><?=$diner[$dinerId]->diner_name;?></td>
                                                <td><?=$manager[$dinerId]->realname;?></td>
                                                <td>0</td>
                                                <td><?=$offlineItem['amount'] ? '￥' . $offlineItem['amount'] : 0;?></td>
                                                <td><?=$offlineItem['amount'] ? '￥' . $offlineItem['amount'] : 0;?></td>
                                                <td><a href="/customer/order/orderOnline?diner_id=<?=$dinerId?>&date=<?=$date;?>" class="btn btn-info btn-mini">查看详情</a></td>
                                            </tr>
                                        <?php endforeach;?>
                                        <?php endif;?>
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
        $('.datepicker').datepicker({});
    </script>
<?php $this->region('Module\Common:foot') ?>