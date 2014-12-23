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
<!--	<a href="<?php echo site_url('/admini/dashboard/add_car/'.$data['id']);?>"><button class="btn btn-danger">旗下餐车添加</button></a>
	<a href="<?php echo site_url('/admini/dashboard/foodcarlist/'.$data['id']);?>"><button class="btn btn-danger">旗下餐车列表</button></a>-->

    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>商家信息</h5>
          </div>
          <div class="widget-content nopadding">
			<?php
			$attributes = array('name' => 'storeinfo','id' => 'storeinfo','class' => 'form-horizontal');
			$hidden = array("sid"=>$data['id'],"merchant_id"=>$merchant_id,"merchant_name"=>$merchant_name);
			echo form_open_multipart('/admini/doedit/storeinfo',$attributes,$hidden);?>
                <div class="control-group">
                    <label class="control-label">商户名称：</label>
                    <div class="controls">
                    <input type="text" disabled name="store_name" id="required" value="<?php echo $data['store_name'];?>" class="tip-top span4"  data-content="重新填写视为修改！" data-placement="top" data-toggle="popover"  data-original-title="友情提示：重新填写视为修改！">
                </div>
                <div class="control-group">
                    <label class="control-label">商户电话：</label>
                    <div class="controls">
                    <input type="text" disabled name="store_tel" id="required" value="<?php echo $data['store_tel'];?>" class="tip-top span4" data-content="重新填写视为修改！" data-placement="top" data-toggle="popover"  data-original-title="友情提示：重新填写视为修改！">
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">商户LOGO：</label>
                  <div class="controls">
					<img style="max-height: 200px;max-width: 200px;border: 1px solid #ddd" src="<?php echo MERCHANT_URL . $data['store_logo'];?>">
					<input type="hidden" disabled name="hid_storelogo" id="hid_storelogo" value="<?php echo $data['store_logo'];?>" >
                  </div>
                </div>
                
                 <div class="control-group">
                    <label class="control-label">商户地址</label>
                    <div class="controls">
                        <input type="text" class="span4" disabled name="store_address" maxlength="20" required id="local_dress" value="<?php echo $data['address'];?>">
                        <input type="button" class="btn" value="定位" id="local_button"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">地图定位</label>
                    <div class="controls">
                        <input type="text" disabled name="pointx" id="pointx" value="<?php echo $data['pointx'];?>" class="span2"/>
                        <input type="text" disabled name="pointy" id="pointy" value="<?php echo $data['pointy'];?>" class="span2"/>
                    </div>
                    <div class="controls">
                        <div id="container" style="width: 400px; height: 300px;"></div>
                    </div>
                </div>               
                                			
                <div class="control-group">
                  <label for="normal" class="control-label">营业开始时间:</label>
                  <div class="controls">
                      <?php $time = new DateTime($data['store_hours_start']); ?>
                    <input type="text" disabled id="mask-time-s" class="span3 mask text" name="store_hours_start" value="<?php echo $time->format('H:i');?>">
                  </div>
                </div>
                <div class="control-group">
                  <label for="normal" class="control-label">营业结束时间:</label>
                  <div class="controls">
                      <?php $time = new DateTime($data['store_hours_end']); ?>
                    <input type="text" disabled id="mask-time-e" class="span3 mask text" name="store_hours_end" value="<?php echo $time->format('H:i');?>">
                  </div>
                </div>
				<div class="control-group">
				  <label class="control-label">餐厅实体图：</label>
				  <div class="controls">
                      <?php if ($data['store_images']): ?>
                      <?php foreach ($data['store_images'] as $v): ?>
					<img src="<?php echo MERCHANT_URL . $v;?>" style="max-height: 200px;max-width: 200px;border: 1px solid #ddd">
                      <?php endforeach; endif ?>
					<input type="hidden" disabled name="hid_store_images" id="hid_store_images" value="" >
				  </div>
				</div>
                <div class="control-group">
                    <label class="control-label">简介：</label>
                    <div class="controls">
                    <textarea class="span6" disabled name="description" id="description"><?php echo $data['description'];?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">餐厅氛围：</label>
                    <div class="controls">
                    <textarea class="span6" disabled name="store_atmosphere" id="store_atmosphere"><?php echo $data['store_atmosphere'];?></textarea>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">商户特点：</label>
                  <div class="controls">
                    <select multiple name="store_feature" disabled id="store_feature">
						<?php echo $storelabel;?>
                    </select>
					<input type="hidden"  name="hid_feature" id="hid_feature" value="<?php echo $data['store_feature'];?>" />
					<input type="hidden" name="hid_feature_num" id="hid_feature_num" value="" >
                  </div>
                </div>
                <div class="control-group">
                	<label class="control-label">菜系：</label>
                    <div class="controls">
                        <?php echo $foodclass;?>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">是否支持送餐：</label>
                  <div class="controls">
                    <label>
                      <input type="radio" disabled name="delivery_yorn" value="0" onClick="changeable(0);" <?php if($data['delivery_yorn']=='0'):?> checked="checked" <?php endif?>/>
                      否</label>
                    <label>
                      <input type="radio" name="delivery_yorn" value="1" onClick="changeable(1);" <?php if($data['delivery_yorn']=='1'):?> checked="checked" <?php endif?>/>
                      是</label>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">送餐条件及送餐范围：</label>
                  <div class="controls">
                    <input type="text" disabled placeholder="选中送餐后，所填写的内容才被记录…"  class="span6" id="condition_and_range" name="condition_and_range" <?php if($data['delivery_yorn']=='0'):?> disabled="" <?php endif?> value="<?php echo $data['condition_and_range'];?>"><br/>
                  </div>
                </div>
                <div class="control-group">
                  <label for="normal" class="control-label">商家建议的人均:</label>
                  <div class="controls">
                    <input type="text" disabled id="per_capita" class="span3 mask text" name="per_capita" value="<?php echo $data['per_capita'];?>">
                  </div>
                </div>
				<div class="control-group">
				  <label for="normal" class="control-label">商家状态:</label>
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
