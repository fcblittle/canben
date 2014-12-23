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
            <?php echo $messages; ?>
            <div class="row-fluid">
                <div class="span12">
                    <!--  添加菜品 表单 -->
                    <div class="widget-box">
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <input type="hidden" name="diner_id" value="<?php echo( $_GET['diner_id']);?>"/>
                                <div class="control-group">
                                    <label class="control-label">选择菜品</label>
                                    <div class="controls">
                                        <select name="dish[]" required id="required" multiple class="span10">
                                            <?php
                                                /*if ($dishes):
                                                $dish = $_POST['dish'] ?: $dish ?: array();
                                                foreach ($dishes as $key=>$value):
                                                    ?>
                                                    <option <?php if(in_array($value->id, $dish)){echo("selected='true'");} ?> value="<?php echo($value->id)?>"><?php echo($value->food_name) ?></option>
                                                <?php
                                                endforeach;
                                            endif;*/
                                            ?>
                                            <?php if($dish && $dishes):?>
                                            <?php foreach($dish as $item):?>
                                                <?php if(array_key_exists($item->food_id, $dishes)):?>
                                                    <option value="<?=$item->food_id;?>" <?=($item->status == 1) ? 'selected' : '';?>><?=$dishes[$item->food_id];?></option>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                            <?php endif;?>
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
                format:'Y-m-d H:i:00'
            });
        });
    </script>
<?php $this->region('Module\Common:foot') ?>