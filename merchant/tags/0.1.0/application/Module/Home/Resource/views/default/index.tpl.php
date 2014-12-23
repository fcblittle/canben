<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
<div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
</div>
<!--End-breadcrumbs-->

<!--Chart-box-->
<div class="container-fluid">
<div class="row-fluid">
    <div class="widget-box">
        <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
            <h5>信息统计</h5>
        </div>
        <div class="widget-content" >
            <div class="row-fluid">
                <span style="float: left">月度订单统计：</span>
            <div class="span1">
                <select class="select_month" id="year">
                    <?php
                    $thisYear = date("Y");
                        for ($i=$thisYear - 5; $i<$thisYear; $i++)
                        {
                            echo "<option value=$i>$i</option>";
                        }
                        ?>
                    ?>
                    <option value="<?=$thisYear?>" selected><?=$thisYear?></option>
                </select>
            </div>
            <div class="span1" style="margin: 0px;">
                <select class="select_month" id="month">
                    <?php
                    $m = date('m');
                    for ($i=1; $i<=12; $i++)
                    {
                        if($i<10){$i= "0".$i;}
                    ?>
                       <option value=<?=$i?> <?php if ($i == $m): ?>selected<?php endif ?>><?=$i?></option>
                    <?php } ?>
                </select>
            </div>
            </div>ount
            <div class="row-fluid">

                <div class="span9">
                    <div class="chart"></div>
                </div>
                <div class="span3">
                    <ul class="site-stats">
                        <li class="bg_lh"><a href="/merchant/staff"><i class="icon-user"></i> <strong><?php echo($count["staff"]);?></strong> <small>员工</small></a></li>
                        <li class="bg_lh"><a href="/customer/order"><i class="icon-shopping-cart"></i> <strong><?php echo($count["order"]);?></strong> <small>订单</small></a></li>
                        <li class="bg_lh"><a href="/merchant/diningcar"><i class="icon-tag"></i> <strong><?php echo($count["diningcar"]);?></strong> <small>餐车</small></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--End-Chart-box-->
</div>
</div>

<!--end-main-container-part-->
<script src="<?php echo $this->misc('js/excanvas.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
<script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.flot.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.flot.resize.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/fullcalendar.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.dashboard.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.gritter.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.interface.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.chat.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.wizard.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.uniform.js'); ?>"></script>
<script src="<?php echo $this->misc('js/select2.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.popover.js'); ?>"></script>
<script src="<?php echo $this->misc('js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>
<?php $this->region('Module\Common:foot') ?>