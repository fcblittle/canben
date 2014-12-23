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
        <h1><?php echo $city;?>菜品原料</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label">分类</label>
                                <div class="controls">
                                    <select name="category_id" required id="required" class="span2">
                                        <?php foreach ($categories as $v): ?>
                                            <option  value="<?=$v->id ?>" <?php echo (isset($item) && $item->category_id === $v->id) ? 'selected':'' ?>><?=$v->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">供应厨房</label>
                                <div class="controls">
                                    <select name="kitchen_id" required id="required" class="span2">
                                        <?php foreach ($kitchens as $v): ?>
                                            <option  value="<?=$v->id ?>" <?php echo (isset($item) && $item->kitchen_id === $v->id) ? 'selected':'' ?>><?=$v->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">名称</label>
                                <div class="controls">
                                    <input type="text" class="span2" name="name" maxlength="10" required id="" value="<?php echo isset($item)?$item->name : ''?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">规格</label>
                                <div class="controls">
                                    <input type="text" class="span2" name="spec" maxlength="20" required id="" value="<?php echo isset($item)?$item->spec : ''?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">单位</label>
                                <div class="controls">
                                    <select name="unit" required id="required" class="span2">
                                        <?php foreach ($units as $v): ?>
                                        <option  value="<?=$v ?>" <?php echo (isset($item) && $item->unit === $v) ? 'selected':'' ?>><?=$v ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">单价</label>
                                <div class="controls">
                                    <input type="text" class="span2" name="price" maxlength="10" required id="" value="<?php echo isset($item)?$item->price : ''?>"> 元
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">状态</label>
                                <div class="controls">
                                    <label>
                                        <input type="radio" value="1"  name="status" <?php if (! isset($item) || $item->status == 1): ?>checked<?php endif ?> />
                                        上架</label>
                                    <label>
                                        <label>
                                            <input type="radio" value="0" name="status" <?php if (isset($item) && $item->status == 0): ?>checked<?php endif ?> />
                                            下架</label>
                                        <label>
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
