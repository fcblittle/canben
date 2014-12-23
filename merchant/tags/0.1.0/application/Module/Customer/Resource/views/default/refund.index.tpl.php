<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">退款管理</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <?php
            $status = array(
                '0' => '待处理',
                '1' => '已驳回',
                '2' => '已同意',
                '3' => '已退款',
                '4' => '已取消'
            );
            ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>退款申请</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                            <form action="" method="get" class="form-inline widget-content">
                            <div class="controls controls-row">
                                <select name="store_id" required id="required" class="span1" style="width: 150px">
                                    <option value="-1">所有餐车</option>
                                    <?php foreach ($cars as $v): ?>
                                        <option value="<?=$v->id ?>" <?php if ($_GET['store_id'] == $v->id):?>selected<?php endif ?>><?=$v->diner_name ?></option>
                                    <?php endforeach ?>
                                </select>
                                <select name="status" required id="required" class="span1">
                                    <option  value="-1">所有状态</option>
                                    <?php foreach ($status as $k => $v): ?>
                                    <option  value="<?=$k ?>" <?php if ($_GET['status'] == $k): ?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                </select>
                                <select name="type" required id="required" class="span1" style="width: 120px">
                                    <option  value="order_no" <?php if (! $_GET['type'] || $_GET['type'] == 'order_no'): ?>selected<?php endif ?>>订单号</option>
                                    <option  value="account" <?php if ($_GET['type'] == 'account'): ?>selected<?php endif ?>>客户账号</option>
                                </select>
                                <input type="text" value="<?=$_GET['key']?>" name="key" placeholder="请输入要查询的内容…" class="span3 m-wrap">
                                <button style="margin-left: 1em" class="btn btn-primary" type="submit">查询</button>
                            </div>
                            </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>订单号</th>
                                    <th>餐车</th>
                                    <th>客户账号</th>
                                    <th style="width: 25%">退款理由</th>
                                    <th>审核备注</th>
                                    <th>申请时间</th>
                                    <th>处理时间</th>
                                    <th>支付时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($list):
                                    foreach ($list as $item):
                                        ?>
                                        <tr class="gradeX" data-orderno="<?=$item->order_no ?>">
                                            <td><a href="/customer/order?type=orderno&key=<?=$item->order_no ?>"><?=$item->order_no ?></a></td>
                                            <td><a href="/merchant/diningcar/edit?id=<?=$item->store_id?>"><?=$cars[$item->store_id]->diner_name ?></a></td>
                                            <td><?=$users[$item->user_id]->mobile_phone ?></td>
                                            <td><?=$item->message ?></td>
                                            <td><?=$item->remark ?></td>
                                            <td><?=date('Y-m-d H:i:s', $item->time_created) ?></td>
                                            <td><?=($item->time_processed ? date('Y-m-d H:i:s', $item->time_processed) : '') ?></td>
                                            <td><?=$item->time_paid ? date('Y-m-d H:i:s', $item->time_paid) : '' ?></td>
                                            <td><?=$status[$item->status] ?></td>
                                            <td class="center">
                                                <?php if ($item->status == 0): ?>
                                                <a href="javascript:;" class="btn btn-info btn-mini agree">同意</a>
                                                <a href="javascript:;" class="btn btn-danger btn-mini reject">驳回</a>
                                        <?php endif ?>
                                        <?php if ($item->status == 3): ?>
                                            --
                                        <?php endif ?>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                endif;
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo($pager)?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="remark" class="modal fade" style="display: none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">审核理由(可选)</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success alert-block" style="display: none">
                        提交成功
                    </div>
                    <div class="alert alert-error alert-block" style="display: none">
                        提交失败，请稍后重试
                    </div>
                    <div class="row-fluid">
                        <textarea name="remark" class="span12" style="height: 150px"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ok">确定</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
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
<script type="text/javascript">
$(function () {
    var item = {orderNO: 0, status: 0};
    $(".agree").on("click", function () {
        $("#remark").modal();
        item.orderNO = $(this).parents("tr").attr("data-orderno");
        item.status = 2;
    });
    $(".reject").on("click", function () {
        $("#remark").modal();
        item.orderNO = $(this).parents("tr").attr("data-orderno");
        item.status = 1;
    });
    $("#remark .ok").on("click", function () {
        $.post(
            "/api/customer/refund/update",
            {
                order_no: item.orderNO,
                remark: $("#remark [name=remark]").val(),
                status: item.status
            },
            function (data) {
                if (data.code == 200 && data.content == 1) {
                    $("#remark .alert-success").show();
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                } else {
                    $("#remark .alert-error").show();
                }
            }
        );
    });
});
</script>
<?php $this->region('Module\Common:foot') ?>