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
        <h1>官方菜品</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- 菜品 表单 -->
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label">菜品名称</label>
                                <div class="controls">
                                    <input type="text" name="food_name" maxlength="20" required id="" value="<?php echo isset($item)?$item->food_name : ''?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">供应价格</label>
                                <div class="controls">
                                    <input type="text" name="supply_price" required id="required" value="<?php echo isset($item)?$item->supply_price : ''?>" placeholder="0.00">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">销售价格</label>
                                <div class="controls">
                                    <input type="text" name="sale_price" required id="required" value="<?php echo isset($item)?$item->sale_price : ''?>" placeholder="0.00">
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
                                <label class="control-label">用餐时段</label>
                                <div class="controls">
                                    <select name="mealtime_id" required id="required" class="span2">
                                        <?php foreach ($mealtimes as $v): ?>
                                            <option  value="<?=$v->id ?>" <?php echo (isset($item) && $item->mealtime_id === $v->id) ? 'selected':'' ?>><?=$v->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">菜品图片</label>
                                <div class="controls">
                                    <span id="images"></span>
                                    <?php
                                    $images = isset($_POST['images']) ? $_POST['images'] : isset($item->images) ? $item->images : array();
                                    $imageArr = is_array($images) ? $images : explode(',', $images);
                                    $imageStr = implode(',', $imageArr);
                                    ?>
                                    <input type="hidden"  name="images" required value="<?=$imageStr?>">
                                    <ul class="list-img" style="margin-left:5px"></ul>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label">菜品简介</label>
                                <div class="controls">
                                    <!-- 加载编辑器的容器 -->
                                    <textarea style="heigth:100px;" class="span6" id="container" name="description"><?php echo isset($item)?$item->description : ''?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">菜品分类</label>
                                <div class="controls">
                                    <select required class="span6" name="cate_id">
                                        <?php if (! in_array($item->cate_id, $cat)): ?>
                                        <option value="" selected>　</option>
                                        <?php endif;
                                        if (! empty($cat)):
                                            foreach ($cat as $c):
                                                ?>
                                                <option  value="<?=$c->id ?>" <?php if (! empty($item) && $item->cate_id == $c->id): ?>selected<?php endif ?>><?=$c->classname ?></option>
                                            <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">菜品标签</label>
                                <div class="controls">
                                    <select multiple name="tag_id[]">
                                        <?php
                                        if (! empty($tags)):
                                            foreach ($tags as $v):
                                                ?>
                                                <option  value="<?=$v->id ?>" 
                                                    <?php if (! empty($item) && in_array($v->id, $item->tag_id)):?>selected<?php endif ?>><?=$v->name ?></option>
                                            <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>                            
                            <div class="control-group">
                                <label class="control-label">菜品原料</label>
                                <div class="controls">
                                    <div class="widget-box span6">
                                        <div class="widget-content nopadding">
                                        <table class="table table-bordered table-striped" id="original">
                                                <thead>
                                                    <tr>
                                                        <td>原料</td>
                                                        <td>数量</td>
                                                        <td>单位</td>
                                                        <td>操作</td>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>

                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">菜品状态</label>
                                <div class="controls">
                                    <label>
                                        <input type="radio" value="1"  name="foodstatus" <?php if (! isset($item) || $item->foodstatus == 1): ?>checked<?php endif ?> />
                                        上架</label>
                                    <label>
                                        <label>
                                            <input type="radio" value="0" name="foodstatus" <?php if (isset($item) && $item->foodstatus == 0): ?>checked<?php endif ?> />
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

<!-- 配置文件 -->
<script type="text/javascript" src="/asset/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/asset/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container',{
        toolbars: [
                      ['source', 'undo', 
                       'fullscreen',
                       'italic', //斜体
                       'redo', //重做
                       'bold', //加粗
                       'fontfamily', //字体
                       'fontsize', //字号
                       'forecolor', //字体颜色
                       
                       ],
                      [
                        'justifyleft', //居左对齐
                        'justifyright', //居右对齐
                        'justifycenter', //居中对齐
                        'justifyjustify', //两端对齐
                        'simpleupload', //单图上传
                       ]
                   ]
    });
    
</script>

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
<script id="materials-tpl-add" type="text/html">
    <tr>
        <td>
            <select name="materials1[]" id="">
                        <?php foreach($materials as $v) {?>
                        <option unit="<?php echo($v->unit)?>"  value="<?php echo($v->id)?>"><?php echo($v->name)?></option>
                    <?php }?>
                    </select>
        </td>
        <td width="2">
            <input type="text" name="materials2[]" class="text-center" value="1"/>
        </td>
        <td class="unit">
                <?php echo($v->unit)?>
        </td>
        <td class="text-center">
            <a href="#"><i class="icon-plus"></i></a>
            <a href="#"><i class="icon-minus"></i></a>
        </td>
    </tr>
