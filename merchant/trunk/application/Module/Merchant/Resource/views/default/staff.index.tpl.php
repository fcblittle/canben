<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">店员管理</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    
                    <a href="/merchant/staff/add" class="btn btn-info btn">新增</a>
                    
                    <!-- 菜品 表格 -->
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>账户列表</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>登录名</th>
                                    <th>姓名</th>
                                    <th>餐车</th>
                                    <th>角色</th>
                                    <th>状态</th>
                                    
                                    <th>操作</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (count($list) > 0):
                                    foreach ($list as $item):
                                        if ($item->role == 1 
                                            && $item->id === $_USER->id) {
                                            continue;
                                        }
                                        ?>
                                        <tr class="gradeX">
                                            <td><?=$item->username ?></td>
                                            <td><?=$item->realname ?></td>
                                            <td><?php echo ($item->diner_id ? $cars[$item->diner_id]->diner_name : '')?></td>
                                            <td><?=$item->role == 1 ? '餐车管理员' : '店小二'  ?></td>
                                            <td><?php echo(($item->status==-1)?"禁用":"启用"); ?></td>
                                            <?php 
                                            if (!($_USER->type === "merchant" && $cars[$item->diner_id]->role == 2 || ($_USER->type === "manager" && $item->role == 1))){
                                            ?>
                                            <td class="center">
                                                <a href="/merchant/staff/edit?id=<?=$item->id?>" class="btn btn-info btn-mini">编辑</a>
                                                <?php if($item->status == 1):?>
                                                <a href="/merchant/staff/delete?id=<?=$item->id?>" class="btn btn-danger btn-delete btn-mini">禁用</a>
                                                <?php endif;?>
                                            </td>
                                            <?php
                                            } elseif ($_USER->type === "manager") {
                                                echo '<td class="center"></td>';
                                            } else {
                                                echo '<td class="center"></td>';
                                            }
                                            ?>
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