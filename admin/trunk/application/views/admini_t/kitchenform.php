<?php echo $admini_head;?>
<link rel="stylesheet" href="<?php echo base_url(), 'asset/libs/uploadify/uploadify.css' ?>">
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        </div>
        <h1><?php echo $city;?>厨房</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label">厨房名称</label>
                                <div class="controls">
                                    <input type="text" name="name" maxlength="20" required id="" value="<?php echo isset($item)?$item->name : ''?>"> * 必填
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label">联系人</label>
                                <div class="controls">
                                    <input type="text" name="person" maxlength="15" required id="" value="<?php echo isset($item)?$item->person : ''?>"> * 必填
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">联系电话</label>
                                <div class="controls">
                                    <input type="text" name="phone" maxlength="11" required id="" value="<?php echo isset($item)?$item->phone : ''?>"> * 必填
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">联系地址</label>
                                <div class="controls">
                                    <input type="text" name="address" maxlength="50"  required id="" value="<?php echo isset($item)?$item->address : ''?>"> * 必填
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">厨房传真</label>
                                <div class="controls">
                                    <input type="text" name="fax"  maxlength="13" required id="" value="<?php echo isset($item)?$item->fax : ''?>"> * 必填
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <input type="hidden" name="city_id" value="<?php echo isset($city_id)? $city_id: $item->city_id;?>">
                                <input type="submit" value="提交" class="btn btn-success">
                                <a class="btn btn-info" href="/admini/kitchen/kitchenlist/<?php echo $city_id; ?>" >关闭</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display: none" id="image-item">
    <li class="img" style="float:left;position:relative;list-style: none">
        <img width="100" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" data-key="" src="" />
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
</body>
</html>
