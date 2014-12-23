<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<style type="text/css">
    .form-horizontal .control-label {width: 80px;}
    .form-horizontal .controls {margin-left: 90px;}
</style>
<?php echo $admin_sidebar;?>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">物流费用提报</a>
        </div>
        <h1>物流费用提报</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <a href="#shipping" class="btn btn-danger" id="deduct" data-toggle="modal">新增提报</a>
                <div class="widget-box">
                    <form method="get" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td>
                                    <input type="text" class="datepicker span3" name="date" data-date-format="yyyy-mm" data-date-viewMode="months" placeholder="日期" value="<?=$_GET['date'];?>" />&nbsp;&nbsp;
                                    <select style="width: 150px" name="city" data-placeholder="城市">
                                            <option value="-1">全部城市</option>
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
                        <h5>物流费用列表</h5> &nbsp;&nbsp;&nbsp;
                        <!-- <a href="#" class="btn btn-small btn-info pull-right" style="margin: 10px 10px 0 0;">新增</a> -->
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>日期</th>
                                <th>城市</th>
                                <th>经营者</th>
                                <th>餐车</th>
                                <th>所属商户</th>
                                <th>物流费</th>                           
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach($shipping as $item):?>
                                    <tr>
                                        <td><?=date('Y-m', $item->shipping_time);?></td>
                                        <td><?=$item->city;?></td>
                                        <td><?=$item->realname;?></td>
                                        <td><?=$item->diner_name;?></td>
                                        <td><?=$item->merchant_name;?></td>
                                        <td><?=$item->amount;?></td>
                                 </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div id="shipping" class="modal hide fade in" style="display: none;">
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">×</a>
                            <h3>物流费用提报</h3>
                        </div>
                        <form id="shippingForm" name="myform" method="post" class="form-horizontal">
                            <div class="modal-body">
                                <div class="control-group">
                                    <lable class="control-label">提报月份：</lable>
                                    <div class="controls">
                                        <input type="text" class="datepicker span6" name="date" data-date-format="yyyy-mm" data-date-viewMode="months" placeholder="日期" value="<?=$shipping_time;?>" disabled />
                                    </div>
                                    <lable class="control-label">餐车：</lable>
                                    <div class="controls">
                                        <select id="mycity" style="width: 150px" name="city" data-placeholder="城市">
                                                <option value="-1">选择城市</option>
                                                <?php foreach($cities as $city):?>
                                                    <option value="<?=$city['id'];?>"><?=$city['name'];?></option>
                                                <?php endforeach;?>
                                        </select>
                                        <!-- <select style="width: 250px" id="diner" name="diner" data-placeholder="餐车">
                                                <option value="-1">选择餐车</option>
                                        </select> -->
                                        <input type="text" class="select2-input" id="diner" name="diner" style="width: 150px;display: none;" placeholder="请选择餐车">
                                        <div class="input-append symbol myadd">
                                            <span class="add-on btn btn-mini add"><i class="icon-plus"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group shippingItems">
                                    <!-- <div class="item">
                                        <lable class="control-label">物流费用：</lable>
                                        <div class="controls">
                                            
                                            <span>青岛市</span>
                                            &nbsp;&nbsp;
                                            <span>hev</span>
                                            &nbsp;&nbsp;
                                            <input type="hidden" class="myid" name="dinerId[]" value="25">
                                            <div class="input-append">
                                                <input type="text" name="amount[]" class="form-control text-center" style="width: 150px;">
                                                <span class="add-on">￥</span>
                                            </div>
                                            <div class="input-append symbol">
                                                <span class="add-on btn btn-mini delete"><i class="icon-minus"></i></span>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-info" id="allocateSalary">确定</a>
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
    // shipping
    var $modal = $('#shipping');
    // var $shippingModel = $modal.find('.shippingItems').find('.item').clone();
    // $shippingModel.find('.symbol').find('.add').remove();

    // 选择城市
    $modal.find('select[name=city]').on('click', function () {
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

    // 新增提报
    $modal.find('.myadd').find('.add').on('click', function () {
        // 先判断是否选中餐车
        if ($("#diner").val() != '') {
            // 判断餐车是否已经选出
            var flag = true;
            $('.myid').each(function(){
                if ($(this).val() == $("#diner").val()) {
                    flag = false;
                }
            });
            if (flag) {
                // 添加提报项
                $modal.find('.shippingItems').append('<div class="item"><lable class="control-label">物流费用：</lable><div class="controls"><span style="line-height:30px;">'+$("#mycity").select2('data').text+'</span>&nbsp;&nbsp;<span>'+$("#diner").select2('data').text+'</span>&nbsp;&nbsp;<input type="hidden" class="myid" name="dinerId[]" value="'+$("#diner").val()+'"><div class="input-append symbol" style="float:right;margin-left:10px"><span class="add-on btn btn-mini delete"><i class="icon-minus"></i></span></div><div class="input-append" style="float:right;"><input type="text" name="amount[]" class="form-control text-center" style="width: 150px;"><span class="add-on">￥</span></div></div></div>');
            }
        }

        // console.log($("#diner").select2('data').text);
        // console.log($("#mycity").select2('data').text)
        // $shippingModel.appendTo($modal.find('.shippingItems'));
    });

    // 删除提报
    $('.delete').live('click', function () {
        $(this).parents('.item').remove();
    });

    //提交提报
    $('#allocateSalary').on('click', function () {
        if ($('.shippingItems').find('.item').length > 0) {
            $.ajax({
                // url: "<?php echo site_url('/admini/shipping/add'); ?>",
                url:  "<?php echo site_url('/fundapi/shipping'); ?>",
                type: 'post',
                data: $('#shippingForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    if (data.code === 'OK') {
                        alert('添加扣项成功！');
                        window.location.reload();
                    } else if (data.code === 'SHIPPING.ERROR_VALIDATE') {
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
        }
    });
});
</script>
</body>
</html>
