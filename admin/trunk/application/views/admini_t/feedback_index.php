<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">用户反馈列表</a>
        </div>
        <h1>用户反馈列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">                   
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>数据表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>用户昵称</th>
                                <th>反馈内容</th>
                                <th>联系方式</th>
                                <th>反馈时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($data_list): ?>
                            <?php foreach($data_list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo isset($users[$v->user_id])? "<a href='".site_url('/admini/dashboard/userinfo_edit/'.$v->user_id)."'>" .$users[$v->user_id]->nickname."</a>" : '[匿名用户]'; ?></td>
                                    <td><?php echo mb_substr($v->content, 0 ,30); 
                                    if (mb_strlen($v->content) > 30) {
                                          echo '...';
                                        }?></td>
                                    <td><?php echo $v->contact; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $v->insert_time); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pager"><?=$pager ?></div>
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
