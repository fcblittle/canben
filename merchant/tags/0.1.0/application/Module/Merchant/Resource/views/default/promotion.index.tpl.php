<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">促销管理</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ; ?>
            <div class="row-fluid">
                <div class="span12">
                    <a href="/merchant/promotion/add" class="btn btn-info btn">新增促销</a>
                    <!-- 促销 表格 -->
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>促销列表</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>促销主题</th>
                                    <th>促销方式</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>折扣</th>
                                    <th>促销状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                if (count($list) > 0):
                                    foreach ($list as $item):
                                        ?>
                                        <tr>
                                            <td><?php echo($item->title)?></td>
                                            <td><?php echo($types[$item->type]->text)?></td>
                                            <td><?php echo($item->start)?></td>
                                            <td><?php echo($item->end)?></td>
                                            <td><?php echo $item->discount, ' %'?></td>
                                            <td><?php
                                                if ($item->startTimestamp > REQUEST_TIME):
                                                    echo '<span class="label label-info">未开始</span>';
                                                elseif ($item->endTimestamp < REQUEST_TIME):
                                                    echo '<span class="label">已结束</span>';
                                                else:
                                                    echo '<span class="label label-success">进行中</span>';
                                                endif;
                                                ?></td>
                                            <td class="center">
                                                <a href="/merchant/promotion/edit?id=<?=$item->id?>" class="btn btn-info btn-mini">编辑</a>
                                                <a href="/merchant/promotion/delete?id=<?=$item->id?>" class="btn btn-danger btn-delete btn-mini">删除</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <ul class="dish-list">
                                                    <?php
                                                    if ($item->dish):
                                                    foreach ($item->dish as $dishId):
                                                        $dish = $dishes[$dishId];
                                                    ?>
                                                            <li style="margin-right: 15px;padding:5px;">
                                                                <div class=""> <img width="80" height="80" alt="User" src="/<?php echo($dish->images[0]);?>"> </div>
                                                                <div class="">
                                                                    <span class="label label-info"><?php echo($dish->food_name);?> </span>
                                                                    <br/>
                                                                    <?php echo($dish->price);?>元
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
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
    <script src="<?php echo $this->misc('libs/uploadify/jquery.uploadify.min.js'); ?>" type="text/javascript"></script>

<?php $this->region('Module\Common:foot') ?>