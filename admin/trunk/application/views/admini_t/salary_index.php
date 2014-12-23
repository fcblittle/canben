<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $status = array(
    '0' => '未发放',
    '1' => '已发放',
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">经营者工资结算</a>
        </div>
        <h1>经营者工资列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <form method="get" action="<?php echo site_url('/admini/salary/index');?>" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td>
                                    <input type="text" class="datepicker span3" name="date" data-date-format="yyyy-mm" data-date-viewMode="months" placeholder="时间" value="<?=$_GET['date'];?>" />&nbsp;&nbsp;
                                    <select style="width: 150px" name="status" data-placeholder="发放状态">
                                            <option value="-1">是否发放</option>
                                            <option value="0">未发放</option>
                                            <option value="1">已发放</option>
                                    </select>&nbsp;&nbsp;

                                    <select style="width: 150px" name="type" data-placeholder="查询类型">
                                        <optgroup label="按商户查找">
                                            <option value="name">商户名</option>
                                            <option value="phone">商户登陆号码</option>
                                        </optgroup>
                                        <optgroup label="按餐车查找">
                                            <option value="diner">餐车名</option>
                                        </optgroup>
                                    </select>
                                    <input type="text" name="kw" value="<?=$_GET['kw'];?>" placeholder="关键字">
                                </td>
                                <td><input type="submit" value="查询" class="btn btn-primary"/></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>经营者工资列表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>时间</th>
                                <th>商户</th>
                                <th>餐车</th>
                                <th>应发工资</th>
                                <th>扣项合计</th>
                                <th>实发工资</th>
                                <th>是否分配</th>
                                <th>是否发放</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data as $item):?>
                                    <tr>
                                        <td><?=$item->timeRecord;?></td>
                                        <td><?=$item->merchant_name;?></td>
                                        <td><?=$item->diner_name;?></td>
                                        <td><?=$item->salary;?></td>
                                        <td><?=$item->deduction;?></td>
                                        <td><?=$item->salary - $item->deduction;?></td>
                                        <td><?=! empty($item->allocation) ? '已分配' : '未分配';?></td>
                                        <td><?=$status[$item->status];?></td>
                                        <td>
                                        <?php if(! empty($item->allocation)):?>
                                            <a class="btn btn-mini btn-info allocationDetail" data-diner="<?=$item->diner_name?>" data-date="<?=$item->timeRecord;?>">工资分配明细</a>
                                        <?php if($item->status == 0):?>
                                            <a class="btn btn-mini btn-danger doallocate" href="#" data-id="<?=$item->id?>" data-date="<?=$item->timeRecord;?>" data-diner="<?=$item->diner_name?>">发工资</a>
                                        <?php endif;?>
                                            <input type="hidden" name="allocation" value='<?=$item->allocation;?>'>
                                        <?php else:?>
                                            <span style="color: #999;">未进行工资分配~</span>
                                        <?php endif;?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <div id="allocationList" class="modal hide fade in" style="display: none;">
                            <div class="modal-header">
                                <a class="close" data-dismiss="modal">×</a>
                                <h3></h3>
                            </div>
                            <div class="modal-body form-horizontal">
                                
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn check" data-dismiss="modal">确定</a>

                                <a href="#" class="btn btn-info act" id="allocateSalary" style="display: none;">确定</a>
                                <a href="#" class="btn act" data-dismiss="modal" style="display: none;">取消</a>
                            </div>
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
$(function() {
    $('[name=status]').select2('val', "<?php echo $_GET['status'];?>");
    $('[name=type]').select2('val', "<?=$_GET['type'];?>");

    // 获取工资分配明细
    $('.allocationDetail').on('click', function () {
        var $modal, role;

        // var dataDinerId = $(this).attr('data-diner');
        var diner_name = $(this).attr('data-diner');
        var dataDate    = $(this).attr('data-date');

        clearModalFormData('#allocationList');

        $modal = $('#allocationList');
        // 设置头部
        setModalHeader('#allocationList', {
            name:  diner_name,
            date:  dataDate,
            title: '工资明细'
        });

        data = $.parseJSON($(this).siblings('[name=allocation]').val());

        // 设置主体
        $.each(data, function(i, v) {
            role = (v.role == 1) ? '店长' : '店小二';

            setModalFormData('#allocationList', {
                                name: role + '(' + v.realname + ')',
                                amount: v.salary
                            });
        });

        // $modal.find('.modal-footer').html($modal.data("btnPre"));
        $modal.find('.modal-footer').find('.check').show();
        $modal.find('.modal-footer').find('.act').hide();
        // 模态框
        $('#allocationList').modal({
            backdrop:true
        });
    });

    // 设置模态框头部
    function setModalHeader(selector, data)
    {
        $(selector)
        .find('.modal-header')
        .find('h3')
        .text(data.name + ' ' + getDateInfo(data.date) + ' ' + data.title);
    }

    // 设置表单数据
    function setModalFormData(selector, data)
    {
        var $dataModel, $dataBody, $controls;

        $dataModel = $('<div class="control-group"></div>');

        // label
        $dataBody = $('<lable class="control-label">' + data.name + '：' + '</lable>');

        // controls
        $controls = $('<div class="controls"><div class="input-append"></div></div>');
        $('<input />', {
            type: 'text',
            class: 'form-control text-center allocationItem',
            value: data.amount,
            disabled: true
        }).add('<span class="add-on">￥</span>').appendTo($controls.find('.input-append'));

        $dataBody.add($controls).appendTo($dataModel);
        $dataModel.appendTo($(selector).find('.modal-body'));
    }

    // 清除表单数据
    function clearModalFormData(selector)
    {
        $(selector).find('.modal-body').empty();
    }

    function getDateInfo(date)
    {
        var dateArr = date.split('-');

        return dateArr[0] + '年' + dateArr[1] + '月';
    }
});
</script>
<script type="text/javascript">
$(function () {
    // 发工资
    var $allocationList = $('#allocationList');
    $('.doallocate').on('click', function () {
        $allocationList.find('.modal-header').find('h3').text('发放工资');

        // 获取data值
        $allocationList.data('diner', {
            id: $(this).attr('data-id'),
            date: $(this).attr('data-date')
        });

        // 提示信息
        $allocationList.find('.modal-body')
                        .html('确定发放餐车 <font style="font-weight: bold;">' + $(this).attr('data-diner') + ' ' + $(this).attr('data-date') + '</font> 工资？');

        // 改变按钮状态
        $footer = $allocationList.find('.modal-footer');
        // $allocationList.data('btnPre', $footer.html());

        // $footer.html('<a href="#" class="btn btn-info" id="allocateSalary">确定</a><a class="btn" data-dismiss="modal">取消</a>');
        $footer.find('.act').show();
        $footer.find('.check').hide();

        $allocationList.modal({
            backdrop:true
        });
    });

    $allocationList.find('#allocateSalary').on('click', function () {
        $.ajax({
            url: '/fundapi/pay4Salary',
            data: $allocationList.data('diner'),
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.code != 'OK') {
                    alert(data.message);
                } else {
                    alert('工资发放成功！');
                }

                window.location.reload();
            }
        });

        $allocationList.removeData('diner');
    });
});
</script>
</body>
</html>
