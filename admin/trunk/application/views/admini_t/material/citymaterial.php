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
    </div>
    <h1>城市原材料列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>城市原材料列表</h5> &nbsp;&nbsp;&nbsp;
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>城市</th>
                  <th>原材料数量</th>
                  <th>添加时间</th>
                  <th>更新时间</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data as $key => $list): ?>
                <tr class="gradeA">
                  <td><?php echo $list['name']; ?></td>
                  <td><?php echo $list['total'] ?></td>
                  <td><?php echo date('Y-m-d',$list['time_created']); ?></td>
                  <td>
                    <?php  
                        if($list['time_update']){
                           echo date('Y-m-d',$list['time_update']);
                         } else { 
                           echo date('Y-m-d',$list['time_created']);
                         } 
                    ?>
                  </td>
                  <td>
                    <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/citymaterial/materiallist/'.$list['id']);?>">原材料明细</a>
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

<!--<script src="<?php echo base_url()?>asset/js/jquery.ui.custom.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.uniform.js"></script>
<script src="<?php echo base_url()?>asset/js/select2.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>asset/js/matrix.js"></script>
<script src="<?php echo base_url()?>asset/js/matrix.tables.js"></script>-->
</body>
</html>
