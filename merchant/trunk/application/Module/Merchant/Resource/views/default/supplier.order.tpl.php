<?php $this->region('Module\Common:head') ?>
<style>
tr.dish td, .table-striped tbody>tr.dish:nth-child(odd)>td {
}
tr.dish {
    cursor: pointer;
}
tr.car td,
.table-striped tbody>tr.car:nth-child(odd)>td {
    background: #f3f3f3;
}
tr.car .name {
    padding-left: 2em;
}
</style>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">菜品订购</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="span8">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>官方菜品</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered" width="100%">
                                <tbody>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><span class="badge badge-info"><?=$cat->classname ?></span></td>
                                </tr>
                                <tr>
                                    <td>
                                    <ul class="dish-list">
                                        <?php
                                        foreach ($dishes as $item):
                                            if (! empty($item->cate_id) && $cat->id == $item->cate_id):
                                        ?>
                                        <li style="margin-right: 15px;padding:5px;" data-id="<?=$item->id ?>">
                                            <img style="width:80px;height:80px" alt="<?=$item->food_name ?>" src="<?php echo $this->config['common']['officialBaseUrl'], '/', $item->images[0] ?>"><br>
                                            <div style="text-align: left">
                                                <div style="text-align: center"><?=$item->food_name ?></div>
                                                进货价：<?=$item->supply_price ?>元<br>
                                                销售价：<?=$item->sale_price ?>元
                                            </div>
                                            <button class="btn btn-info btn-mini order">加入购物车</button>
                                        </li>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </ul></td>
                                </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                            </div>

                        </div>
                    </div>

                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"><i class="icon-time"></i></span>
                                <h5>购物车</h5><h5 style="float: right"><a href="javascript:;" id="clear">清空</a></h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-striped table-bordered" id="cart">
                                    <thead>
                                    <tr>
                                        <th style="width: 55%">产品名称</th>
                                        <th style="width: 15%">数量</th>
                                        <th style="width: 15%">金额(元)</th>
                                        <th style="width: 15%">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                    <tr class="sum-all">
                                        <td style="text-align: right"></td>
                                        <td style="text-align: right">
                                            总金额：
                                        </td>
                                        <td>&yen; <span class="sum-price" style="color:red">0.00</span></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div style="text-align: right"><button type="submit" class="btn btn-success submit-order">提交订单</button></div>
                    </div>
                    <?php echo($pager)?>
                </div>
            </div>
        </div>
    </div>

    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-datepicker.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-datetimepicker.min.js'); ?>"></script>
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
<!--ORDER-DETAIL-->
<div class="modal fade" id="order-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">订购详情</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary ok">添加到购物车</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="alert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">提示</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<div id="orderCheck" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">选择送货日期</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="control-group">
                    <lable class="control-label">请选择送货日期：</lable>
                    <div class="controls">
                        <input type="text" class="datepicker" name="shippingDate" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info checkOrder" data-dismiss="modal">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<script type="text/html" id="tpl-order-detail">
<div class="widget-box">
    <div class="widget-content nopadding">
        <div style="margin-right: 15px; overflow:hidden;padding:5px;">
            <img width="80" height="80" alt="User" class="dish-img" style="float: left" src="<?=$this->config['common']['officialBaseUrl']?>/<%=item.images[0]%>">
            <div style="text-align: left;margin-left: 100px">
                <div class="dish-name"><%=item.food_name%></div>
                进货价：<span class="dish-supply-price"><%=item.supply_price%></span>元<br>
                销售价：<span class="dish-sale-price"><%=item.sale_price%></span>元
            </div>
        </div>
    </div>
</div>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width: 70%">餐车</th>
        <th style="width: 30%">数量(份)</th>
    </tr>
    </thead>
    <tbody>
        <?php $sum = 10 * count($cars); ?>
        <?php foreach ($cars as $car): ?>
        <tr class="item-car" data-id="<?=$car->id ?>">
            <td data-id="<?=$car->id ?>"><?=$car->diner_name?></td>
            <td style="text-align: center"><input class="num" style="text-align:center;width:40px;min-height: 22px;height: 22px;" value="0"></td>
        </tr>
    <?php endforeach ?>
    </tbody>
    <tfoot>
    <tr>
        <td style="text-align: right">小计：</td>
        <td>
            数量：<span class="sum">0</span> (份)<br>
            金额：<span class="sum-price" style="color:red">0.00</span> (元)
        </td>
    </tr>
    </tfoot>
