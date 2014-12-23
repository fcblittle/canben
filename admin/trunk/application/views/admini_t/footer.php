<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2014 &copy; 吃在身边 版权所有 © 青岛德高软件开发有限公司 2005-2011 鲁ICP备08015072号 </div>
</div>
<div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5><i class="gi gi-circle_question_mark"></i> 确认</h5>
            </div>
            <div class="modal-body">
                <form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
                    <div class="form-group">
                        <h4 class="message text-center text-danger">您确定要进行此操作吗？</h4>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-sm btn-primary ok">确定</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>

<!--end-Footer-part-->
<script src="<?php echo base_url()?>asset/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.ui.custom.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap.min.js"></script>
<!--store_info edit_foodclass-->
<script src="<?php echo base_url()?>asset/js/bootstrap-colorpicker.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url()?>asset/js/masked.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.uniform.js"></script>
<!--/store_info edit_foodclass-->
<script src="<?php echo base_url()?>asset/js/select2.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>asset/js/matrix.js"></script>
<script src="<?php echo base_url()?>asset/js/matrix.tables.js"></script>
<script src="<?php echo base_url()?>asset/js/matrix.form_common.js"></script>
<script src="<?php echo base_url()?>asset/js/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.peity.min.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap-wysihtml5.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.PrintArea.js"></script>
<script>
    $('.textarea_editor').wysihtml5();
</script>
<script>
    $(function () {
        $(".confirm").on("click", function () {
            var $this = $(this);
            $("#modal-confirm .message").html($this.attr("data-confirm"));
            $("#modal-confirm").modal();
            $("#modal-confirm").data("ref", $this);
            return false;
        });
        $("#modal-confirm .ok").on("click", function () {
            var $elem = $("#modal-confirm").data("ref");
            $("#modal-confirm").modal("hide");
            if (typeof $elem.attr("href") !== "undefined") {
                window.location.href = $elem.attr("href");
            }
        });
    });
</script>
