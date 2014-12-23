<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">App包管理</a>
    </div>
    <h1>App包列表</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <a href="<?php echo site_url('/admini/app/add');?>" ><button class="btn btn-info">发布新版本</button></a>
        
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>App包列表</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered ">
              <thead>
                <tr>
                  <th class="span1">版本编号</th>
                  <th class="span1">App</th>
                  <th class="span1">版本名</th>
                  <th class="span1">下载地址</th>
                  <th class="span2">版本描述</th>
                  <th class="span1">强制更新</th>
                  <th class="span1">包大小</th>
                  <th class="span2">发布时间</th>
                  <th class="span2">操作</th>
                </tr>
              </thead>
              <tbody>
               <?php if(! empty($data)):?>
                <?php foreach($data as $item):?>
                  <tr>
                    <td><?=$item->id;?></td>
                    <td><?=$item->app;?>(<?=$item->devCode;?>)</td>
                    <td><?=$item->versionName;?></td>
                    <td><?=$item->versionUrl;?></td>
                    <td><?=$item->versionContent;?></td>
                    <td><?=$item->versionForcibly;?></td>
                    <td><?=$item->versionSize;?></td>
                    <td><?=$item->pubtime;?></td>
                    <td>
                    <?php if(empty($latest_version[$item->devCode][$item->appCode]) || 
                              $latest_version[$item->devCode][$item->appCode]->id == $item->id): ?>
                      <a class="btn btn-primary btn-mini" href="<?php echo site_url('/admini/app/edit?id=' .  $item->id);?>">编辑</a>
                    <?php endif;?>
                  </tr>
                <?php endforeach;?>
               <?php endif;?>
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
