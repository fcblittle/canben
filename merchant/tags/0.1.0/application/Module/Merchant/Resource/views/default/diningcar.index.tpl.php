<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">餐车列表</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>餐车列表</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>餐车名称</th>
                                <th>第一联系人</th>
                                <th>联系电话</th>
                                <th>第二联系人</th>
                                <th>联系电话</th>
                                <th>车牌号</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                $statuses = array(
                                    '0' => '未通过',
                                    '1' => '正常',
                                    '2' => '停牌'
                                );
                                if (count($list) > 0):
                                    foreach ($list as $item):
                                        ?>
                                    <tr class="gradeX">
                                        <td><?php echo($item->diner_name)?></td>
                                        <td><?php echo($item->first_person)?></td>
                                        <td><?php echo($item->first_person_tel)?></td>
                                        <td><?php echo($item->second_person)?></td>
                                        <td><?php echo($item->second_person_tel)?></td>
                                        <td><?php echo($item->car_license_plate)?></td>
                                        <td><?=$statuses[$item->store_stauts]?></td>
                                        <td class="center">
                                            <a href="/merchant/diningcar/edit?id=<?=$item->id?>" class="btn btn-info btn-mini">编辑</a>
                                        </td>
                                    </tr>
                                    <?php
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>

                    </div>
                    <?php echo($pager)?>
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
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>、
<?php $this->region('Module\Common:foot') ?>