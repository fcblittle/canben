<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
<?php echo '对不起，您访问的页面不存在！';die;?>
    <style>
        img {
            max-width: none;
        }
    </style>
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
<div id="content-header">
    <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">餐厅管理</a></div>
</div>
<!--End-breadcrumbs-->

<!--Action boxes-->
<div class="container-fluid">
    <?php echo $messages ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                        <div class="control-group">
                            <label class="control-label">餐厅名称 <span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" name="store_name" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> maxlength="10" required id="" value="<?php echo $_POST['store_name'] ?: $item->store_name ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">电话 <span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" name="store_tel" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> required id="required" value="<?php echo $_POST['store_tel'] ?: $item->store_tel ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">餐厅logo <span class="required">*</span></label>
                            <div class="controls">
                                <?php if ($item->store_stauts != 3): ?><input type="file"  id="file_upload_single" /><?php endif ?>
                                <input type="hidden"  name="store_logo" required value="<?php echo $_POST['store_logo'] ?: $item->store_logo ?>"/>
                                <div class="img"><img class="merchant-logo" style="padding: 5px;border: 1px solid #ddd;max-height: 100px;max-width: 100px" src="/<?php echo $_POST['store_logo'] ?: $item->store_logo ?: '/misc/images/merchant/logo_default.png' ?>"></div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">商户地址 <span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" name="address" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> maxlength="20" required id="local_dress" value="<?php echo $_POST['address'] ?: $item->address ?>"> <input type="button" class="btn" value="定位" id="local_button"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <input type="hidden" name="pointx" value="<?php echo $_POST['pointx'] ?: $item->pointx ?>"/>
                            <input type="hidden" name="pointy" value="<?php echo $_POST['pointy'] ?: $item->pointy ?>"/>
                            <div class="controls">
                                <style type="text/css">img{max-width: inherit}</style>
                                <div id="container" style="width: 400px; height: 300px;border:1px solid #ddd"></div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">营业时间 <span class="required">*</span></label>
                            <div class="controls ">

                                <div class="input-append bootstrap-timepicker">
                                    <input type="text" class="timepicker " <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> name="store_hours_start"  id="" value="<?php echo $_POST['store_hours_start'] ?: $item->store_hours_start ?>">
                                    <span class="add-on"><i class="icon-time"></i></span>
                                </div>
                                    -
                                <div class="input-append bootstrap-timepicker">
                                    <input type="text" class="timepicker" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> name="store_hours_end" id="" value="<?php echo $_POST['store_hours_end'] ?: $item->store_hours_end ?>">
                                    <span class="add-on"><i class="icon-time"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">餐厅实体图 <span class="required">*</span></label>
                            <div class="controls">
                                <?php if ($item->store_stauts != 3): ?><input type="file"  id="file_upload_multi"/><?php endif ?>
