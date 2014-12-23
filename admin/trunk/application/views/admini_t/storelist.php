<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php 
   $statuses = array(
                      '-1' => '全部状态',
                      '0' => '未通过审核',
                      '1' => '正常显示',
                      '2' => '停牌',
                      '3' => '审核中'
                    ) 
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">餐厅列表</a>
    </div>
    <h1>餐厅列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
      	<?php if($merchantid){ ?>
	  	<a href="<?php echo site_url('/admini/dashboard/add_store/'.$merchantid);?>" ><button class="btn btn-danger">新增餐厅</button></a>
        <?php } ?>
        <div class="widget-box">
                    <form method="get" action="<?php echo site_url('/admini/dashboard/merchantlist');?>" class="widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12">
                            <td><select style="width: 120px" name="status">
                                    <?php foreach ($statuses as $k => $v): ?>
                                        <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                </select></td>
                           <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>餐厅名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>餐厅电话</option>
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                            <td><input type="submit" class="btn btn-primary" value="查询"/></td>
                        </table>
                    </form>
                </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>数据集</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered ">
              <thead>
                <tr>
                  <th>餐厅名称</th>
                  <th>餐厅电话</th>
                  <th>餐厅地址</th>
				          <th>餐厅状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
              
               <?php foreach($data_list as $key => $list): ?>
                <tr class="gradeA">
                  <td><?php echo $list->store_name; ?></td>
                  <td><?php echo $list->store_tel; ?></td>
                  <td><?php echo $list->address; ?></td>
                  
				  <td><?=$statuses[$list->store_stauts]?> </td>
                  <td>
                    <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/edit_store/'.$list->merchant_id);?>">编辑</a>
                    <!--<a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dodel/storelist/'.$list->id);?>">删除</a>-->
					<!--<a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dashboard/add_foodlist/store/'.$list->merchant_id.'/'.$list->id);?>">添加菜品</a>
                    <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/foodlist/store/'.$list->id);?>">菜品列表</a>-->
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