</script>
<script id="materials-tpl" type="text/html">
    <?php if(isset($item->material)){?>
        <?php foreach($item->material as $mv) {?>
        <tr>
            <td>
                <select name="materials1[]" id="">
                    <?php foreach($materials as $v) {?>
                        <option unit="<?php echo($v->unit)?>"  <?php if($v->id==$mv[0]){ echo "selected";};?> value="<?php echo($v->id)?>"><?php echo($v->name)?></option>
                    <?php }?>
                </select>
            </td>
            <td width="2">
                <input type="text" name="materials2[]" class="text-center" value="<?php echo $mv[1];?>"/>
            </td>
            <td class="unit">
                <?php foreach($materials as $v) {?>
                    <?php
                    if($mv[0]==$v->id){
                        echo("$v->unit");
                    }
                    ?>
                <?php }?>
            </td>
            <td class="text-center">
                <a href="#"><i class="icon-plus"></i></a>
                <a href="#"><i class="icon-minus"></i></a>
            </td>
        </tr>
        <?php } ?>
    <?php }else{?>
        <tr>
        <tr>
            <td>
                <select name="materials1[]" id="">
                        <?php foreach($materials as $v) {?>
                        <option unit="<?php echo($v->unit)?>" value="<?php echo($v->id)?>"><?php echo($v->name)?></option>
                    <?php }?>
                    </select>
            </td>
            <td width="2">
                <input type="text" name="materials2[]" class="text-center" value="1"/>
            </td>
            <td class="unit">
                <?php echo($v->unit)?>
            </td>
            <td class="text-center">
                <a href="#"><i class="icon-plus"></i></a>
                <a href="#"><i class="icon-minus"></i></a>
            </td>
        </tr>
    <?php }?>
</script>
<script type="text/javascript">
$(function() {
    var prefix = "/",
        images = "<?= $imageStr ?>",
        list   = $(".list-img"),
        imageItem = $("#image-item").children();
    if ($.trim(images) !== "") {
        $.each(images.split(","), function(k, v) {
            var item = imageItem.clone();
            item.find("img").attr({
                "data-key": v,
                "src": prefix + v
            })
            list.append(item);
        });
    }
    $(".list-img li")
        .live("mouseenter", function() {
            var $item = $(this),
                remover = $('<a href="javascript:;" class="remove">删除</a>');
            if (! $item.siblings().length) return;
            remover
                .css({
                    "position": "absolute",
                    "top": 8,
                    "right": 8,
                    "background": "red",
                    "color": "#fff",
                    "padding": "2px 5px"
                })
                .on("click", function() {
                    if (! $item.siblings().length) return;
                    $item.remove();
                });
            $(this).append(remover);
        })
        .live("mouseleave", function() {
            $(this).find(".remove").remove();
        });
    // 提交表单
    $("#basic_validate").on("submit", function() {
        var imgs = [];
        $(".list-img img").each(function() {
            imgs.push($(this).attr("data-key"));
        });
        $("[name='images']").val(imgs.join(","));
    });
    $('#images').uploadify({
        'multi'    : true,
        'swf'      : '<?php echo base_url(), 'asset/libs/uploadify/uploadify.swf'; ?>',
        'uploader' : '<?php echo site_url('/admini/file/upload') ?>',
        'buttonText' : "请选择",
        'onUploadSuccess' : function(file, data, response) {
            var data = JSON.parse(data), item;
            item = imageItem.clone();
            item.find("img").attr({
                "data-key": data.content.key,
                "src": data.content.src
            });
            $(".list-img").append(item);
        }
    });
    for(var i=0;i<2;i++){
        $("#original").find("tbody").html($("#materials-tpl").html());
        $("select").select2();
        $("select").change(function(){
            $(this).parents("tr").find(".unit").html($(this).find("option:selected").attr("unit"));
        });

    }
    $("body").delegate("#original a", "click", function(){
        var  p = $(this).parents("tr");
        if($(this).find("i").hasClass("icon-plus")){
            p.after($("#materials-tpl-add").html());
            $("select").select2();
            $("select").change(function(){
                $(this).parents("tr").find(".unit").html($(this).find("option:selected").attr("unit"));
            });
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
