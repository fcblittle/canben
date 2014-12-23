<?php echo $admini_head;?>
<!--close-Header-part-->
<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
		<a href="<?php echo site_url('/admini/dashboard/storelist');?>" class="tip-bottom">商户列表</a>
        <a href="#" class="current">菜品列表</a>
    </div>
    <h1>菜品列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
	  	<a href="<?php echo site_url('/admini/dashboard/add_food/'.$storeid);?>" ><button class="btn btn-danger">新增菜品</button></a>
        <a href="<?php echo site_url('/admini/dashboard/merchantlist/');?>" ><button class="btn btn-info">返回</button></a>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
			<h5><font color="#0000FF"><?php echo $merchant_name; ?></font>&nbsp;&nbsp;菜品列表</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>菜品名称</th>
                  <th>菜品价格</th>
                  <th>菜品状态</th>
                  <th>菜品分类</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $list): ?>
                <tr class="gradeA">
                  <td><?php echo $list['food_name']; ?></td>
                  <td><?php echo $list['price']; ?>&nbsp;<?php echo $list['unit']; ?></td>
                  <td><?php if($list['foodstatus']=='1'){echo '上架';}else{echo '下架';} ?></td>
                  <td class="center"><?php echo $list['cate_id']; ?></td>
                  <td>
                    <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/edit_food/'.$list['id']);?>">编辑</a>
                    <a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dodel/foodlist/'.$list['id']);?>">删除</a>
                  </td>
                </tr>
               <?php endforeach; ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer;?>
</body>
</html>
