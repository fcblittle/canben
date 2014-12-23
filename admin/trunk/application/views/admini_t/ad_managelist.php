<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">广告列表</a>
    </div>
    <h1>广告列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
	  	<a href="<?php echo site_url('/admini/dashboard/add_advert/');?>" ><button class="btn btn-danger">新增广告</button></a>
		
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>数据集</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>广告名称</th>
                  <th>广告所属人</th>
                  <th>跳转地址</th>
                  <th>广告图</th>
				  <th>排序</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $list): ?>
                <tr class="gradeA">
                  <td><?php echo $list['ad_name']; ?></td>
                  <td><?php echo $list['ad_belongs']; ?></td>
                  <td><?php echo $list['tourl']; ?></td>
                  <td><img src="<?php echo $list['ad_pic']; ?>" border="0" width="100" height="50"/></td>
				  <td><?php echo $list['sort']; ?></td>
                  <td><?php echo $list['status']; ?></td>
                  <td align="center">
                    <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/dashboard/edit_advert/'.$list['id']);?>">编辑</a>
                    <a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dodel/advertisement/'.$list['id']);?>">删除</a>
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
