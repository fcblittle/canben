<?php echo $admini_head;?>
<!--close-Header-part-->
<link rel="stylesheet" href="<?php echo base_url(), 'asset/libs/uploadify/uploadify.css' ?>">
<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
    <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
    <a href="<?php echo site_url('/admini/diner/dinerlist');?>">餐车列表</a> <a href="#" class="current">添加餐车信息</a>
    </div>
    <h1>编辑餐车信息</h1>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>餐车信息</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
			$attributes = array('name' => 'carinfo','id' => 'carinfo','class' => 'form-horizontal ui-formwizard form-wizard' ,'enctype' => 'multipart/form-data');
			$hidden = array("merchant_id"=>$merchant_id,"merchant_name"=>$merchant_name,"dinerid"=>$dinerid);
			echo form_open_multipart('/admini/doedit/carinfo',$attributes,$hidden);?>
        <div id="form-wizard-1" class="step ui-formwizard-content" style="display: block;">
                <div class="control-group">
                    <label class="control-label">餐车名称：<span class="required">*</span></label>
                    <div class="controls">
                    <input type="text" name="diner_name" id="diner_name" value="<?php echo $data['diner_name'];?>" class="span4" >
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">餐车类型：</label>
                    <div class="controls">
                      <select class="span4 myselect" id="myselect" style="display: none;" name="role">
                        <option value="1" <?php if ($data['role'] == 1) echo 'selected';?>>自营</option>
                        <option value="2" <?php if ($data['role'] == 2) echo 'selected';?>>托管</option>
                      </select>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">餐车logo：</label>
                  <div class="controls">
                    <span id="image2"></span>
                    <div id="image-item2"></div>
                    <input type="hidden" name="logo" id="logo" value="" /> 
                  </div>
                </div>
                <div style="display: none" id="image-patten2">
                        <img width="100" height="100" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" src="" />
                  </div>
                
                <div class="control-group">
                  <label class="control-label">餐车图片：</label>
                  <div class="controls">
                    <span id="image"></span>
                    <div id="image-item"></div>
                    <input type="hidden" name="images" id="images" value="" /> 
                  </div>
                </div>
                
                <div style="display: none" id="image-patten">
                        <img width="150" height="150" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" src="" />
                  </div>
                  
                 <input type="hidden" name="hid_images" id="hid_images" value="<?php echo $data['images'];?>" />
                <div class="control-group">
                    <label class="control-label">餐车车牌：<span class="required">*</span></label>
                    <div class="controls">
                    <input type="text" name="car_license_plate" id="car_license_plate" value="<?php echo $data['car_license_plate'];?>" class="span4" >
                    </div>
                </div>
                    <div class="control-group">
                        <label class="control-label">经营区域：<span class="required">*</span></label>
                        <div class="controls">
                        <?php //var_dump($data);die;?>
                            <select class="span4" name="area">
                                <?php $selected = 0 ?>
                                <?php foreach ($areas as $v): ?>
                                <option value="<?=$v['id']?>" <?php if ($data['area'] == $v['id']): $selected = 1; ?>selected<?php endif ?>><?=$v['area'] ?></option>
                                <?php endforeach ?>
                                <?php if (! $selected): ?>
                                    <option value="" selected>　</option>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>				
                <div class="control-group">
                  <label class="control-label">出车时间：</label>
                  <div class="controls">
					<label>
					<input type="checkbox" name="trip_time1" id="trip_time1" value="1" <?php if($data['trip_time1_start']){?> checked="checked" <?php } ?> onClick="ckxvisable()"/>
					早餐
					</label>
					<div id="trip1">
                    <?php $time = new DateTime($data['trip_time1_start']); ?>
					开始：<input type="text" id="mask-time-s" class="span2 mask text" name="trip_time1_start" value="<?php echo $time->format('H:i');?>">&nbsp;~&nbsp;
                        <?php $time = new DateTime($data['trip_time1_end']); ?>
					结束：<input type="text" id="mask-time-e" class="span2 mask text" name="trip_time1_end" value="<?php echo $time->format('H:i');?>">
					</div>
					<hr/>
					
					<label>
					<input type="checkbox" name="trip_time2" id="trip_time2" value="2" <?php if($data['trip_time2_start']){?> checked="checked" <?php } ?> onClick="ckxvisable()"/>
					中餐</label>
					<div id="trip2">
					开始：<input type="text" id="mask-time-s2" class="span2 mask text" name="trip_time2_start" value="<?php echo $data['trip_time2_start'];?>">&nbsp;~&nbsp;
					结束：<input type="text" id="mask-time-e2" class="span2 mask text" name="trip_time2_end" value="<?php echo $data['trip_time2_end'];?>">
					 </div>
					 <hr/>
					 
                    <label>
					<input type="checkbox" name="trip_time3" id="trip_time3" value="3" <?php if($data['trip_time3_start']){?> checked="checked" <?php } ?> onClick="ckxvisable()"/>
                      晚餐</label>
					  <div id="trip3">
					  开始：<input type="text" id="mask-time-s3" class="span2 mask text" name="trip_time3_start" value="<?php echo $data['trip_time3_start'];?>">&nbsp;~&nbsp;
					  结束：<input type="text" id="mask-time-e3" class="span2 mask text" name="trip_time3_end" value="<?php echo $data['trip_time3_end'];?>">
					  </div>				  
                  </div>
                </div>

                <div class="control-group">
                    <label class="control-label">简介：</label>
                    <div class="controls">
                    <textarea class="span6" name="description" id="description"><?php echo $data['description'];?></textarea>
                    </div>
                </div>

                <div class="control-group">
                  <label class="control-label">是否支持送餐：</label>
                  <div class="controls">
                    <label>
                      <input type="radio" name="delivery_yorn" value="0" onClick="changeable(0);" <?php if($data['delivery_yorn']=='0'):?> checked="checked" <?php endif?>/>
                      否</label>
                    <label>
                      <input type="radio" name="delivery_yorn" value="1" onClick="changeable(1);" <?php if($data['delivery_yorn']=='1'):?> checked="checked" <?php endif?>/>
                      是</label>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">送餐条件及送餐范围：</label>
                  <div class="controls">
                    <input type="text" placeholder="选中送餐后，所填写的内容才被记录…" class="span6" name="condition_and_range" id="condition_and_range" <?php if($data['delivery_yorn']=='0'):?> disabled="" <?php endif?> value="<?php echo $data['condition_and_range'];?>">
                  </div>
                </div>
                <div class="control-group">
                  <label for="normal" class="control-label">商家建议的人均:</label>
                  <div class="controls">
                    <input type="text" class="span3 text" name="per_capita" value="<?php echo $data['per_capita'];?>">
                  </div>
                </div>
				<div class="control-group">
				  <label for="normal" class="control-label">餐车状态:</label>
				  <div class="controls">
					<label>
					  <input type="radio" name="store_stauts" id="store_stauts" value="0" <?php if($data['store_stauts']=='0'):?> checked="checked" <?php endif?>/>
					  未通过审核</label>
					<label>
					  <input type="radio" name="store_stauts" id="store_stauts" value="1" <?php if($data['store_stauts']=='1'):?> checked="checked" <?php endif?>/>
					  正常显示</label>
					<label>
					  <input type="radio" name="store_stauts" id="store_stauts" value="2" <?php if($data['store_stauts']=='2'):?> checked="checked" <?php endif?>/>
					  停牌处理</label>
				  </div>
				</div>
        </div>
        
        <div id="form-wizard-2" class="step ui-formwizard-content" style="display: none;">
            <div class="control-group">
                <label class="control-label">经营者名称 :</label>
                <div class="controls">
                    <input type="text" name="manager_name" class="span4" value="<?php echo empty($manager) ? '' : $manager['realname'];?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">经营者电话 :</label>
                <div class="controls">
                    <input type="text" name="manager_login" id="merchant_login" class="span4" value="<?php echo empty($manager) ? '' : $manager['username'];?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">经营者登录密码：</label>
                <div class="controls">
                <input type="password" name="manager_pwd" id="merchant_pwd" class="span4" placeholder="默认密码为123456"/>
                </div>
                
                <label class="control-label">确认登录密码：</label>
                <div class="controls">
                <input type="password" name="manager_ck_pwd" id="merchant_pwd" class="span4" placeholder="默认密码为123456"/>
                </div>
            </div>
        </div>
      
        <div class="form-actions form-actions1">
          <input id="back" class="btn btn-success" type="reset">
          <input id="next" class="btn btn-success" type="submit">
        </div>
        <div class="form-actions form-actions2" style="display:none;">
          <!-- <input id="back" class="btn btn-success" type="reset"> -->

          <input class="btn btn-success" type="button" value="提交" id="mybtn">

        </div>
  <!--          </form>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div style="display: none" id="image-item">
    <li class="img" style="float:left;position:relative;list-style: none">
        <img width="50px" height="50px" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" data-key="" src="" />
    </li>