</table>
<p class="help-block">提示：每辆餐车最少需订购 <?=$min ?> 份，不订请填 0</p>
</script>
<!--TPL-CART-ITEM-->
<script type="text/html" id="tpl-cart-item">
<tr class="dish" data-id="<%=item.id%>">
    <td class="name">
        <div style="float: left;"><img src="<?=$this->config['common']['officialBaseUrl']?>/<%=item.images[0]%>" width="80" height="80" /></div>
        <div style="margin-left: 90px"><h5><%=item.food_name%></h5><p>进货价：<%=parseFloat(item.supply_price).toFixed(2)%> 元</p></div>
    </td>
    <td style="text-align: center"><span class="sum-num"><%=item.total%></span></td>
    <td style="text-align: center"><span class="sum-price"><%=(item.total * item.supply_price).toFixed(2)%></span></td>
    <td style="text-align: center">
        <a href="javascript:;" title="展开"><i class="icon-chevron-down"></i></a>&nbsp;
        <a href="#" title="删除" class="tip-top remove" data-original-title="Delete"><i class="icon-remove"></i></a>
    </td>
</tr>
<% $.each(item.cars, function(k,v) { %>
    <tr class="car" data-id="<%=v.id%>" style="display: none">
        <td class="name"><%=v.diner_name%></td>
        <td style="text-align: center">
            <input class="num" style="text-align:center;width:40px;min-height: 22px;height: 22px;" value="<%=v.num%>">
        </td>
        <td style="text-align: center">
            <span class="subtotal-price"><%=(v.num * item.supply_price).toFixed(2)%></span>
        </td>
        <td></td>
    </tr>
    <% }); %>
</script>
<script type="text/javascript">
var cars = <?=json_encode($cars) ?>;
var dishes = <?=json_encode($dishes) ?>;

var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
var shipping = <?php echo $shipping;?>;
shipping = shipping.pop();
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    onRender: function (date) {
        if (date.valueOf() <= now.valueOf()
            || (date.valueOf() - shipping.start) % (shipping.interval * 24 * 3600000) != 0) {
            return 'disabled';
        }
    }
});

