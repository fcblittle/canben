<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

<?php
$payStatus = array(
    '1' => '否',
    '2' => '是',
);
?>


    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="商户采购结算" class="tip-bottom"><i class=""></i>商户采购结算</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages;?>
            
            <div class="widget-front">
               <span class="wallet">我的钱包：<?=$balance->wallet?>元<br/>经营账户：<?=$balance->account?>元</span>
            </div>
            
            <div class="row-fluid">
                <div class="span12">

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>采购结算明细列表</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form action="" method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">
                                        <label>下单时间</label>
                                        <input type="text" style="width: 10em" name="start" id="start" value="<?php echo $_GET['start'] ?: $item->start ?>" class="datepicker">&nbsp;&nbsp;
                                        <select name="payStatus" id="payStatus" style="width: 120px">
                                           <option value="-1" <?php if (! isset($_GET['payStatus']) || $_GET['payStatus'] == -1){echo("selected");} ?>>是否支付</option>
                                           <?php
                                               foreach ($payStatus as $k => $v):
                                           ?>
                                                   <option <?php if(isset($_GET['payStatus']) && $k == $_GET['payStatus']){echo("selected");} ?> value="<?=$k?>"><?=$v?></option>
                                               <?php
                                               endforeach;
                                           ?>
                                       </select>&nbsp;&nbsp; 
                                        <select name="diner_id" id="diner_id" style="width:200px;">
                                            <option value="-1">所有餐车</option>
                                            <?php 
                                                if (is_array($diner_names)) {
                                                    foreach ($diner_names as $k => $v) {
                                                        if ($_GET['diner_id'] == $k) {
                                                            echo "<option value={$k} selected>{$v}</option>";
                                                        } else {
                                                            echo "<option value={$k}>{$v}</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <button style="margin-left: 1em" class="btn btn-primary" type="submit">查询</button>
                                    </div>
                                </form>
                            </div>
                            <div class="widget-box">
                                <form action="" method="get" class="form-inline widget-content batch-op">
                                    <div class="controls controls-row">
                                        <label>选中订单</label>
                                        <select name="batch" style="width: 100px">
                                            <option value="pay">全部支付</option>
                                        <?php if($_USER->type == 'manager'){?>
                                            <option value="cancel">全部取消</option>
                                        <?php }?>
                                        </select>
                                        <a href="javascript:;" class="btn btn-primary btn-mini payAll">确定</a>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-bordered data-list">
                                <thead>
                                <tr>
                                    <th style="width: 5%">
                                        <div class="checker" id="uniform-undefined">
                                            <span class=""><input type="checkbox" style="opacity: 0;"></span>
                                        </div>
                                    </th>
                                    <th>日期</th>
                                    <th>餐车名称</th> 
                                    <th>经营者</th>
                                    <th>采购总计</th>
                                    <th>是否结算</th>
                                    <th style="width: 15%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(! empty($dailyTotal)):?>
                                    <?php foreach ($dailyTotal as $key => $item):?>
                                       
                                        <tr class="gradeX" args-dinerid="<?=$key?>" args-amount="<?=$item['amount']?>">
                                            <td style="text-align: center">
                                                <div class="checker" id="uniform-undefined">
                                                    <span class=""><input type="checkbox" style="opacity: 0;"></span>
                                                </div>
                                            </td>
                                            <td><?=$_GET['start'];?></td>
                                            <td><?=$item['diner_name'];?></td>
                                            <td><?=$item['realname'];?></td>
                                            <td><?=$item['amount'];?></td>
                                            <td><?php echo $item['status'] ? '已支付' : '未支付';?></td>
                                            <td>
                                                <?php if($item['status']==0) {?>
                                                <a href="javascript:;" class="btn btn-primary btn-mini pay" args-amount="<?=$item['amount']?>">支付</a>
                                                <?php }?>
                                                <a href="/merchant/supplier/orderList?start=<?=$_GET['start'] . " 00:00:00";?>&end=<?=$_GET['start'] . " 24:00:00";?>&diner_id=<?=$key?>&status=<?=-1?>" class="btn btn-info btn-mini see">查看明细</a></td>
                                        </tr>
                                        
                                    <?php endforeach;?>
                                <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php echo($pager)?>
                </div>
            </div>
        </div>
    </div>
    <div class="loading progress progress-striped active" style="display:none;width: 200px;position: fixed;left:50%;margin-left:-100px;top:50%;border-radius: 15px;margin-top:-10px;box-shadow:0px 0px 8px rgba(0,0,0,.3)">
        <div class="bar" style="width: 100%;"></div>
    </div>
    <!-- pay -->
    <div id="to-pay" class="modal fade" style="display: none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">确认支付</h4>
                </div>
                <div class="modal-body">
                    <h4>支付总金额： <span class="total">0.00</span> 元</h4>
                    <form method="post" name="E_FORM" id="form-payment" action="">
                        <input type="hidden" name="diner_id"         value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ok" >确认支付</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div><!-- /.modal-content -->
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
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
    <div id="alert" class="modal fade" style="display: none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">提示</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info ok" >确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
   <script type="text/javascript">

   
$(function(){
    var ids=[];

  
   // 支付
    $(".pay").on("click", function () {
        var diner_id = $(this).parents("tr").attr("args-dinerid");
        var amount = parseFloat($(this).attr("args-amount")).toFixed(2);
        $("#to-pay .total").html(amount);
        $("#to-pay").find("input[name=diner_id]").val(diner_id);

        $("#to-pay").modal();
    });

    $(".payAll").on("click", function () {
        
        var amount = 0;
        $checked = $("table.data-list input:checked");
        if ($checked.length === 0) {
            alert("没有选择订单");
            return false;
        }
        $checked.each(function (i, el) {
            var id = $(this).parents("tr").attr("args-dinerid");
            typeof id !== "undefined" && ids.push(id);
        });
        //console.log(ids);
        $checked.each(function (i, el) {
            if (i == 0) {return true;}
            var sum = parseFloat($(this).parents("tr").attr("args-amount"));
            typeof sum !== "undefined" && (amount = amount + sum);
        });
        amount=amount.toFixed(2);
        $("#to-pay .total").html(amount);
        $("#to-pay").modal();
    });

    $("#to-pay .ok").on("click", function () {
            $("#to-pay").modal('hide');
            var args = {
                'ids' : ids,
                'diner_id': $("#to-pay").find("input[name=diner_id]").val(),
                'amount'  : parseFloat($("#to-pay .total").html()),
                'start': $("#start").val()
            };
            var tempData = "";
            $.ajax({
                url: "/fund/purchase/pay",
                type: 'post',
                data: args,
                dataType: 'text',
                async:false,
                success: function(data) {
                    try {
                        tempData=JSON.parse(data);//服务器回发JSON
                    }catch(e){
                        tempData=data;
                    } 
                    
                    if (tempData.code === 'PURCHASE.NOT_MATCH_SHIPPING_TIME') {
                        $('#alert').find('.modal-body').html(tempData.message);
                        $('#alert').modal('show');
                        return false;
                    } else {
                        alert(tempData);
                        window.location.reload();
                    }
                }
            });
    });
    
    $('#alert .ok').on('click', function () {
        $("#alert").modal('hide');
        var args = {
            'ids' : ids,
            'diner_id': $("#to-pay").find("input[name=diner_id]").val(),
            'amount'  : parseFloat($("#to-pay .total").html()),
            'start': $("#start").val(),
            'validate': false
        };

        $.ajax({
            url: "/fund/purchase/pay",
            type: 'post',
            data: args,
            success: function(data) {
                try {
                    tempData=JSON.parse(data);//服务器回发JSON
                }catch(e){
                    tempData=data;
                } 
                
                if (tempData.code === 'PURCHASE.NOT_MATCH_SHIPPING_TIME') {
                    $('#alert').find('.modal-body').html(tempData.message);
                    $('#alert').modal('show');
                    return false;
                } else {
                    alert(tempData);
                    window.location.reload();
                }
            }
        });
    });
 });

    

    $(function () {
            $(".datepicker").datetimepicker({
                lang:'ch',
                timepicker:false,
                format:'Y-m-d',
                formatDate:'Y-m-d'
            });
        });
    
   </script>
<?php $this->region('Module\Common:foot') ?>