<?php echo $admini_head;?>
<!--close-Header-part-->
<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '0' => '未通过审核',
    '1' => '正常显示',
    '2' => '停牌'
) ?>
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
              <?php if($merchantid){ ?>
              <a href="<?php echo site_url('/admini/dashboard/add_car/'.$merchantid);?>" ><button class="btn btn-danger">新增餐车</button></a>
              <?php } ?>
                <div class="widget-box">
                    <form method="get" action="<?php echo site_url('/admini/diner/dinerlist');?>" class="widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12">
                            <td><select style="width: 120px" name="status">
                                    <?php foreach ($statuses as $k => $v): ?>
                                        <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                </select></td>
                           <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>餐车名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>手机号</option>
                              <option value="3" <?php if (isset($_GET['type']) && $_GET['type'] == 3): ?>selected<?php endif ?>>车牌号</option>
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                            <td><input type="submit" class="btn btn-primary" value="查询"/></td>
                        </table>
                    </form>
                </div>
    <div class="row-fluid">
      <div class="span12">
      	
		<!--<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/edit_store/'.$storeid);?>" >返回</a>-->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>数据集</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered">
              <thead>
                <tr>
				  <th>餐车编号</th>
                  <th>餐车名称</th>
                  <th>餐车类型</th>
				  <th>餐车所属公司</th>
                  <th>餐车车牌</th>
				  <th>餐车出车时间</th>
				  <th>餐车状态</th>
				  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $list): ?>
                <tr class="gradeA">
				          <td><?php echo $list->id;?></td>
                  <td><?php echo $list->diner_name; ?></td>
                  <td><?php 
                  if ($list->role == 1) {
                    echo '自营';
                  } else {
                    echo '托管';
                  }
                  ?></td>
				          <td><?php echo $list->merchant_name; ?></td>
                  <td><?php echo $list->car_license_plate; ?></td>
        				  <td>
        					  <?php echo $list->trip_time1_start;?>~<?php echo $list->trip_time1_end;?><br/>
        					  <?php echo $list->trip_time2_start;?>~<?php echo $list->trip_time2_end;?><br/>
        					  <?php echo $list->trip_time3_start;?>~<?php echo $list->trip_time3_end;?>
        				  </td>
                  <td>
                    <?=$statuses[$list->store_stauts] ?>
                  </td>
        				  <td>
        				  	<a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/edit_car/'.$list->id);?>">编辑</a>
        					  <a class="btn btn-danger btn-mini" href="<?php echo site_url('/admini/dodel/del_car/'.$list->id);?>">停牌</a>&nbsp;&nbsp;
                           <!-- <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/add_foodlist/diner/'.$list->merchant_id.'/'.$list->id);?>">添加菜品</a>
                            <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/dashboard/foodlist/diner/'.$list->id);?>">菜品列表</a>-->
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
<?php echo $footer;?>
</body>
</html>
