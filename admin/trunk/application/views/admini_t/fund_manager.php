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
        <a href="#" class="current">经营者资金管理</a>
    </div>
    <h1>经营者账户明细列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <form method="get" action="<?php echo site_url('/admini/fund/manager');?>" class="widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12">
                           <td><select style="width: 150px" name="type">
                              <option value="1" <?php if ((!isset($_GET['type']))||($_GET['type']==1)) echo 'selected';?>>经营者名称</option>
                              <option value="2" <?php if ($_GET['type']==2) echo 'selected';?>>餐车名称</option>
                              <option value="3" <?php if ($_GET['type']==3) echo 'selected';?>>所属商户名称</option>
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                            <td><input type="submit" class="btn btn-info" value="查询"/></td>
                        </table>
                    </form>
                </div>
    <div class="row-fluid">
      <div class="span12">
        
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>明细列表</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>经营者名称</th>
                  <th>餐车名称</th>
                  <th>所属商户名称</th>
                  <th>我的钱包</th>
                  <th>经营账户</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
              <?php if (is_array($data_list)) {
                  foreach ($data_list as $value) {
              ?>   
                  <tr>
                    <td><?=$value->realname ?></td>
                    <td><?=$value->diner_name ?></td>
                    <td><?=$value->merchant_name ?></td>
                    <td><?=$value->wallet ?></td>
                    <td><?=$value->account ?></td>
                    <td><a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/fund/manager_detail/'.$value->staff_id);?>">往来明细</a></td>
                  </tr>
              <?php }}?>
              </tbody>
            </table>            
          </div>
        </div>
        <div class="pager"><?=$pager ?></div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer;?>
</body>
</html>
