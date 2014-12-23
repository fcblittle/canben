<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">城市列表</a>
    </div>
    <h1>城市列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
	  	<a href="<?php echo site_url('/admini/dashboard/add_city');?>" ><button class="btn btn-danger">新增城市</button></a>
		
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>数据集</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered ">
              <thead>
                <tr>
                  <th>城市编号</th>
                  <th>城市名</th>
                  <th>添加时间</th>
                  <th>权重</th>
                  <th>是否显示</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data as $key => $list):?>
                <tr class="gradeA">
                  <td><?php echo $list['id']; ?></td>
                  <td><?php echo $list['name']; ?></td>
                  <td><?php echo date('Y-m-d',$list['time_created']); ?></td>
                  <td><?php echo $list['wight']; ?></td>
                  <?php if ($list['isDelete'] == 0) {?>
                  <td>正常显示</td>
                  <?php } else {?>
                  <td>已删除</td>
                  <?php } ?>
                  <td align="center">
                    <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/dashboard/edit_city/'.$list['id']);?>">编辑</a>
                    <a class="btn btn-danger btn-mini confirm" href="<?php echo site_url('/admini/dodel/del_city/'.$list['id']);?>">删除</a>
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
