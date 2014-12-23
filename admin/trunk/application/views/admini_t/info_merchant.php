<?php echo $admini_head;?>
<!--close-Header-part-->

<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<link rel="stylesheet" href="<?php echo base_url(), 'asset/libs/uploadify/uploadify.css' ?>">
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
    <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
    <a href="<?php echo site_url('/admini/dashboard/merchantlist');?>">商户列表</a> <a href="#" class="current">编辑商户信息</a>
    </div>
    <h1>编辑商户信息</h1>
  </div>
  <div class="container-fluid"><hr>
  <!--<button class="btn btn-danger">餐厅菜品添加</button>-->
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>商家信息</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
			$attributes = array('name' => 'merchantinfo','id' => 'merchantinfo','class' => 'form-horizontal');
			$hidden = array("mid"=>$data['id']);
			echo form_open_multipart('/admini/doedit/merchantinfo',$attributes,$hidden);?>			
                <div class="control-group">
                    <label class="control-label">商户登录名 :</label>
                    <div class="controls">
                        <input type="text" name="merchant_login" id="merchant_login" class="span4" value="<?php echo $data['merchant_login'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">商户登录密码：</label>
                    <div class="controls">
                    <input type="password" name="merchant_pwd" id="merchant_pwd" value="<?php echo $data['merchant_pwd'];?>" class="span4"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">确认登录密码：</label>
                    <div class="controls">
                    <input type="password" name="merchant_ck_pwd" id="merchant_pwd" value="<?php echo $data['merchant_pwd'];?>" class="span4"/>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">法人代表：</label>
                    <div class="controls">
                    <input type="text" name="legal_represent" id="required" value="<?php echo $data['legal_represent'];?>" class="span4" >
                </div>
                <div class="control-group">
                    <label class="control-label">商户名称：</label>
                    <div class="controls">
                    <input type="text" name="merchant_name" id="required" value="<?php echo $data['merchant_name'];?>" class="span4" >
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">申请人姓名：</label>
                    <div class="controls">
                    <input type="text" name="apply_name" id="required" value="<?php echo $data['apply_name'];?>" class="span4" >
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">营业执照复印件：</label>
                  <div class="controls">
                    <span id="image"></span>
                    <div id="image-item"></div>
                    <input type="hidden" name="business_license" id="business_license" />
                  </div>
                </div>
                <div class="control-group">
                  <label for="normal" class="control-label">商家状态:</label>
                  <div class="controls">
                    <label>
                      <input type="radio" name="status" id="status" value="1" <?php if($data['status']=='1'):?> checked="checked" <?php endif?>/>
                      正常显示</label>
                    <label>
                      <input type="radio" name="status" id="status" value="2" <?php if($data['status']=='2'):?> checked="checked" <?php endif?>/>
                      停牌处理</label>
                    <label>
                      <input type="radio" name="status" id="status" value="3" <?php if($data['status']=='3'):?> checked="checked" <?php endif?>/>
                      未通过审核</label>
                  </div>
                </div>
                <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/merchantlist/');?>" >返回</a>
                </div>
  <!--          </form>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div style="display: none" id="image-patten">
    <img width="250" height="250" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" src="" />
</div>

<?php echo $footer;?>

<script src="<?php echo base_url(), 'asset/libs/uploadify/jquery.uploadify.min.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  var item;
  item = $('#image-patten').find("img").clone();

  imgSrc = "<?=$data['business_license']?>";
  item.attr('src', imgSrc);
  $('#image-item').append(item);
  $("#business_license").val(imgSrc);

  $('#image').uploadify({
        'multi'    : true,
        'swf'      : '<?php echo base_url(), "asset/libs/uploadify/uploadify.swf"; ?>',
        'uploader' : '<?php echo site_url("/admini/file/uploadMerchant") ?>',
        'buttonText' : "请选择",
        'onUploadSuccess' : function(file, data, response) {
            var data = JSON.parse(data);
            if (data.code != 200) {alert(data.message);}
            item.attr({
                "src": data.content.src
            });
            $("#image-item").empty();
            $("#image-item").append(item);
            $("#business_license").val(data.content.key);
        }
    });
});
</script>
</body>
</html>
