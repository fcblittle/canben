<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

    <!--main-container-part-->
    <div id="content" style="height:auto !important;">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">查看提报</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5><?php echo date('Y-m-d', $result[0]["created"])?>&nbsp;<?=$result[0]["diner_name"]?>的销售提报</h5>
                        </div>
                        <div class="widget-content" style="overflow:hidden;zoom:1;">
                            
                            <div class="widget-box">
                                <div class="widget-content">
                                <div style="display:inline-block;text-align:left;width:33%;">线上销售额：<span id="myonline"><?=$online?></span></div>
                                <div style="display:inline-block;text-align:center;width:33%;">线下销售额：<span class="myoffline"><?=$offline?></span></div>
                                <div style="display:inline-block;text-align:right;width:33%;">本日销售额：<span id="mytotal"><?php echo $online+$offline;?></span></div>
                                </div>
                            </div>
                            <div class="widget-box">
                            <div class="widget-content nopadding">
                            <table class="table table-bordered data-table" id="mytable">
                                <thead>
                                <tr>
                                    <th>餐品名称</th>
                                    <th>餐品数量</th>
                                    <th>单价</th>
                                    <th>小计</th>
                                    <!-- <th>操作</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (is_array($result)) {
                                        foreach ($result as $v) {
                                            echo '<tr>';
                                            echo "<td>{$v["food_name"]}</td>";
                                            echo "<td>{$v["count"]}</td>";
                                            echo "<td>{$v["sale_price"]}</td>";
                                            echo "<td>".($v["count"] * $v["sale_price"])."</td>";
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            </div>
                            </div>
                            <!-- <div class="widget-box">
                                <div class="widget-content">
                                    在此添加餐品&nbsp;&nbsp;
                                    <select name="" id="myselect" style="width:200px;">
                                    
                                        
                                    </select>&nbsp;&nbsp;
                                    <a href="#" class="btn btn-info btn-mini" id="myadd">添加</a>
                                </div>
                            </div> -->
                            <hr>
                            <div style="float:left;">线下总计：<span class="myoffline"><?=$offline?></span></div>
                            <!-- <div style="float:right;"><a href="#" class="btn btn-info btn" onclick="mysubmit()">提交</a></div> -->
                        </div>
                        
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
        $(document).ready(function(){
            $(".dataTables_filter").css("top","0px");
        });
    </script>

<?php $this->region('Module\Common:foot') ?>