$(function() {
    var min = <?=$min ?>,
        cart,
        current = null,
        $dialog = $("#order-detail"),
        $alert  = $("#alert");

    var Cart = function () {
        var self = this;
        this.storage = window.localStorage || null;
        this.$cart = $("#cart");
        this.$list = $("#cart tbody");
        this.$sum = $("#cart .sum-price");
        this.tpl = tpl("tpl-cart-item");
        this.data = {};
        data = this.storage ? $.parseJSON(this.storage.getItem("cart")) : {};
        if (! $.isEmptyObject(data)) {
            $.each(data, function (k, item) {
                self.addItem(item);
                $(".dish-list [data-id=" + item.id + "]").hide();
            });
        } else {
            data = {};
        }
        this.data = data;
    };

    Cart.prototype = {
        constructor: Cart,

        /**
         * 添加
         * @param item
         */
        addItem: function(item) {
            var self = this, $item = null;
            item.total = 0;
            if (typeof this.data[item.id] === "undefined") {
                $.each(item.cars, function (k, v) {
                    item.total += v.num;
                });
                this.data[item.id] = item;
                $item = $(this.tpl.render({item: item}));
                this.$list.append($item);
            }
            // 更新
            $item.find(".num").on("change", function () {
                var $this = $(this),
                    num = parseInt($this.val()),
                    $car = $(this).parents("tr"),
                    $dish = $car.prevAll(".dish:first"),
                    dish = self.data[$dish.attr("data-id")],
                    $dishCars = $dish.nextUntil(".dish"),
                    subsum = 0;
                if (num < min && num !== 0) {
                    num = min;
                }
                if (isNaN(num)) {
                    num = 0;
                }
                $this.val(num);
                dish.cars[$car.attr("data-id")].num = num;
                $car.find(".subtotal-price").text((num * dish.supply_price).toFixed(2));
                $dishCars.find(".num").each(function () {
                    subsum += parseInt($(this).val());
                });
                $dish.find(".sum-num").text(subsum);
                $dish.find(".sum-price").text((subsum * dish.supply_price).toFixed(2));
                self.updateSum();
                self.updateSotrage();
            });
            this.$sum.text(parseInt(this.$sum.text()) + item.total);
            this.updateSum();
            this.updateSotrage();
        },

        /**
         * 移除
         * @param id
         */
        removeItem: function (id) {
            var $row = this.$list.find("[data-id=" + id + "]");
            delete this.data[id];
            $row.nextUntil(".dish").remove();
            $row.remove();
            this.updateSum();
            this.updateSotrage();
        },

        /**
         * 清空
         */
        clear: function () {
            this.data = {};
            this.$list.empty();
            this.updateSotrage();
        },

        /**
         * 获取数据
         */
        getData: function () {
            var data = {};
            $.each(this.data, function (k, v) {
                var list = [];
                if (! $.isEmptyObject(v.cars)) {
                    $.each(v.cars, function (k1, v1) {
                        if (v1.num) {
                            list.push([k1, v1.num]);
                        }
                    });
                }
                data[k] = list;
            });
            return data;
        },

        /**
         * 更新存储
         */
        updateSotrage: function () {
            this.storage.setItem("cart", JSON.stringify(this.data));
        },

        /**
         * 更新总计
         */
        updateSum: function () {
            var self = this,
                sumPrice = 0,
                $dishes = this.$cart.find(".dish");
            $dishes.each(function () {
                sumPrice += parseFloat($(this).find(".sum-price").text());
            });
            this.$cart.find(".sum-all .sum-price").text(sumPrice.toFixed(2));
        }
    };
    cart = new Cart;
    // 最少10份
    $(document).on("change", ".num", function () {
        var val = parseInt(this.value);
        val = isNaN(val) ? 0 : val;
        this.value = (val < 10 && val !== 0) ? 10 : val;
    });
    // 订购详情
    $("button.order").on("click", function () {
        var $nums = $dialog.find(".num"),
            itemId = $.parseJSON($(this).parent().attr("data-id"));
        current = dishes[itemId];
        $dialog.find(".modal-body").html(tpl("tpl-order-detail").render({item: current}));
        $dialog.modal();
    });
    // 小计
    $(document).on("change input", "#order-detail .num", function () {
        var sum = 0;
        $dialog.find(".num").each(function () {
            var val = parseInt(this.value);
            sum += isNaN(val) ? 0 : val;
        });
        $("#order-detail .sum").text(sum);
        $("#order-detail .sum-price").text((sum * current.supply_price).toFixed(2));
    });
    // 添加到购物车
    $("#order-detail .ok").on("click", function () {
        var list = {};
        $("#order-detail tr.item-car").each(function () {
            var $this = $(this),
                num = parseInt($this.find(".num").val());
            for (var i in cars) {
                if (cars[i].id == $this.attr("data-id")) {
                    cars[i].num = num;
                    list[cars[i].id] = cars[i];
                }
            }
        });
        if ($.isEmptyObject(list)) return;
        current.cars = list;
        cart.addItem(JSON.parse(JSON.stringify(current)));
        $dialog.modal('hide');
        $(".dish-list [data-id=" + current.id + "]").fadeOut();
    });
    // 下单
    $("button.submit-order").on("click", function () {
        $('#orderCheck').modal();
    });
    $("button.checkOrder").on("click", function () {
        var data = cart.getData();
        if ($.isEmptyObject(data)) {
            $alert.find(".modal-body").html("您没有选择任何菜品");
            $('#orderCheck').modal('hide');
            $alert.modal();
            return false;
        }
        if (! $('[name=shippingDate]').val()) {
            $alert.find(".modal-body").html("请选择送货日期！");
            $('#orderCheck').modal('hide');
            $alert.modal();
            return false;
        }
        data['date'] = $('[name=shippingDate]').val();

        $.post(
            "/api/merchant/merchantOrder/add",
            {data: JSON.stringify(data)},
            function (data) {
                if (data.code == 200) {
                    cart.clear();
                    $alert.find(".modal-body").html("订单创建成功");
                    $alert.modal();
                    $alert.on('hidden.bs.modal', function (e) {
                        window.location.href = "/merchant/supplier/orderList";
                    })
                } else {
                    $alert.find(".modal-body").html("订单创建失败，请稍后重试");
                }
        });
    });
    // 移除
    $(document).on("click", "#cart .remove", function () {
        var id = $(this).parents("tr").attr("data-id");
        cart.removeItem(id);
        $(".dish-list [data-id=" + id + "]").fadeIn();
    });
    // 清空
    $("#clear").on("click", function () {
        cart.clear();
        window.location.reload();
    });
    // 展开
    $(document).on("click", "#cart .dish", function () {
        $("#cart .car").hide();
        $(this).nextUntil(".dish").toggle();
    });
});
</script>
<?php $this->region('Module\Common:foot') ?>