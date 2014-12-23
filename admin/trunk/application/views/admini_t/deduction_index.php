<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<style type="text/css">
    .form-horizontal .control-label {width: 80px;}
    .form-horizontal .controls {margin-left: 90px;}
</style>
<?php echo $admin_sidebar;?>
<?php $status = array(
    '0' => '未发放',
    '1' => '已发放',
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">经营者扣项提报</a>
        </div>
        <h1>经营者扣项列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="#deduction" class="btn btn-danger" id="deduct" data-toggle="modal">新增扣项</a>
                <div class="widget-box">
                    <form method="get" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td>
                                    <input type="text" class="datepicker span3" name="date" data-date-format="yyyy-mm-dd" placeholder="日期" value="<?=$_GET['date'];?>" />&nbsp;&nbsp;
                                    <select style="width: 150px" name="city" data-placeholder="城市">
                                            <option value="-1">选择城市</option>
                                            <?php foreach($cities as $city):?>
                                                <option value="<?=$city['id'];?>"><?=$city['name'];?></option>
                                            <?php endforeach;?>
                                    </select>&nbsp;&nbsp;

                                    <select style="width: 150px" name="type" data-placeholder="查询类型">
                                        <option value="merchant">商户名</option>
                                    </select>
                                    <input type="text" name="kw" value="<?=$_GET['kw'];?>" placeholder="请输入商户名">
                                </td>
                                <td><input type="submit" value="查询" class="btn btn-primary"/></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>经营者扣项列表</h5> &nbsp;&nbsp;&nbsp;
                        <!-- <a href="#" class="btn btn-small btn-info pull-right" style="margin: 10px 10px 0 0;">新增</a> -->
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>日期</th>
                                <th>城市</th>
                                <th>扣项类型</th>
                                <th>经营者</th>
                                <th>餐车</th>
                                <th>所属商户</th>
                                <th>金额</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach($deductions as $item):?>
                                    <tr>
                                        <td class="date"><?=date('Y-m-d', $item->timeRecord);?></td>
                                        <td><?=$item->city;?></td>
                                        <td><?=$deductionType[$item->typeId];?></td>
                                        <td><?=$item->realname;?></td>
                                        <td><?=$item->diner_name;?></td>
                                        <td><?=$item->merchant_name;?></td>
                                        <td>￥<?=$item->amount;?></td>
                                        <td>
                                            <a class="<?php echo $item->isEditable ? 'btn btn-mini btn-info edit' : 'label';?>" data-id="<?=$item->id;?>" data-city="<?=$item->city_id;?>">编辑</a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div id="deduction" class="modal hide fade in" style="display: none;">
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">×</a>
                            <h3>经营者扣项提报</h3>
                        </div>
                        <form id="deductForm" method="post" class="form-horizontal">
                            <div class="modal-body">
                                <div class="control-group">
                                    <lable class="control-label">日期：</lable>
                                    <div class="controls">
                                        <input type="text" class="datepicker span6" name="date" data-date-format="yyyy-mm-dd" placeholder="日期" value="<?=$_GET['date'];?>" />
                                    </div>
                                    <lable class="control-label">餐车：</lable>
                                    <div class="controls">
                                        <select class="city" style="width: 150px" data-placeholder="城市">
                                                <option value="0">选择城市</option>
                                                <?php foreach($cities as $city):?>
                                                    <option value="<?=$city['id'];?>"><?=$city['name'];?></option>
                                                <?php endforeach;?>
                                        </select>
                                        <input type="text" class="select2-input" id="diner" name="diner" style="width: 250px;display: none;" placeholder="请选择餐车">
                                    </div>
                                </div>
                                <div class="control-group deductionItems">
                                    <div class="item first">
                                        <lable class="control-label">扣项：</lable>
                                        <div class="controls">
                                            <select style="width: 150px" name="deduction[]" />
                                                <option value="0">扣项类型</option>
                                            <?php foreach($deductionType as $key => $v):?>
                                                <option value="<?=$key;?>"><?=$v?></option>
                                            <?php endforeach;?>
                                            </select>
                                            <div class="input-append">
                                                <input type="text" name="amount[]" class="form-control text-center amount" style="width: 150px;">
                                                <span class="add-on">￥</span>
                                            </div>
                                            <div class="input-append symbol">
                                                <span class="add-on btn btn-mini add"><i class="icon-plus"></i></span>
                                                <span class="add-on btn btn-mini delete"><i class="icon-minus"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" />
                        </form>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-info ok" id="allocateSalary">确定</a>
                            <a href="#" class="btn btn-info update" style="display: none;" id="allocateSalary">提交</a>
                            <a href="#" class="btn" data-dismiss="modal">取消</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pager">
        <?php echo $page;?>
    </div>
</div>
<?php echo $footer;?>
<script type="text/javascript">
$(function () {
    // init
    var city = "<?php echo !empty($_GET['city']) ? $_GET['city'] : ''; ?>";
    var type = "<?php echo !empty($_GET['type']) ? $_GET['type'] : ''; ?>";
    $('select[name=city]').select2("val", city);
    $('select[name=type]').select2("val", type);
});
</script>
<script type="text/javascript">
$(function () {
    // deduct
    var $modal = $('#deduction');
    var $deductionModel = $modal.find('.deductionItems').find('.item');

    // 选择城市
    $modal.find('.city').on('click', function () {
        var cityOptions = [];

        $.ajax({
            url: "<?php echo site_url('/admini/deduction/getDinerCity');?>",
            type: 'post',
            data: {city: $(this).val()},
            dataTyep: 'json',
            success: function (data) {
                $('#diner').select2({data: data.content});
            }
        });
    });

    // 新增扣项模态框
    $('#deduct').on('click', function () {
        $modal.data('symbol') && $modal.data('symbol').appendTo($modal.find('.first').find('.controls')) && $modal.removeData('symbol');
        $modal.find('.ok').show();
        $modal.find('.update').hide();
    });

    // 新增扣项
    $modal.find('.deductionItems').find('.add').live('click', function () {
        /*$deductionModel.clone().removeClass('first')
            .appendTo($modal.find('.deductionItems'))
            .find('.symbol').find('.add').remove();*/

        var $item = $('<div class="item"><lable class="control-label">扣项：</lable><div class="controls"></div></div>');

        // select
        var $selectDeductionType = $('<select style="width: 150px" name="deduction[]" /></select>');
        var options = ['<option value="-1">扣项类型</option>'];
        <?php foreach($deductionType as $key => $v):?>
            options.push("<option value=<?=$key?>><?=$v;?></option>");
        <?php endforeach;?>
        $(options.join('')).appendTo($selectDeductionType);
        $selectDeductionType.appendTo($item.find('.controls'));

        // input
        $('<div class="input-append" style="margin-left: 6px;"><input type="text" name="amount[]" class="form-control text-center" style="width: 150px;"><span class="add-on">￥</span></div>').appendTo($item.find('.controls'));

        // symbol
        $('<div class="input-append symbol" style="margin-left: 6px;"><span class="add-on btn btn-mini delete"><i class="icon-minus"></i></span></div>').appendTo($item.find('.controls'));

        $item.appendTo($('.deductionItems'));

        $item.find('select').select2();
    });
    // 删除扣项
    $('.delete').live('click', function () {
        if ($(this).parents('.item').hasClass('first')) { alert('此元素不可删除！');return;};
        $(this).parents('.item').remove();
    });

    // 提交扣项
    $modal.find('.ok').on('click', function () {
        $.ajax({
            url: "<?php echo site_url('/admini/deduction/add'); ?>",
            type: 'post',
            data: $('#deductForm').serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.code === 'OK') {
                    alert('添加扣项成功！');
                    window.location.reload();
                } else if (data.code === 'DEDUCTION.ERROR_VALIDATE') {
                    var message = [];
                    $.each(data.message, function (i, v) {
                        message.push(v);
                    });

                    alert(message.join('\n'));
                } else {
                    alert(data.message);
                }
            }
        });
    });

    // 编辑模态框
    $('.edit').on('click', function () {
        var id = $(this).attr('data-id');
        var city = $(this).attr('data-city');
        var date = $(this).parent('td').siblings('.date').text();
        var $modalBody = $modal.find('.modal-body');

        $.ajax({
            url: "<?php echo site_url('/admini/deduction/getItem');?>",
            type: 'post',
            data: {id: id,city: city},
            dataType: 'json',
            success: function (data) {
                if (data.code !== 'OK') {alert(data.message)};
                $modalBody.find('[name=date]').val(date);
                $modalBody.find('.city').select2('val', city);

                $modalBody.find('.first').find('select').select2('val', data.content.typeId);
                $modalBody.find('#diner').select2({data: data.content.diner});
                $modalBody.find('#diner').select2('data', {id: data.content.diner_id, text: data.content.diner_name});
                $modalBody.find('.first').find('.amount').val(data.content.amount);
                ! $modal.data('symbol') && $modal.data('symbol', $modalBody.find('.first').find('.symbol').remove());

                $modal.find('#deductForm').find('input[name=id]').val(data.content.id);
                $modal.find('.ok').hide();
                $modal.find('.update').show();

                $modal.modal();
            }
        });
    });

    // 编辑扣项
    $modal.find('.update').on('click', function () {
        $.ajax({
            url: "<?php echo site_url('/admini/deduction/edit');?>",
            type: 'post',
            dataType: 'json',
            data: $('#deductForm').serialize(),
            success: function (data) {
                if (data.code === 'OK') {
                    alert('修改扣项成功！');

                    $modal.removeData('symbol');
                    window.location.reload();
                } else if (data.code === 'DEDUCTION.ERROR_VALIDATE') {
                    var message = [];
                    $.each(data.message, function (i, v) {
                        message.push(v);
                    });

                    alert(message.join('\n'));
                } else {
                    alert(data.message);
                }
            }
        });
    });
});
</script>
</body>
</html>
