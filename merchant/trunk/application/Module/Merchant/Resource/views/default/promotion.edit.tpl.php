<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">编辑促销</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages; ?>
            <div class="row-fluid">
                <div class="span12">
                    <!--  促销 表单 -->
                    <div class="widget-box">
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">促销主题</label>
                                    <div class="controls">
                                        <input type="text" name="title" maxlength="10" required id="" value="<?php echo $_POST['title'] ?: $item->title ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">促销方式</label>
                                    <div class="controls">
                                        <select name="type" required id="required" class="span2">
                                            <?php
                                            if ($types):
                                                $type = $_POST['type'] ?: $item->type;
                                                foreach ($types as $key=>$value):
                                                    ?>
                                                    <option <?php if($type == $value->id){echo("selected='true'");} ?> value="<?=$value->id?>"><?= $value->text ?></option>
                                                <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">折扣</label>
                                    <div class="controls">
                                        <input type="text" class="span1" name="discount" required id="required" value="<?php echo $_POST['discount'] ?: $item->discount ?>"> %
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">开始时间</label>
                                    <div class="controls">
                                        <input type="text" name="start" value="<?php echo $_POST['start'] ?: $item->start ?>" class="datepicker span4"></div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">结束时间</label>
                                    <div class="controls">
                                        <input type="text" name="end" value="<?php echo $_POST['end'] ?: $item->end ?>" class="datepicker span4"></div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">选择菜品</label>
                                    <div class="controls">
                                        <select name="dish[]" required id="required" multiple class="span10">
                                            <?php
                                                if ($dishes):
                                                    $dish = $_POST['dish'] ?: $item->dish ?: array();
                                                    foreach ($dishes as $key=>$value):
                                                        ?>
                                                        <option <?php if(in_array($value->id, $dish)){echo("selected='true'");} ?> value="<?php echo($value->id)?>"><?php echo($value->food_name) ?></option>
                                                    <?php
                                                    endforeach;
                                                endif;
                                            ?>
                                        </select>
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

    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.datetimepicker.js'); ?>"></script>
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
            $('.datepicker').datetimepicker({
                format:'Y-m-d H:i:00',
                lang:"ch"
            });
        });
    </script>
<?php $this->region('Module\Common:foot') ?>