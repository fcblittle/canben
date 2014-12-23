<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
<?php echo '对不起，您访问的页面不存在！';die;?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">编辑参数</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages;?>
            <div class="row-fluid">
                <div class="span12">
                    <!--  促销 表单 -->
                    <div class="widget-box">
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">名称</label>
                                    <div class="controls">
                                        <input type="text" name="name" maxlength="10" required id="" value="<?php echo $_POST['name'] ?: $item->name ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">排序</label>
                                    <div class="controls">
                                        <input type="text" name="weight" maxlength="10" required value="<?php echo $_POST['weight'] ?: $item->weight ?>">
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