<?php $this->region('Module\Common:head') ?>
    <!--main-container-part-->
    <div id="content" style="margin-left: 0">

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages;?>
            
            <div class="row-fluid">
                <div class="span12">
                    <div id="alert" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">提示</h4>
                                </div>
                                <div class="modal-body">
                                    <h3><?=$result['v_pstatus'] == 20 ? '<span class="icon-ok-sign"></span>　恭喜，您已成功支付' : '<span class="icon-remove"></span>　支付失败，请稍后重试' ?></h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭页面</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loading progress progress-striped active" style="display:none;width: 200px;position: fixed;left:50%;margin-left:-100px;top:50%;border-radius: 15px;margin-top:-10px;box-shadow:0px 0px 8px rgba(0,0,0,.3)">
        <div class="bar" style="width: 100%;"></div>
    </div>
    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-timepicker.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.datetimepicker.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/masked.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.uniform.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/select2.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
<script>
    $("#alert").modal();
    $('#alert').on('hidden.bs.modal', function (e) {
        window.close();
    })
</script>
<?php $this->region('Module\Common:foot') ?>