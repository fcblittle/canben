<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">线下提报转账</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            
            <div class="row-fluid">
                <div class="span12">
                    <?php if ($_USER->type === 'manager') { ?>
                    <a href="/fund/report/add" class="btn btn-info btn">新增提报</a>
                    <?php }?>
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>线下提报转账</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                            <form action="" method="get" class="form-inline widget-content" onsubmit="return mycheck();">
                            <div class="controls controls-row">
                                &nbsp;
                                <input type="text" id="start" name="start"  data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['start']) ? $_GET['start'] : date('Y-m-d', time() - 2592000) ?>" class="datepicker" style="width: 6em">
                                <label class="control-label">-</label>
                                <input type="text" id="end" name="end"  data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['end']) ? $_GET['end'] : date('Y-m-d', time()) ?>" class="datepicker" style="width: 6em">
                                &nbsp;
                                    
                                <select name="key" id="" style="width:200px;">
                                    <option value="-1">所有餐车</option>
                                    <?php 
                                        if (is_array($diner)) {
                                            foreach ($diner as $v) {
                                                if ($_GET['key'] === $v->diner_name) {
                                                    echo "<option value='{$v->diner_name}' selected>{$v->diner_name}</option>";
                                                } else {
                                                    echo "<option value='{$v->diner_name}'>{$v->diner_name}</option>";
                                                }
                                                
                                            }
                                        }
                                    ?>
                                </select>

                                <button style="margin-left: 1em" class="btn btn-info" type="submit">查询</button>
                            </div>
                            </form>
                            </div>
                            <div class="widget-box">
                            <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>餐车</th>
                                    <th>线上销售</th>
                                    <th>线下销售</th>
                                    <th>总金额</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                foreach ($result as $v) {
                                ?>
                                    <tr>
                                        <td><?=$v[0]["date"]?></td>
                                        <td><?=$v[0]["diner_name"]?></td>
                                        <td><?=$v["online"]?></td>
                                        <td><?=$v["offline"]?></td>
                                        <td><?php echo $v["online"]+$v["offline"];?></td>
                                        <td>
                                            <a href="/fund/report/view/<?=$v[0]['diner_id']?>/<?=$v[0]['created']?>" class="btn btn-info btn-mini">查看详细</a>
                                            <?php 
                                            if ($v[0]["transfered"] == 0 && $_USER->type === 'manager'){
                                            ?>
                                            <a href="javascript:;" class="btn btn-primary btn-mini pay" data-amount="<?=$v["offline"]?>" data-time="<?=$v[0]["created"]?>">转账</a>
                                            <a href="/fund/report/delete/<?=$v[0]["created"]?>" class="btn btn-mini btn-danger">删除</a>
                                            <?php 
                                            } else if ($v[0]["transfered"] == 0 && $_USER->type === 'merchant'){
                                                echo '&nbsp;未支付';
                                            } else {
                                            ?>
                                            &nbsp;支付时间：<?=date("Y-m-d", $v[0]["transfered"])?>
                                            <?php
                                            }
                                            ?>
                                            
                                        </td>

                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- pay -->
    <div id="to-pay" class="modal fade" style="display: none">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">确认转账</h4>
                </div>
                <div class="modal-body">
                    <h4>转账总金额： <span class="total">0.00</span> 元</h4>
                    <form method="post" name="E_FORM" id="form-payment" action="">
                        <input type="hidden" name="time"         value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ok" >确认转账</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- pay -->
    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-datepicker.js'); ?>"></script>
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
    <script src="<?php echo $this->misc('libs/uploadify/jquery.uploadify.min.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $(".datepicker").datetimepicker({
                lang:'ch',
                timepicker:false,
                format:'Y-m-d',
                formatDate:'Y-m-d'
            });
        });
        $(document).ready(function(){
            $(".dataTables_filter").remove();
        });

        function mycheck()
        {
            if (dateCompare($("#start").val(), $("#end").val()) === 1){
                alert("日期输入有误");
                return false;
            }
        }
        function dateCompare(date1,date2){
            date1 = date1.replace(/\-/gi,"/");
            date2 = date2.replace(/\-/gi,"/");
            var time1 = new Date(date1).getTime();
            var time2 = new Date(date2).getTime();
            if(time1 > time2){
                return 1;
            }else if(time1 == time2){
                return 2;
            }else{
                return 3;
            }
        }
        function mytransfer(mytime){
            if (confirm("确认要进行转账？")) {
                $.ajax({
                    url: "/api/fund/report/transfer",
                    data: {time:mytime},
                    async: false,
                    success: function(data) {
                        alert(data.message);
                    }
                });
            }
        }
        
    </script>
    <script type="text/javascript">
        $('.pay').on("click", function() {
            // alert($(this).attr('data-amount'));
            var amount = $(this).attr('data-amount');
            var time = $(this).attr('data-time');
            $("#to-pay .total").html(amount);
            $("#to-pay").find("input[name=time]").val(time);

            $("#to-pay").modal();

        });
        $("#to-pay .ok").on("click", function () {
            
            $("#to-pay").modal('hide');
            
            $("#form-payment").submit();
        });
    </script>
<?php $this->region('Module\Common:foot') ?>