<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">修改密码</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <?php echo $messages;?>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab1">修改密码</a></li>
                            <li><a data-toggle="tab" href="#tab2">银行卡信息</a></li>
                        </ul>
                    </div>
                    <div class="widget-content tab-content">
                        <div id="tab1" class="tab-pane active">
                            <form class="form-horizontal" method="post" action="/account/setting/password" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">当前密码</label>
                                    <div class="controls">
                                        <input class="text_value" name="pass" type="password" value="" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">新密码</label>
                                    <div class="controls">
                                        <input class="text_value" name="newpass" type="password" value="" />
                                    </div>
                                </div>
                                <input type="hidden" name="token" value="<?php echo $token ?>" />

                                <div class="form-actions">
                                    <input type="submit" value="提交" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                        <div id="tab2" class="tab-pane">
                            <form class="form-horizontal" method="post" action="/account/setting/bank" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">开户行</label>
                                    <div class="controls">
                                        <input class="text_value" name="bank_name" type="text" value="<?= $item->bank_name ?>" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">银行卡号</label>
                                    <div class="controls">
                                        <input class="text_value" name="bank_account" type="text" value="<?= $item->bank_account ?>" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">开户名</label>
                                    <div class="controls">
                                        <input class="text_value" name="bank_account_name" type="text" value="<?= $item->bank_account_name ?>" />
                                    </div>
                                </div>
                                <input type="hidden" name="token" value="<?php echo $token ?>" />

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