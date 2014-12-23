<?php echo $admini_head;?>
<!--close-Header-part--> 

<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch--> 

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a> 
    <?php if($tag=='store'){ ?> <a href="<?php echo site_url('/admini/dashboard/storelist/'.$merchant_id);?>">餐厅列表</a> <?php }else{ ?> <a href="#">餐厅列表</a> <?php } ?><a href="#" class="current">菜品添加</a> </div>    
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
		<?php
        $attributes = array('name' => 'foodinfo','id' => 'foodinfo','class' => 'form-horizontal');
        $hidden = array("merchant_id"=>$merchant_id,"tag"=>$tag,"id"=>$id);
        echo form_open_multipart('/admini/doadd/foodrelation',$attributes,$hidden);?>   
      	<button type="submit" class="btn btn-success">提交</button>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-picture"></i> </span>
            <h5>菜品添加</h5>
          </div>
          <div class="widget-content">
       
            <ul class="thumbnails">
              <?php foreach($data_list as $key => $list): ?>
              <li class="span2"> <a> <img src="<?php echo $list['images'];?>" alt="" > </a>
                <div >
                <span class="label label-info"><?php echo $list['food_name'];?>&nbsp;&nbsp;<?php echo $list['price'];?>/<?php echo $list['unit'];?></span>
                <input type="checkbox" name="food[]" value="<?php echo $list['id'];?>" <?php echo $list['checked'];?> style="height:18px; width:18px;"/></div>
              </li>
              <?php endforeach; ?>
            </ul>            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Footer-part-->
<?php echo $footer;?>
</body>
</html>
