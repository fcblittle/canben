<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
<?php
$statuses = array(
    '0' => '待支付',
    '1' => '已取消',
    '2' => '已支付',
    '3' => '已确认',
    '4' => '已完成'
);
?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="订单列表" class="tip-bottom"><i class=""></i>订单列表</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages;?>
            
            <div class="row-fluid">
                <div class="span12">

                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>订单列表</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form action="" method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">
                                        <label>时间从</label>
                                        <input type="text" style="width: 10em" name="start" value="<?php echo $_GET['start'] ?: $item->start ?>" class="datepicker">&nbsp;&nbsp;到&nbsp;&nbsp;
                                        <input type="text" style="width: 10em" name="end" value="<?php echo $_GET['end'] ?: $item->end ?>" class="datepicker">&nbsp;&nbsp;
                                    <?php if($_USER->type === 'merchant'):?>
                                        <select name="diner_id" id="diner_id" style="width: 120px">
                                            <option value="-1">全部餐车</option>
                                            <?php
                                            if ($diner):
                                                $current = $_GET['diner_id'] ?: $item->diner_id ?: array();
                                                foreach ($diner as $key=>$value):
                                                    ?>
                                                    <option <?php if(in_array($value->id, $current)){echo("selected");} ?> value="<?php echo($value->id)?>"><?php echo($value->diner_name) ?></option>
                                                <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>&nbsp;&nbsp;
                                    <?php endif;?>
                                        <select name="status" id="diner_id" style="width: 120px">
                                            <option value="-1" <?php if (! isset($_GET['status']) || $_GET['status'] == -1): ?>selected<?php endif ?>>全部状态</option>
                                            <?php
                                                foreach ($statuses as $k => $v):
                                            ?>
                                                    <option <?php if(isset($_GET['status']) && $k == $_GET['status']){echo("selected");} ?> value="<?=$k?>"><?=$v?></option>
                                                <?php
                                                endforeach;
                                            ?>
                                        </select>&nbsp;&nbsp;
                                        <input type="text" name="order_id" style="width: 200px" maxlength="20" placeholder="请输入订单编号…" id="" value="<?php echo $_GET['order_id'] ?: $item->order_id ?>">
                                        <button style="margin-left: 1em" class="btn btn-primary" type="submit">查询</button>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="widget-box">
                                <form action="" method="get" class="form-inline widget-content batch-op">
                                    <div class="controls controls-row">
                                        <label>选中订单</label>
                                        <select name="batch" style="width: 100px">
                                            <option value="pay">全部支付</option>
                                        <?php if($_USER->type == 'manager'){?>
                                            <option value="cancel">全部取消</option>
                                        <?php }?>
                                        </select>
                                        <button style="margin-left: 1em" class="btn btn-primary" type="submit">确定</button>
                                    </div>
                                </form>
                            </div> -->
                            <table class="table table-bordered data-list">
                                <thead>
                                <tr>
                                    <th style="width: 5%">
                                        <div class="checker" id="uniform-undefined">
                                            <span class=""><input type="checkbox" style="opacity: 0;"></span>
                                        </div>
                                    </th>
                                    <th>订单编号</th>
                                    <th>餐车信息</th> 
                                    <th>下单时间</th>
                                    <th>支付时间</th>
                                    <th>订单金额</th>
                                    <th>订单状态</th>
                                    <th>送货时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (count($list) > 0):
                                    foreach ($list as $item):
                                ?>
                                        <tr class="gradeX" data-orderid="<?=$item->id ?>" data-orderno="<?=$item->id ?>">
                                            <td style="text-align: center">
                                                <div class="checker" id="uniform-undefined">
                                                    <span class=""><input type="checkbox" style="opacity: 0;"></span>
                                                </div>
                                            </td>
                                            <td><?php echo($item->id)?></td>
                                            <td><?php echo($item->diner_name)?></td>
                                            <td><?php echo($item->created)?></td>
                                            <td><?=$item->time_paid ? date('Y-m-d H:i:s', $item->time_paid) : '' ?></td>
                                            <td><span class="total_price"><?php echo($item->total_price)?></span></td>
                                            <td style="text-align: center">
                                                <?php
                                                switch ($item->status){
                                                    case 0:
                                                        echo("<span class='label label-important'>待支付</span>");
                                                        break;
                                                    case 1:
                                                        echo("<span class='label'>已取消</span>");
                                                        break;
                                                    case 2:
                                                        echo("<span class='label label-success'>已支付</span>");
                                                        break;
                                                    case 3:
                                                        echo("<span class='label label-info'>已确认</span>");
                                                        break;
                                                    default:
                                                        echo("<span class='label'>已作废</span>");
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?=date('Y-m-d H:i', $item->time_send);?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <ul class="dish-list">
                                            <?php
                                            if($map[$item->id]):
                                                foreach ($map[$item->id] as $aitem):
                                              ?>
                                                <li style="margin-right: 15px;padding:5px;">
                                                    <img style="max-height: 80px;max-width: 80px" alt="User" src="<?=$this->config['common']['officialBaseUrl'] ?>/<?php echo($aitem->images);?>"><br>
                                                    <div style="text-align: left">
                                                    <span class="label label-info"><?php echo($aitem->food_name);?> </span><span class="label label-important" style="font-weight:bold">x<?php echo($aitem->quantity);?>份</span><br>
                                                单价：<?php echo($aitem->supply_price);?>元<br>
                                                总价：<?php echo($aitem->supply_price * $aitem->quantity);?>元
                                                    </div></li>
                                            <?php
                                                        endforeach;
                                                    endif;
                                            ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                endif;
                                ?>
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
                    <form method="post" name="E_FORM" target="_blank" id="form-payment" action="https://Pay3.chinabank.com.cn/PayGate">
                        <input type="hidden" name="v_mid"         value="">
                        <input type="hidden" name="v_oid"         value="">
                        <input type="hidden" name="v_amount"      value="">
                        <input type="hidden" name="v_moneytype"   value="">
                        <input type="hidden" name="v_url"         value="">
                        <input type="hidden" name="v_md5info"     value="">
                        <input type="hidden" name="remark1"     value="">
                        <input type="hidden" name="remark2"     value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ok" >去网银支付</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- pay -->
    <!-- paid -->
    <div id="paid" class="modal fade" style="display: none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">完成支付</h4>
                </div>
                <div class="modal-body">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>如果未成功支付，请参考以下信息并重试支付</h5></div>
                        <div class="widget-content">
                            常见问题:
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">完成</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- paid -->
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
                    <button type="button" class="btn btn-default ok" >确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
   <script type="text/javascript">

   $(function () {
    var item = {orderNO: 0, status: 0};
    var $toPay = $("#to-pay");
   function prepareToPay(ids) {
       $(".loading").show();
       $.get("/api/merchant/merchantOrder/pay", {
           ids: ids
       }, function (data) {
           $(".loading").hide();
           if (data.code == 200) {
               var payment = data.content.payment;
               $("#to-pay .ok").removeClass("disabled");
               if (data.content.total == 0) {
                   $("#to-pay .ok").addClass("disabled");
                   return false;
               }
               $("#to-pay .total").text(data.content.total.toFixed(2));
               $toPay.find("input[name=v_mid]").val(payment.v_mid);
               $toPay.find("input[name=v_oid]").val(payment.v_oid);
               $toPay.find("input[name=v_amount]").val(payment.v_amount);
               $toPay.find("input[name=v_moneytype]").val(payment.v_moneytype);
               $toPay.find("input[name=v_url]").val(payment.v_url);
               $toPay.find("input[name=v_md5info]").val(payment.v_md5info);
               $toPay.find("input[name=remark1]").val(payment.remark1);
               $toPay.find("input[name=remark2]").val(payment.remark2);
           }
       });
       $("#to-pay").modal();
   }
    $(".cancel").on("click", function () {
        item.orderNO = $(this).parents("tr").attr("data-orderno");
        item.status = 1;
        $alert  = $("#alert");
        $alert.modal();
        $alert.find(".modal-body").html("您确定要取消订单吗？");
    });
    $("#alert .ok").on("click", function () {
        $.post(
            "/api/merchant/merchantOrder/update",
            {
                order_no: item.orderNO,
                status: item.status
            }, 
            function (data) {
                if (data.content == 1) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 50);
                } else {
                    $alert.find(".modal-body").html("此订单无法取消");
                    setTimeout(function () {
                        window.location.reload();
                    }, 50);
                }
            }
        );
    });
   // 支付
    $(".pay").on("click", function () {
        prepareToPay($(this).parents("tr").attr("data-orderid"));
    });
    $("form.batch-op .btn-primary").on("click", function () {
        var ids = [];
        $checked = $("table.data-list input:checked");
        if ($checked.length === 0) {
            alert("没有选择订单");
        }
        $checked.each(function (i, el) {
            var id = $(this).parents("tr").attr("data-orderid");
            typeof id !== "undefined" && ids.push(id);
        });
        prepareToPay(ids);
        return false;
    });
    $("#to-pay .ok").on("click", function () {
        if ($(this).hasClass("disabled")) return false;
        $("#to-pay").modal('hide');
        $("#paid").modal();
        $("#form-payment").submit();
    });
    $('#paid').on('hidden.bs.modal', function () {
        window.location.reload();
    })
});
   function byDinerSearch(){
       
       var obj=document.getElementById('diner_id');
       
       document.getElementById('select_id').setAttribute('value',getSelectedItem(obj));
       
    }
   function getSelectedItem(obj){
        var slct="";
        for(var i=0; i<obj.options.length ;i++)
        {
            
            if(obj.options[i].selected==true){
                slct+=obj.options[i].value+",";
            }
        }
         slct=slct.substr(0,slct.length-1);
        return slct;
    }
         $(function(){
            $('.datepicker').datetimepicker({
                format:'Y-m-d H:i:00',
                lang:"ch"
            });
        });
   </script>
<?php $this->region('Module\Common:foot') ?>