<?php echo $admini_head;?>
<!--close-Header-part-->
<link rel="stylesheet" href="<?php echo base_url(), 'asset/libs/uploadify/uploadify.css' ?>">
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
    <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
    <a href="<?php echo site_url('/admini/dashboard/edit_merchant/'.$merchant_id);?>">商户信息</a> <a href="#" class="current">添加餐车信息</a>
    </div>
    <h1>编辑餐车信息</h1>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> 
            <span class="icon"><span class="badge badge-warning"><?php echo $merchant_name;?></span></span>&nbsp;
            <h5>旗下餐车信息</h5>
          </div>
          <div class="widget-content nopadding">
      <?php
      $attributes = array('name' => 'carinfo','id' => 'carinfo','class' => 'form-horizontal form-wizard'); //form-horizontal ui-formwizard form-wizard
      $hidden = array("merchant_id"=>$merchant_id,"merchant_name"=>$merchant_name);
      echo form_open_multipart('/admini/doadd/carinfo',$attributes,$hidden);?>
          <div id="form-wizard-1" class="step ui-formwizard-content" style="display: block;">
                <div class="control-group">
                    <label class="control-label">餐车名称：<span class="required">*</span></label>
                    <div class="controls">
                    <input type="text" name="diner_name" id="diner_name" value="" class="span4" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">餐车类型：</label>
                    <div class="controls">
                      <select class="span4 myselect" id="myselect" style="display: none;" name="role">
                        <option value="1">自营</option>
                        <option value="2" selected>托管</option>
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
                        <img width="250" height="250" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" src="" />
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
                        <img width="250" height="250" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" src="" />
                  </div>
                
                <div class="control-group">
                    <label class="control-label">餐车车牌：<span class="required">*</span></label>
                    <div class="controls">
                    <input type="text" name="car_license_plate" id="car_license_plate" value="" class="span4" >
                    </div>
                </div>
                    <div class="control-group">
                        <label class="control-label">经营区域：<span class="required">*</span></label>
                        <div class="controls">
                            <select class="span4" name="area">
                                <?php foreach ($areas as $v): ?>
                                    <option value="<?=$v['id']?>"><?=$v['area'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                <div class="control-group">
                  <label class="control-label">出车时间：</label>
                  <div class="controls">
          <label>
          <input type="checkbox" name="trip_time1" id="trip_time1" value="1" onClick="ckxvisable()"/>
          早餐
          </label>
          <div id="trip1" style="display:none;">
          开始：<input type="text" id="mask-time-s" class="span2 mask text" name="trip_time1_start" value="">&nbsp;~&nbsp;
          结束：<input type="text" id="mask-time-e" class="span2 mask text" name="trip_time1_end" value="">
          </div>
          <hr/>         
          <label>
          <input type="checkbox" name="trip_time2" id="trip_time2" value="2" onClick="ckxvisable()"/>
          中餐</label>
          <div id="trip2" style="display:none;">            
          开始：<input type="text" id="mask-time-s2" class="span2 mask text" name="trip_time2_start" value="">&nbsp;~&nbsp;
          结束：<input type="text" id="mask-time-e2" class="span2 mask text" name="trip_time2_end" value="">
           </div>
           <hr/>           
                    <label>
          <input type="checkbox" name="trip_time3" id="trip_time3" value="3" onClick="ckxvisable()"/>
                      晚餐</label>
            <div id="trip3" style="display:none">           
            开始：<input type="text" id="mask-time-s3" class="span2 mask text" name="trip_time3_start" value="">&nbsp;~&nbsp;
            结束：<input type="text" id="mask-time-e3" class="span2 mask text" name="trip_time3_end" value="">
            </div>          
                  </div>
                </div>
                <div class="control-group">
                    <label class="control-label">简介：</label>
                    <div class="controls">
                    <textarea class="span6" name="description" id="description"></textarea>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">是否支持送餐：</label>
                  <div class="controls">
                    <label>
                      <input type="radio" name="delivery_yorn" value="0" checked="checked" onClick="changeable(0);"/>
                      否</label>
                    <label>
                      <input type="radio" name="delivery_yorn" value="1" onClick="changeable(1);"/>
                      是</label>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">送餐条件及送餐范围：</label>
                  <div class="controls">
                    <input type="text" placeholder="选中送餐后，所填写的内容才被记录…" disabled="" class="span6" name="condition_and_range" id="condition_and_range" value="">
                  </div>
                </div>                
                <div class="control-group">
                  <label for="normal" class="control-label">商家建议的人均:</label>
                  <div class="controls">
                    <input type="text" class="span3 text" name="per_capita" value="">
                  </div>
                </div>
               
        </div>
      
        <div id="form-wizard-2" class="step ui-formwizard-content" style="display: none;">
            <div class="control-group">
                <label class="control-label">经营者名称 :</label>
                <div class="controls">
                    <input type="text" name="manager_name" class="span4" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">经营者电话 :</label>
                <div class="controls">
                    <input type="text" name="manager_login" id="merchant_login" class="span4" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">经营者登录密码：</label>
                <div class="controls">
                <input type="password" name="manager_pwd" id="merchant_pwd" value="" class="span4"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">确认登录密码：</label>
                <div class="controls">
                <input type="password" name="manager_ck_pwd" id="merchant_pwd" value="" class="span4"/>
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
            <!-- </form> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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

  imgSrc = "";
  
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
