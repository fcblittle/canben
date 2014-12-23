<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--close-top-serch-->

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
    <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
    <a href="<?php echo site_url('/admini/dashboard/categorylist');?>">餐厅菜系列表</a> <a href="#" class="current">更新餐厅菜系信息</a>
    </div>
    <h1>更新餐厅菜系信息</h1>
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>

        </div>
        <div class="widget-content nopadding">
        <?php
        $attributes = array('name' => 'category','id' => 'category','class' => 'form-horizontal');
        $hidden = array("cid"=>$class_info['id']);
        echo form_open('/admini/doedit/category',$attributes,$hidden);?>
            <div class="control-group">
              <label class="control-label">餐厅菜系名称:</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="菜系名称" name="cuisine" value="<?php echo $class_info['cuisine'];?>"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">排序 :</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="输入排序数字，越小的数字排行越靠前" name="sort" value="<?php echo $class_info['sort'];?>"/>
              </div>
            </div>
            
            <div class="form-actions">
              <button type="submit" class="btn btn-success">保存</button>
              <a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/categorylist/');?>" >返回</a>
            </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php echo $footer;?>
</body>
</html>
