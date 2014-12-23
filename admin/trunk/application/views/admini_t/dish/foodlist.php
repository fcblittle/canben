<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">菜品管理</a><a href="#" class="current">菜品列表</a>
        </div>
        <h1><?php echo $city;?>菜品列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="<?php echo site_url('/admini/cityfood/add?city_id='.$city_id);?>" ><button class="btn btn-danger">新增</button></a>
                <?php $statuses = array(
                    '-1' => '全部状态',
                    '0' => '下架',
                    '1' => '上架',
                ) ?>                 
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5><?php echo $city;?>菜品列表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>供货价格</th>
                                <th>销售价格</th>
                                <th>菜品状态</th>
                                <th>添加时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($list){ ?>
                            <?php foreach($list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo $v['food_name']; ?></td>
                                    <td><?php echo $v['supply_price']; ?></td>
                                    <td><?php echo $v['sale_price']; ?></td>
                                    <td><?php echo $v['foodstatus'] ? '上架' : '<span style="color:#bbb">下架</span>'; ?></td>
                                    <td><?=$v['time_created'] ? date('Y-m-d H:i:s', $v['time_created']) : '' ?></td>
                                    <td><?=$v['time_updated'] ? date('Y-m-d H:i:s', $v['time_updated']) : '' ?></td>
                                    <td>
                                        <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/cityfood/edit?city_id='.$city_id.'&&id='.$v['id']);?>">编辑</a>
                                        <a class="btn btn-danger btn-mini confirm" data-confirm="您确定要删除这条菜品吗？" href="<?php echo site_url('/admini/cityfood/delete?city_id='.$city_id.'&&id='.$v['id']);?>">删除</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php } else {?>
                            <tr><td>该城市下还没有添加菜品，快添加菜品吧</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                <!--<div class="pager"><?=$pager ?></div>-->
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
