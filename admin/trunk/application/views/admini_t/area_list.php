<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">区域列表</a>
    </div>
    <h1>区域列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
	  	<a href="<?php echo site_url('/admini/dashboard/add_area');?>" ><button class="btn btn-danger">新增区域</button></a>
		
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>数据集</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered ">
              <thead>
                <tr>
                  <th>区域编号</th>
                  <th>区域名</th>
                  <th>属于城市</th>
                  <th>配送点地址</th>
                  <th>联系人</th>
                  <th>电话</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data as $key => $list):?>
                <tr class="gradeA">
                  <td><?php echo $list['id']; ?></td>
                  <td><?php echo $list['area']; ?></td>
                  <td><?php echo $list['name']; ?></td>
                  <td><?php echo $list['address']; ?></td>
                  <td><?php echo $list['contacts']; ?></td>
                  <td><?php echo $list['phone']; ?></td>
                  <td align="center">
                    <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/dashboard/edit_area/'.$list['id']);?>">编辑</a>
                    <a class="btn btn-danger btn-mini confirm" href="<?php echo site_url('/admini/dodel/del_area/'.$list['id']);?>">删除</a>
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
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
