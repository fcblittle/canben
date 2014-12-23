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
    <a href="<?php echo site_url('/admini/dashboard/userinfo_list');?>">用户列表</a> <a href="#" class="current">添加用户信息</a>
    </div>
    <h1>添加用户信息</h1>
  </div>
  <div class="container-fluid"><hr>
  <!--<button class="btn btn-danger">餐厅菜品添加</button>-->
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>用户信息</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
			$attributes = array('name' => 'merchantinfo','id' => 'merchantinfo','class' => 'form-horizontal');
			echo form_open_multipart('/admini/doadd/userinfo',$attributes);?>			
                <div class="control-group">
                    <label class="control-label">用户姓名 :</label>
                    <div class="controls">
                        <input type="text" name="real_name" id="real_name" class="span4" value="">请输入数字、字母、下划线
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">用户昵称 :</label>
                    <div class="controls">
                        <input type="text" name="nickname" id="nickname" class="span4" value="">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">登录密码：</label>
                    <div class="controls">
                    <input type="password" name="password" id="password" value="" class="span4"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">您的性别：</label>
                    <div class="controls">
                    <input type="text" name="sex" id="sex" value="" class="span4" >
                </div>
                <div class="control-group">
                    <label class="control-label">您的手机：</label>
                    <div class="controls">
                    <input type="text" name="mobile_phone" id="mobile_phone" value="" class="span4" >
                    </div>
                </div>
                <!--<div class="control-group">
                    <label class="control-label">您的电话：</label>
                    <div class="controls">
                    <input type="text" name="phone" id="phone" value="" class="span4" >
                    </div>
                </div>  -->              
                <div class="control-group">
                    <label class="control-label">您的Email：</label>
                    <div class="controls">
                    <input type="text" name="email" id="email" value="" class="span4" >
                    </div>
                </div>  
                <div class="control-group">
                  <label class="control-label">用户头像：</label>
                  <div class="controls">
                    <input type="file" name="head_portrait" id="head_portrait" value=""/> 
                  </div>
                </div> 
               
                <div class="control-group">
                  <label class="control-label">您的生日：</label>
                  <div class="controls">
                    <input type="text"  name="birthday"  id="birthday"  data-date="2013-02-01" 
                    data-date-format="yyyy-mm-dd" value="2013-02-01" class="datepicker span4">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">口味偏好：</label>
                    <div class="controls">
                    <input type="text" name="taste" id="taste" value="" class="span4" >
                    </div>
                </div> 
                <div class="control-group">
                    <label class="control-label">送餐地址：</label>
                    <div class="controls">
                    <input type="text" name="address" id="address" value="" class="span4" >
                    </div>
                </div>  
                <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/userinfo_list/');?>" >返回</a>
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
