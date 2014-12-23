<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">厨房列表</a>
        </div>
        <h1><?php echo $city?>厨房列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="<?php echo site_url('/admini/kitchen/add/'.$city_id);?>" ><button class="btn btn-danger">新增</button></a>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>数据表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>名称</th>
                                <th>联系人</th>
                                <th>联系电话</th>
                                <th>联系地址</th>
                                <th>传真</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($list): ?>
                            <?php foreach($list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo $v->id; ?></td>
                                    <td><?php echo $v->name; ?></td>
                                    <td><?php echo $v->person; ?></td>
                                    <td><?php echo $v->phone; ?></td>
                                    <td><?php echo $v->address; ?></td>
                                    <td><?php echo $v->fax; ?></td>
                                  
                                    <td>
                                        <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/kitchen/edit/'.$v->id.'/'.$city_id);?>">编辑</a>
                                        <a class="btn btn-danger confirm btn-mini" href="<?php echo site_url('/admini/kitchen/delete/'.$v->id.'/'.$city_id);?>">删除</a>
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
