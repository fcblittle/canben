<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">工资结算</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages; ?>
            <div class="row-fluid">
                <div class="span12">
                <!-- <h2><a data-toggle="modal" href="#operation" class="btn btn-primary">操作</a></h2> -->
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-th"></i></span>
                            <h5>工资明细</h5>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">
                                        <input type="text" class="datepicker" id="datepicker" name="date[beginning]" value="<?=$_GET['date']['beginning'];?>" class="span3" style="margin-left: 10px;" placeholder="开始月份">
                                        <input type="text" class="datepicker" id="datepicker" name="date[end]" value="<?=$_GET['date']['end'];?>" class="span3" style="margin-left: 10px;" placeholder="结束月份" />
                                        <select name="status" class="span2">
                                            <option>全部</option>
                                            <option <?php if($_GET['status'] == 1) echo 'selected'; ?> value="1">未分配</option>
                                            <option <?php if($_GET['status'] == 2) echo 'selected'; ?> value="2">已分配</option>
                                        </select>
                                         <input id="search-date" type="submit" class="btn btn-info" value="查询" style="margin-left: 10px;" />
                                     </div>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>月份</th>
                                        <th>经营者</th>
                                        <th>餐车</th>
                                        <th>应发</th>
                                        <th>扣项合计</th>
                                        <th>实发</th>
                                        <th>是否发放</th>
                                        <th>工资分配</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($list as $item):?>
                                    <tr>
                                        <td><?=$item->timeRecord;?></td>
                                        <td><?=$item->manager;?></td>
                                        <td><?=$dinerInfo[$item->diner_id]->diner_name;?></td>
                                        <td>￥<?=$item->salary;?></td>
                                        <td>￥<?=$item->deduction;?></td>
                                        <td>￥<?=$item->salary - $item->deduction;?></td>
                                        <td><?=$item->status;?></td>
                                        <td><?=empty($item->allocation) ? '未分配': '已分配';?></td>
                                        <td>
                                        <?php if($_USER->type === 'manager' 
                                                && empty($item->allocation)):?>
                                            <a class="btn btn-mini btn-info doAllocate" data-amount="<?=$item->salary - $item->deduction;?>" data-date="<?=$item->timeRecord;?>">工资分配</a>
                                        <?php elseif(! empty($item->allocation)):?>
                                            <a class="btn btn-mini btn-info allocationDetail" data-diner="<?=$item->diner_id?>" data-date="<?=$item->timeRecord;?>">工资分配明细</a>
                                        <?php endif;?>
                                            <a class="btn btn-mini btn-info deduction" data-diner="<?=$item->diner_id?>" data-date="<?=$item->timeRecord;?>">扣项明细</a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php echo($pager)?>
                </div>
            </div>
            <?php 
                if($_USER->type === 'manager') {
                    $this->region(':salary.modal');  
                }
                $this->region(':salary.list.modal');
            ?>
        </div>
    </div>
    
    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-datepicker.js'); ?>"></script>
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
    <script src="<?php echo $this->misc('module/fund/js/allocateSalary.js');?>"></script>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            format:   'yyyy-mm',
            viewMode: 'months'
        });
    </script>
<?php $this->region('Module\Common:foot') ?>