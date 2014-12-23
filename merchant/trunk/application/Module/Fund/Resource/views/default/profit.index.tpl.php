<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
<!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="利润分配明细" class="tip-bottom"><i class=""></i>利润分配明细</a> </div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>利润分配明细</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                            <form action="" method="get" class="form-inline widget-content" onsubmit="return mycheck();">
                            <div class="controls controls-row">
                                &nbsp;
                                <input type="text" id="start" name="start"  data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['start']) ? $_GET['start'] : "" ?>" class="datepicker" style="width: 6em">
                                <label class="control-label">-</label>
                                <input type="text" id="end" name="end"  data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['end']) ? $_GET['end'] : "" ?>" class="datepicker" style="width: 6em">
                                &nbsp;
                                    
                                <select name="key" id="" style="width:200px;">
                                    <option value="-1">所有餐车</option>
                                    <?php 
                                        if (is_array($diners)) {
                                            foreach ($diners as $v) {
                                                if ($_GET['key'] == $v->id) {
                                                    echo "<option value='{$v->id}' selected>{$v->diner_name}</option>";
                                                } else {
                                                    echo "<option value='{$v->id}'>{$v->diner_name}</option>";
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
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="vertical-align:middle!important;">日期</th>
                                    <th style="vertical-align:middle!important;">餐车</th>
                                    <th style="vertical-align:middle!important;">销售额</th>
                                    <th style="vertical-align:middle!important;">成本</th>
                                    <th style="vertical-align:middle!important;">毛利润</th>
                                    <th style="vertical-align:middle!important;">当月累计<br>销售额</th>
                                    <th style="vertical-align:middle!important;">商户收益</th>
                                    <th style="vertical-align:middle!important;">商户当月<br>累计收益</th>
                                    <th style="vertical-align:middle!important;">经营者<br>预计收益</th>
                                    <th style="vertical-align:middle!important;">经营者当月<br>预计累计收益</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                if (is_array($list)) {
                                    foreach ($list as $v) {
                                ?>
                                <?php 
                                    $rateChangeString = $compensationMerchant = $compensationManager = '';

                                    if($v->allocation->compensation != 0) {
                                        if ($_USER->type === 'manager') {
                                            $rate = $v->allocation->rate * 100 . '%';
                                            $iconClass = 'icon-arrow-up';
                                            $color = 'green';
                                        } else {
                                            $rate = (1 - $v->allocation->rate) * 100 . '%';
                                            $iconClass = 'icon-arrow-down';
                                            $color = 'red';
                                        }

                                        $rateChangeString = " <font color=\"$color\"><i class=\"$iconClass\"></i>(rate: $rate)</font>";

                                        $compensationMerchant = '(<font color="red"> - ￥' . $v->allocation->compensation . '</font>)';
                                        $compensationManager = '(<font color="green"> + ￥' . $v->allocation->compensation . '</font>)';
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo date("Y-m-d", $v->timeRecord);?></td>
                                        <td><?php echo $diners[$v->diner_id]->diner_name;?></td>
                                        <td><?='￥' . $v->amount?></td>
                                        <td><?='￥' .($v->amount - $v->profit)?></td>
                                        <td><?='￥' .$v->profit?></td>
                                        <td><?='￥' .$v->allocation->monthlySales . $rateChangeString?></td>
                                        <td><?='￥' .$v->allocation->merchantDailyIncome?></td>
                                        <td><?='￥' .$v->allocation->merchantMonthlyIncome . $compensationMerchant?></td>
                                        <td><?='￥' .$v->allocation->salary?></td>
                                        <td><?='￥' .$v->allocation->monthlySalary . $compensationManager?></td>
                                    </tr>
                                <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                            </div>
                            </div>
                        </div>
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
    <script src="<?php echo $this->misc('module/fund/js/fund.js');?>"></script>
    <script type="text/javascript">
        // $('#datepicker').datepicker({});
        $(function () {
            $(".datepicker").datetimepicker({
                lang:'ch',
                timepicker:false,
                format:'Y-m-d',
                formatDate:'Y-m-d'
            });
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
    </script>
<?php $this->region('Module\Common:foot') ?>