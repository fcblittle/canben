<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">餐车列表</a>
    </div>
    <h1>餐车列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>餐车列表</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>餐车名称</th>
                  <th>第一联系人</th>
                  <th>联系电话</th>
                  <th>第二联系人</th>
                  <th>联系电话</th>
                  <th>车牌号</th>
                  <th>注册时间</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $list):   $this->load->helper('date');?>
                <tr class="gradeA">
                  <td><?php echo $list['diner_name']; ?></td>
                  <td><?php echo $list['first_person']; ?></td>
                  <td class="center"><?php echo $list['first_person_tel']; ?></td>
                  <td><?php echo $list['second_person']; ?></td>
                  <td><?php echo $list['second_person_tel']; ?></td>
                  <td><?php echo $list['car_license_plate']; ?></td>
                  <td><?php echo unix_to_human($list['into_time']); ?></td>
                  <td align="center">
                    
                    <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/dashboard/positioning/'.$list['id']);?>">定位</a>
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