<?php $images = $_POST['store_images'] ?: $item->store_images ?>
<?php $imageArr = explode(',', $images); ?>
                                <input type="hidden"  name="store_images" required value="<?= $images?>">
                                <ul class="list-img" style="margin-left:5px">
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">简介 <span class="required">*</span></label>
                            <div class="controls">
                                <textarea class="span6" name="description" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> rows="5"><?php echo $_POST['description'] ?: $item->description ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">餐厅氛围</label>
                            <div class="controls">
                                <textarea class="span6" name="store_atmosphere" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> rows="5"><?php echo $_POST['store_atmosphere'] ?: $item->store_atmosphere ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">餐厅特点</label>
                            <div class="controls">
                                <select multiple required class="span6" name="store_feature[]" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?>>
                                    <?php
                                    if ($features):
                                        $store_features = $_POST['store_feature'] ?: $item->store_feature ?: array();
                                        foreach ($features as $key=>$value):
                                            ?>
                                            <option <?php if(in_array($value->id, $store_features)){echo("selected='true'");} ?> value="<?=$value->id?>"><?=$value->slable_name;?></option>
                                        <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">菜系</label>
                            <div class="controls">
                                <select multiple required class="span6" name="foodclass[]" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?>>
                                    <?php
                                    if ($cuisines):
                                        $cuisine = $_POST['foodclass'] ?: $item->foodclass ?: array();
                                        foreach ($cuisines as $key=>$value):
                                            ?>
                                            <option <?php if(in_array($value->id, $cuisine)){echo("selected='true'");} ?> value="<?=$value->id?>"><?=$value->cuisine;?></option>
                                        <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">是否支持送餐</label>
                            <div class="controls">
                                <label>
                                    <input type="radio" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> value="1" <?php if ($item->delivery_yorn): echo 'checked'; endif; ?> name="delivery_yorn" />
                                    是</label>
                                <label>
                                <label>
                                    <input type="radio" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> value="0" <?php if (! $item->delivery_yorn): echo 'checked'; endif; ?> name="delivery_yorn" />
                                    否</label>
                                <label>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">送餐条件及范围</label>
                            <div class="controls">
                                <input type="text" name="condition_and_range" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> id="" class="span6" value="<?php echo $_POST['condition_and_range'] ?: $item->condition_and_range ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">商家建议人均</label>
                            <div class="controls">
                                <input type="text" name="per_capita" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> id="" class="span2" value="<?php echo $_POST['per_capita'] ?: $item->per_capita ?>"> 元
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="提交" <?php if ($item->store_stauts == 3): ?>disabled<?php endif ?> class="btn btn-success">
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
<img class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px;max-height: 100px;max-width: 100px" data-key="" src="" />
</li>
</div>
<!--end-main-container-part-->
<script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
<script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/bootstrap-timepicker.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/masked.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.uniform.js'); ?>"></script>
<script src="<?php echo $this->misc('js/select2.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
<script src="<?php echo $this->misc('js/wysihtml5-0.3.0.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
<script src="<?php echo $this->misc('libs/uploadify/jquery.uploadify.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=DxjKQhv6VyFfSmhfqzGhFgEB"></script>
<script type="text/javascript">
    $('select').select2();
    var prefix = "<?= $prefix ?>",
        images = "<?= $images ?>",
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
    
    $("#basic_validate").on("submit", function() {
        var imgs = [];
        $(".list-img img").each(function() {
            imgs.push($(this).attr("data-key"));
        });
        $("[name='store_images']").val(imgs.join(","));
    });
    
    var map = new BMap.Map("container"),
        markers = [];
    // 初始化地图，设置中心点坐标和地图级别
    map.addControl(new BMap.NavigationControl({
        anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
        type: BMAP_NAVIGATION_CONTROL_ZOOM}
    ));
    map.enableScrollWheelZoom();
    map.centerAndZoom("青岛", 15);
    var local = new BMap.LocalSearch(map, {
        renderOptions:{map: map}
    });
    map.addEventListener("click", function(e) {
        locate(e.point);
    });
    map.addEventListener("load", function() {
        // 初始定位
        var lng = $.trim($("input[name=pointx]").val()),
            lat = $.trim($("input[name=pointy]").val()),
            point = new BMap.Point(lng, lat);
        if (lng && lat) {
            locate(point);
            map.setCenter(point);
        }
    });
    // 地图定位
    function locate(point) {
        var marker;
        for (var i in markers) {
            map.removeOverlay(markers[i]);
        }
        marker = new BMap.Marker(point);
        markers.push(marker);
        marker.enableDragging();
        marker.addEventListener("dragend", function(e) {
            locate(e.point);
        });
        map.addOverlay(marker);
        $("[name='pointx']").val(point.lng);
        $("[name='pointy']").val(point.lat);
        var opts = {
            width : 200,
            height: 60,
            title : $("input[name=store_name]").val(),
            enableMessage: false,
            offset: {width: 0, height: -25}
        }
        var infoWindow = new BMap.InfoWindow($("#local_dress").val(), opts);
        map.openInfoWindow(infoWindow, point);
    }

    map.addEventListener("click", function(e) {
        locate(e.point);
    });
    $(function(){
        $('.timepicker').timepicker({showMeridian:false});
        $('input[type=checkbox],input[type=radio]').uniform();

        // 地图定位
        $("#local_button").click(function(){
            var address = $("#local_dress").val();
            if(address){
                var geo = new BMap.Geocoder();
                geo.getPoint(address, function(point) {
                    if (point) {
                        locate(point);
                        map.setCenter(point);
                    }
                }, "青岛市");
            }
        });

        $('#file_upload_single').uploadify({
            'multi'    : false,
            'swf'      : '<?php echo $this->misc('libs/uploadify/uploadify.swf'); ?>',
            'uploader' : '/common/file/upload',
            'buttonText' : "请选择",
            'onUploadSuccess' : function(file, data, response){
                console.log(data)
                var data = JSON.parse(data);
                $("[name='store_logo']").val(data.content.key)
                .next().find("img").attr("src", prefix + data.content.key);
                console.log(data);
            }
        });

        $('#file_upload_multi').uploadify({
            'multi'    : true,
            'swf'      : '<?php echo $this->misc('libs/uploadify/uploadify.swf'); ?>',
            'uploader' : '/common/file/upload',
            'buttonText' : "请选择",
            'onUploadSuccess' : function(file, data, response){
                var data = JSON.parse(data), item;
                item = imageItem.clone();
                item.find("img").attr({
                    "data-key": data.content.key,
                    "src": data.content.src
                });
                $(".list-img").append(item);
            }
        });
    });
</script>
<?php $this->region('Module\Common:foot') ?>