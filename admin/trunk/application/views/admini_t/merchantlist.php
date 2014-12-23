<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '1' => '正常显示',
    '2' => '停牌',
    '3' => '未审核'
);
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">商户列表</a>
    </div>
    <h1>商户列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
	  	<a href="<?php echo site_url('/admini/dashboard/add_merchant/');?>" class="btn btn-danger">新增商户</a>
          <div class="widget-box">
              <form method="get" action="<?php echo site_url('/admini/dashboard/storelist');?>" class="widget-content" style="overflow: hidden">
                  <table width="100%" class="controls table-nav controls-row span12">
                      <td><select style="width: 120px" name="status">
                              <?php foreach ($statuses as $k => $v): ?>
                                  <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                              <?php endforeach ?>
                          </select></td>
                      <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>商户名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>手机号</option>
                              <option value="3" <?php if (isset($_GET['type']) && $_GET['type'] == 3): ?>selected<?php endif ?>>法人代表</option>
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                      <td><input type="submit" class="btn btn-primary" value="查询"/></td>
                  </table>
              </form>
          </div>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>商户列表</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered ">
              <thead>
                <tr>
                    <th>ID</th>
                  <th>商户登录名</th>
                  <th>法人代表</th>
                  <th>商户名称</th>
                  <!-- <th>商户类型</th> -->
                  <th>申请人姓名</th>
                  <th>商户状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $item): ?>
                <tr class="gradeA">
                  <td style="text-align: center"><?php echo $item->id; ?></td>
                  <td><?php echo $item->merchant_login; ?></td>
                  <td><?php echo $item->legal_represent; ?></td>
                  <td><?php echo $item->merchant_name; ?></td>
                 
                  <td class="center"><?php echo $item->apply_name; ?></td>
                  <td>
                  	<?=$statuses[$item->status] ?>
                  </td>
                  <td align="center">
                    <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/dashboard/edit_merchant/'.$item->id);?>">编辑</a>
                    <a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dodel/del_merchant/'.$item->id);?>">删除</a>
                    <?php if($item->status == '3'):?>
                    <a class="btn btn-warning btn-mini" href="<?php echo site_url('/admini/dodel/del_merchant/'.$item->id.'/1');?>">审核通过</a>
                    <?php endif;?>
                    <?php if($item->status == '2'):?>
                    <a class="btn btn-warning btn-mini" href="<?php echo site_url('/admini/dodel/del_merchant/'.$item->id.'/1');?>">正常显示</a>
                    <?php endif;?>
                    <?php if($item->status=='1'){ ?>
                    <a class="btn btn-warning btn-mini" href="<?php echo site_url('/admini/dodel/del_merchant/'.$item->id.'/2');?>">停牌处理</a>
                    <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/foodlist/'.$item->id);?>">商户菜品列表</a>
                    <a class="btn btn-success btn-mini" href="<?php echo site_url('/admini/dashboard/add_car/'.$item->id);?>">添加旗下餐车</a>
          					<a class="btn btn-warning btn-mini" href="<?php echo site_url('/admini/diner/dinerlist?merchantid='.$item->id);?>">旗下餐车</a>
                    <?php } ?>
                  </td>
                </tr>
               <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
          <div class="pager"><?=$pager ?></div>
      </div>
    </div>
  </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
