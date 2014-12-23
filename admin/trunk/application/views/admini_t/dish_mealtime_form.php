<?php echo $admini_head;?>
<link rel="stylesheet" href="<?php echo base_url(), 'asset/libs/uploadify/uploadify.css' ?>">
<link rel="stylesheet" href="<?php echo base_url(), 'asset/css/bootstrap-timepicker.min.css' ?>"/>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        </div>
        <h1>用餐时段</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label">名称</label>
                                <div class="controls">
                                    <input type="text" name="name" value="<?=isset($item) ? $item->name : ''?>" class="span2">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">开始时间</label>
                                <div class="controls">
                                    <input type="text" name="start" class="timepicker span2" value="<?=isset($item) ? $item->start : ''?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">结束时间</label>
                                <div class="controls">
                                    <input type="text" name="end" class="timepicker span2" value="<?=isset($item) ? $item->end : ''?>">
                                </div>
                            </div>


                            <div class="form-actions">
                                <input type="submit" value="提交" class="btn btn-success">
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
