<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '0' => '申请中',
    '1' => '已驳回',
    '2' => '已同意',
    '3' => '已支付',
    '4' => '已取消'
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">用户退款列表</a>
        </div>
        <h1>用户退款列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <form method="get" action="<?php echo site_url('/admini/refund/index');?>" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td><select style="width: 120px" name="status">
                                        <option value="-1">全部状态</option>
                                        <?php foreach ($statuses as $k => $v): ?>
                                            <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                        <?php endforeach ?>
                                    </select></td>
                               <!-- <td width="100">
                                    <input type="text" name="date_start" data-date-format="yyyy-mm-dd" value="<?php echo isset( $_GET['date_start']) ? $_GET['date_start'] : date('Y-m-d') ?>" class="datepicker span11">
                                </td>
                                <td><label class="control-label">至</label></td>
                                <td width="100">
                                    <input type="text" name="date_end" data-date-format="yyyy-mm-dd" value="<?php echo isset( $_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d') ?>" class="datepicker span11">
                                </td>-->
                                <td><input type="submit" value="查询" class="btn btn-primary"/></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>数据表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>用户手机号</th>
                                <th>用户昵称</th>
                                <th>订单号</th>
                                <th>理由</th>
                                <th>申请时间</th>
                                <th>受理时间</th>
                                <th>支付时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($list): ?>
                            <?php foreach($list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo isset($orders[$v->order_no]) && isset($users[$orders[$v->order_no]->user_id]) ? $users[$orders[$v->order_no]->user_id]->mobile_phone : '[用户已删除]'; ?></td>
                                    <td><?php echo isset($orders[$v->order_no]) && isset($users[$orders[$v->order_no]->user_id]) ? $users[$orders[$v->order_no]->user_id]->nickname : '[用户已删除]'; ?></td>
                                    <td><?php echo $v->order_no; ?></td>
                                    <td><?php echo $v->message; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $v->time_created); ?></td>
                                    <td><?php echo $v->time_processed ? date('Y-m-d H:i:s', $v->time_processed) : ''; ?></td>
                                    <td><?php echo $v->time_paid ? date('Y-m-d H:i:s', $v->time_paid) : ''; ?></td>
                                    <td>
                                     <?=$statuses[$v->status] ?>
                                    </td>
                                    <td>
                                        <?php if ($v->status == 2): ?>
                                        <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/refund/approval/'.$v->order_no);?>">支付</a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function changeable(n){
    if(n==0){
        $("#condition_and_range").attr("disabled",true);
    }else{
        $("#condition_and_range").removeAttr("disabled");
    }
}
//
function ckxvisable(){
	$('#trip_time1').click(function(){
		if($("#trip_time1").attr("checked")=='checked'){
			$("#trip1").css("display","block");	
		}else{
			$("#trip1").css("display","none");
		}
	})
	//
	$('#trip_time2').click(function(){
		if($("#trip_time2").attr("checked")=='checked'){
			$("#trip2").css("display","block");	
		}else{
			$("#trip2").css("display","none");
		}
	})
	//
	$('#trip_time3').click(function(){
		if($("#trip_time3").attr("checked")=='checked'){
			$("#trip3").css("display","block");	
		}else{
			$("#trip3").css("display","none");
		}
	})		
}
</script>
<?php echo $footer;?>
</body>
</html>
