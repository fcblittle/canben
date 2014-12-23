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
    <a href="<?php echo site_url('/admini/dashboard/storelabellist');?>">菜品标签列表</a> <a href="#" class="current">更新菜品信息</a>
    </div>
    <h1>菜品标签更改</h1>
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
        </div>
        <div class="widget-content nopadding">
        <?php
        $attributes = array('name' => 'foodlabel','id' => 'foodlabel','class' => 'form-horizontal');
        $hidden = array("fid"=>$label_info['id']);
        echo form_open('/admini/doedit/foodlabel',$attributes,$hidden);?>
            <div class="control-group">
              <label class="control-label">标签名称:</label>
              <div class="controls">
                <input type="text" class="span4" placeholder="标签名称" name="flabel_name" value="<?php echo $label_info['flabel_name'];?>"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">所属公司:</label>
              <div class="controls">
                <select class="span4" name="store_id">
					<?php echo $store_and_diner;?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">排序 :</label>
              <div class="controls">
                <input type="text" class="span4" placeholder="输入排序数字，越小的数字排行越靠前" name="sort" value="<?php echo $label_info['sort'];?>"/>
              </div>
            </div>            
            <div class="form-actions">
              <button type="submit" class="btn btn-success">保存</button>
              <a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/foodlabellist/');?>" >返回</a>
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
