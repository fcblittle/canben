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
    <a href="<?php echo site_url('/admini/dashboard/foodinfo_list');?>">城市列表</a> <a href="#" class="current">编辑城市信息</a>
    </div>
    <h1>编辑城市信息</h1>
  </div>
  <div class="container-fluid"><hr>
  <!--<button class="btn btn-danger">餐厅菜品添加</button>-->
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>城市信息</h5>
          </div>
           <form class="form-horizontal" enctype="multipart/form-data" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
          <div class="widget-content nopadding">		
                <div class="control-group">
                    <label class="control-label">城市名称：</label>
                    <div class="controls">
                    <input type="text" name="name" maxlength="10" required  id="name" value="<?php echo isset($item)?$item->name : ''?>" class="span4" >*必填
                </div>
                
                <div class="control-group">
                    <label class="control-label">权重：</label>
                    <div class="controls">
                    <input type="text" name="wight" maxlength="3" required  id="wight" value="<?php echo isset($item)?$item->wight : ''?>" class="span4" >*数值越小越靠前
                </div>
               
               
                <div class="form-actions">
                <button type="submit" class="btn btn-success">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?php echo site_url('/admini/dashboard/get_city/');?>" >返回</a>
                </div>
                </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php echo $footer;?>
<script type="text/javascript">
$(function() {
    // 提交表单
    $("#basic_validate").on("submit", function() {
        var imgs = [];
        $(".list-img img").each(function() {
            imgs.push($(this).attr("data-key"));
        });
        $("[name='images']").val(imgs.join(","));
    });
    for(var i=0;i<2;i++){
        $("#original").find("tbody").html($("#materials-tpl").html());
        $("select").select2();
    }
    $("body").delegate("#original a", "click", function(){
        var  p = $(this).parents("tr");
        if($(this).find("i").hasClass("icon-plus")){
            p.after($("#materials-tpl-add").html());
            $("select").select2();
        }
        if($(this).find("i").hasClass("icon-minus")){
            var n =  $("#original").find("tbody tr").length;
            if(n>1){
                p.remove();
            }else{
                return false;
            }

        }
        return false;
    });
});
</script>
</body>
</html>