</div>



<script>
function changeable(n){
    if(n==0){
        $("#condition_and_range").attr("disabled",true);
    }else{
        $("#condition_and_range").removeAttr("disabled");
    }
}
//
function ckxvisable(){
	$('#trip_time1').click(function(){
		if($("#trip_time1").attr("checked")=='checked'){
			$("#trip1").css("display","block");	
		}else{
			$("#trip1").css("display","none");
		}
	})
	//
	$('#trip_time2').click(function(){
		if($("#trip_time2").attr("checked")=='checked'){
			$("#trip2").css("display","block");	
		}else{
			$("#trip2").css("display","none");
		}
	})
	//
	$('#trip_time3').click(function(){
		if($("#trip_time3").attr("checked")=='checked'){
			$("#trip3").css("display","block");	
		}else{
			$("#trip3").css("display","none");
		}
	})		
}
</script>

<?php echo $footer;?>
<script src="<?php echo base_url(), 'asset/libs/uploadify/jquery.uploadify.min.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function() {

  var item;
  item = $('#image-patten').find("img").clone();

  imgSrc = "<?=$data['images']?>";
  item.attr('src', imgSrc);
  $('#image-item').append(item);
  $("#images").val(imgSrc);
  
  $('#image').uploadify({
        'multi'    : true,
        'swf'      : '<?php echo base_url(), "asset/libs/uploadify/uploadify.swf"; ?>',
        'uploader' : '<?php echo site_url("/admini/file/uploadFoodcar") ?>',
        'buttonText' : "请选择",
        'onUploadSuccess' : function(file, data, response) {
            var data = JSON.parse(data);
            if (data.code != 200) {alert(data.message);}
            item.attr({
                "src": data.content.src
            });
            $("#image-item").empty();
            $("#image-item").append(item);
            $("#images").val(data.content.key);
        }
    });
});
</script>
<script type="text/javascript">
$(function() {

  var item;
  item = $('#image-patten2').find("img").clone();

  imgSrc = "<?=$data['logo']?>";
  item.attr('src', imgSrc);
  $('#image-item2').append(item);
  $("#logo").val(imgSrc);
  
  
  $('#image2').uploadify({
        'multi'    : true,
        'swf'      : '<?php echo base_url(), "asset/libs/uploadify/uploadify.swf"; ?>',
        'uploader' : '<?php echo site_url("/admini/file/uploadFoodcar") ?>',
        'buttonText' : "请选择",
        'onUploadSuccess' : function(file, data, response) {
            var data = JSON.parse(data);
            if (data.code != 200) {alert(data.message);}
            item.attr({
                "src": data.content.src
            });
            $("#image-item2").empty();
            $("#image-item2").append(item);
            $("#logo").val(data.content.key);
        }
    });
});
</script>
<script type="text/javascript" src="<?php echo base_url()?>asset/js/matrix.wizard.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>asset/js/jquery.wizard.js"></script>

<script type="text/javascript">

$(document).ready(function(){
  
  if($("#myselect").val() == 2){
      $(".form-actions1").show();

      $(".form-actions2").hide();
      
    } else {
      $(".form-actions2").show();

      $(".form-actions1").hide();
    }

});

$(function(){
  $("#myselect").change(function(){ 
    if($(this).val() == 2){
      $(".form-actions1").show();

      $(".form-actions2").hide();
      
    } else {
      $(".form-actions2").show();

      $(".form-actions1").hide();
    }
  });
  $("#mybtn").click(function(){
    document.carinfo.submit();
  });
});




</script>

</body>
</html>
