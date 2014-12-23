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
    <a href="<?php echo site_url('/admini/dashboard/merchantlist');?>">商户列表</a> <a href="#" class="current">编辑商户信息</a>
    </div>
    <h1>编辑广告信息</h1>
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>广告信息</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
			$attributes = array('name' => 'advertisement','id' => 'advertisement','class' => 'form-horizontal');
			$hidden = array("aid"=>$data['id']);
			echo form_open_multipart('/admini/doedit/advertisement',$attributes,$hidden);?>			
                <div class="control-group">
                    <label class="control-label">广告名称 :</label>
                    <div class="controls">
                        <input type="text" name="ad_name" id="required" class="span4" value="<?php echo $data['ad_name'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">广告所属人：</label>
                    <div class="controls">
                    <input type="text" name="ad_belongs" id="required" value="<?php echo $data['ad_belongs'];?>" class="span4"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">跳转地址：</label>
                    <div class="controls">
                    <input type="text" name="tourl" id="required" value="<?php echo $data['tourl'];?>" class="span4" >
                </div>
                <div class="control-group">
                  <label class="control-label">广告图：</label>
                  <div class="controls">
                    <input type="file" name="ad_pic" id="required" value="<?php echo $data['ad_pic'];?>"/>
					<img src="<?php echo $data['ad_pic'];?>" width="300px" height="300px">
					<input type="hidden" name="hid_ad_pic" id="hid_ad_pic" value="<?php echo $data['ad_pic'];?>" >
                  </div>
                </div>
                <div class="control-group">
                    <label class="control-label">排序：</label>
                    <div class="controls">
                    <input type="text" name="sort" id="required" value="<?php echo $data['sort'];?>" class="span4" >
                    </div>
                </div>                
                <div class="control-group">
                  <label class="control-label">状态：</label>
                  <div class="controls">
                    <label>
                      <input type="radio" name="status" value="1" <?php if($data['status']=='1'):?> checked="checked" <?php endif?>/>待定
                    </label>
                    <label>
                      <input type="radio" name="status" value="2" <?php if($data['status']=='2'):?> checked="checked" <?php endif?>/>发布
                    </label>
                  </div>
                </div>	
                                
                <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/ad_management/');?>" >返回</a>
                </div>
  <!--          </form>-->
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
