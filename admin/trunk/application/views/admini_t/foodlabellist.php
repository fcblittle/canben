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
        <a href="#" class="current">菜品标签 添加</a>
    </div>
    <h1>菜品标签列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
	  	<a href="<?php echo site_url('/admini/dashboard/edit_foodlable/');?>" ><button class="btn btn-danger">新增</button></a>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>数据表</h5> &nbsp;&nbsp;&nbsp;
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>编号</th>
                  <th>分类名称</th>
                  <th>所属商户</th>
                  <th>排序号</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $list): ?>
                <tr class="gradeA">
                  <td><?php echo $list['id']; ?></td>
                  <td><?php echo $list['flabel_name']; ?></td>
                  <td><?php echo $list['store_name']; ?></td>
                  <td><?php echo $list['sort']; ?></td>
                  <td>
                    <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/edit_foodlable/'.$list['id']);?>">编辑</a>
                    <a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dodel/foodlable/'.$list['id']);?>">删除</a>
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
