<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '0' => '下架',
    '1' => '上架',
    
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">原料列表</a>
        </div>
        <h1>原料列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="<?php echo site_url('/admini/dish_material/add');?>" ><button class="btn btn-danger">新增</button></a>
                <div class="widget-box filter">
                    <form method="get" action="<?php echo site_url('/admini/dish_material/index');?>" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td><select style="width: 120px" name="status">
                                        <?php foreach ($statuses as $k => $v): ?>
                                        <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                    </select></td>
                               <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>原料名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>供应厨房</option>
                              
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                                
                                <td>
                                    <input type="submit" class="btn btn-primary" value="查询"/>
                                </td>
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
                                <th>名称</th>
                                <th>供应厨房</th>
                                <th>规格</th>
                                <th>单位</th>
                                <th>单价(元)</th>
                                <th>状态</th>
                                <th>添加时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($list): ?>
                            <?php foreach($list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo $v->name; ?></td>
                                    <td><?=isset($kitchens[$v->kitchen_id]) ? $kitchens[$v->kitchen_id]->name : '' ?></td>
                                    <td><?php echo $v->spec; ?></td>
                                    <td><?php echo $v->unit; ?></td>
                                    <td><?php echo $v->price; ?></td>
                                    <td><?=$statuses[$v->status] ?><!--<?php echo $v->status ? '上架' : '下架'; ?>--></td>
                                    <td><?=$v->time_created ? date('Y-m-d H:i:s', $v->time_created) : '' ?></td>
                                    <td><?=$v->time_updated ? date('Y-m-d H:i:s', $v->time_updated) : '' ?></td>
                                    <td>
                                        <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dish_material/edit/'.$v->id);?>">编辑</a>
                                        <a class="btn btn-danger btn-mini confirm" data-confirm="您确定要删除此原料吗？" href="<?php echo site_url('/admini/dish_material/delete/'.$v->id);?>">删除</a>
                                    </td>
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
