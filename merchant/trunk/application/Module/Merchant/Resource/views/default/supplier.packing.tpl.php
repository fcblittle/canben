<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">店员管理</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>分装清单</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form class="form-search widget-content" action="<?=url('merchant/supplier/packing') ?>" style="overflow: hidden">
                                    <div class="controls controls-row">
                                    <?php if($_USER->type === 'merchant'):?>
                                        <select style="width: 120px" name="diner_id">
                                            <option value="0">所有餐车</option>
                                            <?php if ($diners): ?>
                                                <?php foreach ($diners as $v): ?>
                                                    <option value="<?=$v->id ?>" <?php if (isset($_GET['diner_id']) && $_GET['diner_id'] == $v->id):?>selected<?php endif ?>><?=$v->diner_name ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>&nbsp;&nbsp;&nbsp;
                                    <?php endif;?>
                                        <label class="control-label">配送日期: </label>
                                        <input type="text" name="start"  data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['start']) ? $_GET['start'] : date('Y-m-d') ?>" class="datepicker" style="width: 6em">
                                        <label class="control-label">-</label>
                                        <input type="text" name="end"  data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['end']) ? $_GET['end'] : date('Y-m-d') ?>" class="datepicker" style="width: 6em">
                                        &nbsp;
                                        <input type="submit" class="btn btn-primary" value="查询"/>&nbsp;
                                        <a href="javascript:;" class="btn btn-info" id="print">打印</a>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-bordered" id="list">
                                <thead>
                                <tr>
                                    <th>餐车</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (! empty($diners)): ?>
                                    <?php
                                        foreach ($diners as $item):
                                            if (empty($dinerMaterials[$item->id])) continue;
                                    ?>
                                            <tr class="gradeA" style="background: #FFFBF1">
                                                <td>
                                                    <span class="badge badge-info"><?=$item->diner_name ?></span>
                                                    <span class="pull-right">
                                                        配送日期：
                                                        <label class="label"><?=$start;?></label>&nbsp;&nbsp;至&nbsp;&nbsp;<label class="label"><?=$end;?></label>
                                                    </span>
                                                </td>
                                                <!-- <td class="pull-right"><span class="badge badge-info"><?=$start;?>-<?=$end;?></span></td> -->
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>产品</th>
                                                            <th>规格</th>
                                                            <th>数量</th>
                                                            <th>单位</th>
                                                            <th>单价(元)</th>
                                                        </tr>
                                                        <?php foreach ($dinerMaterials[$item->id] as $k => $v): ?>
                                                            <?php $material = $materials[$k] ?>
                                                            <tr>
                                                                <td><strong><?=$material->name ?></strong></td>
                                                                <td><?=$material->spec ?></td>
                                                                <td><?=$v ?></td>
                                                                <td><?=$material->unit ?></td>
                                                                <td><?=$material->price ?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                        </thead>
                                                    </table>
                                                </td>
                                            </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>
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
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.PrintArea.js'); ?>"></script>
    <script src="<?php echo $this->misc('libs/uploadify/jquery.uploadify.min.js'); ?>" type="text/javascript"></script>
<script>
    $(function () {
        $(".datepicker").datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d'
        });
    });
    $("#print").on("click", function () {
        $("#list").printArea();
    });
</script>
<?php $this->region('Module\Common:foot') ?>