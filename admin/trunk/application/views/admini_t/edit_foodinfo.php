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
    <a href="<?php echo site_url('/admini/dashboard/foodinfo_list');?>">菜品列表</a> <a href="#" class="current">编辑菜品信息</a>
    </div>
    <h1>编辑菜品信息</h1>
  </div>
  <div class="container-fluid"><hr>
  <!--<button class="btn btn-danger">餐厅菜品添加</button>-->
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>菜品信息</h5>
          </div>
          <div class="widget-content nopadding">
			    <?php
                $attributes = array('name' => 'foodinfo','id' => 'foodinfo','class' => 'form-horizontal');
                $hidden = array("id"=>$label_info['id'],"hid_images"=>$label_info['images']);
                echo form_open_multipart('/admini/doedit/foodinfo_edit',$attributes,$hidden);?>			
                <div class="control-group">
                    <label class="control-label">菜品名称：</label>
                    <div class="controls">
                    <input type="text" name="food_name" id="food_name" value="<?php echo $label_info['food_name'];?>" class="span4" >
                </div>
                <div class="control-group">
                    <label class="control-label">单价：</label>
                    <div class="controls"> 
                    <input type="text" name="price" id="per_capita" value="<?php echo $label_info['price'];?>" class="span2" style="float:left">                
                    <select name="unit" class="span2" style="float:right">
                      <option value="份">份</option>
                      <option value="盘">盘</option>
                      <option value="串">串</option>
                    </select>               
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">菜品图片：</label>
                  <div class="controls">
                    <input type="file" name="images" id="images" value=""/> 
                    <img src="<?php echo $label_info['images'];?>" width="300" height="300">
                  </div>
                </div>  
                 <input type="hidden" name="hid_images" id="hid_images" value="<?php echo $label_info['images'];?>" />                              
                <div class="control-group">
                    <label class="control-label">简介：</label>
                    <div class="controls">
                    <textarea class="span6" name="description" id="description"><?php echo $label_info['description'];?></textarea>
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">菜品自分类：</label>
                  <div class="controls">
                    <select multiple name="cate_id" id="cate_id">
                        <?php echo $foodlabel;?>
                    </select>
                  </div>
                </div>
                 <div class="control-group">
                  <label class="control-label">属于商户：</label>
                  <div class="controls">
                    <select multiple name="store_id" id="store_id">
                        <?php echo $store;?>
                    </select>
                   
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">菜品状态</label>
                  <div class="controls">
                    <label>
                        <?php if($label_info['foodstatus']==1){
                            echo "<input type='radio' name='foodstatus' id='foodstatus' value='1' checked/>上架";
                        }else{
                            echo "<input type='radio' name='foodstatus' id='foodstatus' value='1' />上架";
                        }
                        ?>
                      </label>
                    <label>
                      <?php if($label_info['foodstatus']==0){
                            echo "<input type='radio' name='foodstatus' id='foodstatus' value='0' checked/>下架";
                        }else{
                            echo "<input type='radio' name='foodstatus' id='foodstatus' value='0' />下架";
                        }
                        ?>
                    </label>
                  </div>
                </div>
                <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/foodinfo_list/');?>" >返回</a>
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
