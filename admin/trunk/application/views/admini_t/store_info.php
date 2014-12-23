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
    <a href="<?php echo site_url('/admini/dashboard/storelist');?>">商户列表</a> <a href="#" class="current">编辑商户信息</a>
    </div>
    <h1>编辑商户信息</h1>
  </div>
  <div class="container-fluid">
  <!--<button class="btn btn-danger">餐厅菜品添加</button>-->
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5><font color="#0000FF"><?php echo $merchant_name; ?></font>&nbsp;&nbsp;商家信息</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
			$attributes = array('name' => 'storeinfo','id' => 'storeinfo','class' => 'form-horizontal');
			$hidden = array("merchant_id"=>$merchant_id,"merchant_name"=>$merchant_name);
			echo form_open_multipart('/admini/doadd/storeinfo',$attributes,$hidden);?>			
                <div class="control-group">
                    <label class="control-label">商户名称：</label>
                    <div class="controls">
                    <input type="text" name="store_name" id="required" value="" class="span4" >
                </div>
                <div class="control-group">
                    <label class="control-label">商户电话：</label>
                    <div class="controls">
                    <input type="text" name="store_tel" id="required" value="" class="span4" >
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">商户LOGO：</label>
                  <div class="controls">
                    <input type="file" name="store_logo" id="store_logo" value=""/> 
                  </div>
                </div>                
                <div class="control-group">
                    <label class="control-label">商户地址</label>
                    <div class="controls">
                        <input type="text" name="store_address" maxlength="20" required id="local_dress" value=""> 
                        <input type="button" class="btn" value="定位" id="local_button"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">地图定位</label>
                    <div class="controls">
                        <input type="text" name="pointx" id="pointx" value="" class="span3"/>
                        <input type="text" name="pointy" id="pointy" value="" class="span3"/>                
                    </div>
                    <div class="controls">
                        <div id="container" style="width: 400px; height: 300px;"></div>
                    </div>
                </div>					
                <div class="control-group">
                  <label for="normal" class="control-label">营业开始时间:</label>
                  <div class="controls">
                    <input type="text" id="mask-time-s" class="span3 mask text" name="store_hours_start" value="">
                  </div>
                </div>
                <div class="control-group">
                  <label for="normal" class="control-label">营业结束时间:</label>
                  <div class="controls">
                    <input type="text" id="mask-time-e" class="span3 mask text" name="store_hours_end" value="">
                  </div>
                </div>
				<div class="control-group">
				  <label class="control-label">餐厅实体图：</label>
				  <div class="controls">
					<input type="file" name="store_images" id="store_images" value=""/> 
				  </div>
				</div>
                <div class="control-group">
                    <label class="control-label">简介：</label>
                    <div class="controls">
                    <textarea class="span6" name="description" id="description"></textarea>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">商户特点：</label>
                  <div class="controls">
                    <select multiple name="store_feature" id="store_feature">
						<?php echo $storelabel;?>
                    </select>
					<input type="hidden" name="hid_feature" id="hid_feature" value="" />
					<input type="hidden" name="hid_feature_num" id="hid_feature_num" value="" >
                  </div>
                </div>
<!--                <div class="control-group">-->
<!--                	<label class="control-label">商户归类</label>-->
<!--                    <div class="controls">                       -->
<!--                        --><?php //echo $foodclass;?>
<!--                    </div>-->
<!--                </div>-->
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
                    <input type="text" id="per_capita" class="span3 mask text" name="per_capita" value="">
                  </div>
                </div>
				<!--<div class="control-group">
				  <label for="normal" class="control-label">商家状态:</label>
				  <div class="controls">
					<label>
					  <input type="radio" name="store_stauts" id="store_stauts" value="0"/>
					  未通过审核</label>
					<label>
					  <input type="radio" name="store_stauts" id="store_stauts" value="1"/>
					  正常显示</label>
					<label>
					  <input type="radio" name="store_stauts" id="store_stauts" value="2"/>
					  停牌处理</label>
				  </div>
				</div>-->
                <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/storelist/');?>" >返回</a>
                </div>
  <!--          </form>-->
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
</script>
<?php echo $footer;?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=DxjKQhv6VyFfSmhfqzGhFgEB"></script>
<script type="text/javascript">
    var map = new BMap.Map("container");          // 创建地图实例
    map.centerAndZoom("青岛",15);                // 初始化地图，设置中心点坐标和地图级别
    var local = new BMap.LocalSearch(map, {
        renderOptions:{map: map}
    });
    function showInfo(e){
        $("#pointx").val(e.point.lng);
        $("#pointy").val(e.point.lat);
    }
    map.addEventListener("click", showInfo);
	//$(function(){
        $("#local_button").click(function(){
            var dress = $("#local_dress").val();
            if(dress){
                local.search(dress);
                var cp = map.getCenter();
                $("[name='pointx']").val(cp.lng);
                $("[name='pointy']").val(cp.lat);
            }
        });
	//}
</script>
</body>
</html>
