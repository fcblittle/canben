<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '0' => '禁用',
    '1' => '启用',
) ?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">用户列表</a>
    </div>
    <h1>用户列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="<?php echo site_url('/admini/dashboard/userinfo_add');?>" ><button class="btn btn-danger">新增用户</button></a>
                <div class="widget-box">
                    <form method="get"  action="<?php echo site_url('admini/dashboard/userinfo_list')?>" class="widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12">
                          <td><select style="width: 120px" name="status">
                                    <?php foreach ($statuses as $k => $v): ?>
                                        <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                </select></td>
                           <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>用户名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>用户昵称</option>
                              <option value="3" <?php if (isset($_GET['type']) && $_GET['type'] == 3): ?>selected<?php endif ?>>手机号</option>
                              <option value="4" <?php if (isset($_GET['type']) && $_GET['type'] == 4): ?>selected<?php endif ?>>Email</option>
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
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>用户手机</th>
                  <th>用户Email</th>
                  <th>用户姓名</th>
                  <th>用户昵称</th>
                  <th>用户生日</th>
                  <th>用户余额</th>
                  <th>注册时间</th>
                  <th>用户状态</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
               <?php foreach($data_list as $key => $list):?>
                <tr class="gradeA">
                  <td class="center"><?php echo $list->mobile_phone; ?></td>
                  <td><?php echo $list->email; ?></td>
                  <td><?php echo $list->real_name; ?></td>
                  <td><?php echo $list->nickname; ?></td>
                  <td><?php echo date("Y-m-d",$list->birthday); ?></td>
                  <td><?php echo $list->account; ?></td>
                  <td><?php echo date("Y-m-d h:s",$list->insert_time); ?></td>
                  <td><?php 
                  if($list->status==0)
                  {
                    echo "禁用";
                  }
                  else
                  {
                    echo "启用";
                  }
                  ?>
                  </td>
                  <td align="center">
                    <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/dashboard/userinfo_edit/'.$list->id);?>">编辑</a>
                    <?php 
                    if($list->status==0)
                    {
                         echo '<a class="btn btn-danger btn-mini confirm" 
                                  data-confirm="您确定要重新启用该客户吗？" 
                                  href="/index.php/admini/dodel/del_userinfo?status=1&&id='.$list->id.'" >
                                  启用
                                </a> ';
                    }
                    else
                    {
                       echo '<a class="btn btn-primary btn-mini confirm" 
                                  data-confirm="您确定要禁用该客户吗？" 
                                  href="/index.php/admini/dodel/del_userinfo?status=0&&id='.$list->id.'" >
                                  禁用
                                </a> ';
                    }
                    ?>
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
