<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">编辑菜品</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    <!-- 菜品 表单 -->
                    <div class="widget-box">
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">菜品分类</label>
                                    <div class="controls">
                                        <select required name="cate_id" class="span2">
                                            <?php
                                            if ($categories):
                                                $cat = $_POST['cate_id'] ?: $item->cate_id ?: 0;
                                                foreach ($categories as $key=>$value):
                                                    ?>
                                                    <option <?php if($cat == $value->id){echo("selected='true'");} ?> value="<?=$value->id?>"><?=$value->name;?></option>
                                                <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">菜品名称</label>
                                    <div class="controls">
                                        <input type="text" class="span2" name="food_name" maxlength="10" required id="" value="<?php echo $_POST['food_name'] ?: $item->food_name ?: '' ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">单价</label>
                                    <div class="controls">
                                        <input type="text" class="span2" name="price" required id="required" value="<?php echo $_POST['price'] ?: $item->price ?: '' ?>" placeholder="0.00"> 元
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">份</label>
                                    <div class="controls">
                                        <select name="unit" required id="required" class="span2">
                                            <?php
                                            if ($this->units):
                                                foreach ($this->units as $key=>$value):
                                                    ?>
                                                    <option <?php if($_POST['unit']==$value || $item->unit === $value){echo("selected='true'");} ?> value="<?php echo($value)?>"><?php echo($value) ?></option>
                                                <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">菜品图片</label>
                                    <div class="controls">
                                        <input type="file"  id="images"/>
                                            <?php
                                                $images = $_POST['images'] ?: $item->images;
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
                                        <textarea class="span6" name="description" rows="5"><?php echo $_POST['description'] ?: $item->description ?: '' ?></textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">菜品状态</label>
                                    <div class="controls">
                                        <?php
                                        $status = isset($_POST['foodstatus'])
                                            ? $_POST['foodstauts']
                                            : isset($item->foodstatus) ? $item->foodstatus : 1;
                                        ?>
                                        <label>
                                            <input type="radio" value="1" <?php if ($status): echo 'checked'; endif; ?> name="foodstauts" />
                                            上架</label>
                                        <label>
                                            <label>
                                                <input type="radio" value="0" <?php if (! $status): echo 'checked'; endif; ?> name="foodstauts" />
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
            <img style="max-height: 100px;max-width: 100px" class="store-img" style="margin-right: 5px;border:1px solid #ddd;padding: 5px" data-key="" src="" />
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
    <script src="<?php echo $this->misc('js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
    <script src="<?php echo $this->misc('libs/uploadify/jquery.uploadify.min.js'); ?>" type="text/javascript"></script>
    <script>
        $(function(){
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