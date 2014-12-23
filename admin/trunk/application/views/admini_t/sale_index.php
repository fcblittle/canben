<?php echo $admini_head;?>
<!--close-Header-part-->
<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">销售数据查看</a>
    </div>
    <h1>销售数据列表</h1>
  </div>

  <!--Action boxes-->
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        
                        <div class="widget-box">
                            <form method="get" class="form-inline widget-content">
                                <input type="text" data-date-format="yyyy-mm-dd" class="datepicker"  name="start" style="margin-left: 10px;" value="<?php echo $_GET['start'] ?:'开始时间';?>">
                                <input type="text" data-date-format="yyyy-mm-dd" class="datepicker"  name="end"  style="margin-left: 10px;" value="<?php echo $_GET['end'] ?:'结束时间';?>">

                                <select style="width: 150px" name="type">
                                    <option value="1" <?php if ((!isset($_GET['type']))||($_GET['type']==1)) echo 'selected';?>>经营者名称</option>
                                    <option value="2" <?php if ($_GET['type']==2) echo 'selected';?>>餐车名称</option>
                                    <option value="3" <?php if ($_GET['type']==3) echo 'selected';?>>所属商户名称</option>
                                </select>
                                <input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…">

                                <input type="submit" class="btn btn-info" value="查询" style="margin-left: 10px;">
                            </form>
                        </div>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <thead>
	                                <tr>
	                                	<th>日期</th>
	                                	<th>店长</th>
	                                	<th>餐车名称</th>
	                                	<th>所属商户</th>
	                                	<th>线上销售</th>
	                                	<th>线下销售</th>
	                                	<th>总计</th>
	                                	<th>操作</th>
	                                </tr>
                                </thead>
                                <tbody>
                                    <?php if (is_array($data_list)) {
                                      foreach ($data_list as $value) {
                                    ?>   
                                      <tr>
                                        <td><?=$value['date'] ?></td>
                                        <td><?=$value['manager_name'] ?></td>
                                        <td><?=$value['diner_name'] ?></td>
                                        <td><?=$value['merchant_name'] ?></td>
                                        <td><?=$value['online'] ?></td>
                                        <td><?=$value['offline'] ?></td>
                                        <td><?=$value['online']+$value['offline'] ?></td>
                                        <td><a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/sale/sale_detail/?diner_id='.$value['diner_id'].'&&date='.$value['date']);?>">查看明细</a></td>
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