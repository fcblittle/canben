<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">菜品关联</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ; ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title">
                            <ul class="nav nav-tabs">
                                <li class="active"><a  data-toggle="tab" href="/merchant/dishRelation">餐车菜品</a></li>
<!--                                 <li><a href="/merchant/dish/restaurant">餐厅菜品</a></li> -->
                            </ul>
                        </div>
                        <div class="widget-content tab-content">
                            <?php
                            if ($list):
                            ?>
                            <table class="table table-bordered" width="100%">
                                <tbody>
                                <?php foreach ($list as $item):
                                        ?>
                                        <tr>
                                            <td><span class="badge badge-info"><?=$item->diner_name?></span></td>
                                            <td width="10%" class="center">
                                                <a href="/merchant/dishRelation/edit?diner_id=<?=$item->id?>" class="btn btn-info btn-mini">菜品管理</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <ul class="dish-list">
                                                    <?php
                                                    $thisMap = $item->store_name ? $map['store'][$item->id] : $map['car'][$item->id];
                                                    if ($thisMap):
                                                        foreach ($thisMap as $v):
                                                            $dish = $dishes[$v->food_id];
                                                            ?>
                                                            <li style="margin-right: 15px;padding:5px;">
                                                                <div class=""> <img style="max-width: 80px;max-height: 80px" alt="User" src="<?=($this->config['common']['officialBaseUrl'] . $dish->images[0]);?>"> </div>
                                                                <div class="">
                                                                    <?php echo($dish->food_name);?>
                                                                    <br/>
                                                                    售价：<?php echo($dish->sale_price);?>元<br>
                                                                    售完：<?php echo $v->sold_out ? '是' : '否' ?>
                                                                </div>
                                                            </li>
                                                        <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                            <?php echo($pager)?>
                            <?php else: ?>
                                <div class="alert alert-info">没有可用餐车</div>
                            <?php endif;?>
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

<?php $this->region('Module\Common:foot') ?>