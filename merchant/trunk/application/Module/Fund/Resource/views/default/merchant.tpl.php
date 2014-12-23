<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="资金管理" class="tip-bottom"><i class=""></i>资金管理</a> <a href="/fund/merchant/index" title="<?=$account['name'];?>资金管理" class="tip-bottom"><i class=""></i><?=$account['name'];?>资金管理</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                <h2><a data-toggle="modal" href="#operation" class="btn btn-primary">操作</a></h2>
                    <div class="widget-box">
                        <div class="widget-title">
                            <ul class="nav nav-tabs">
                                <li <?php if(empty($_GET['type']) || $_GET['type'] == 'wallet') echo 'class="active"';?>><a href="/fund/<?=$account['role'];?>/index?type=wallet">我的钱包</a></li>
                                <li <?php if($_GET['type'] == 'account') echo 'class="active"';?>><a href="/fund/<?=$account['role'];?>/index?type=account">经营账户</a></li>
                            </ul>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <form method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">
                                        <input type="text" data-date-format="yyyy-mm-dd" class="datepicker" id="datepicker" name="date" value="<?=$_GET['date'];?>" style="margin-left: 10px;">
                                         <input id="search-date" type="button" class="btn btn-info" value="查询" style="margin-left: 10px;">
                                     </div>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
	                                <tr>
	                                	<th>时间</th>
                                        <th>摘要</th>
                                        <th>账户所属</th>
                                        <th>收/支</th>
                                        <th>余额</th>
	                                </tr>
                                </thead>
                                <tbody>
                                <?php if($variation):?>
                                <?php foreach($variation as $item):?>
                                    <tr>
                                        <td class="span3"><?=$item->created;?></td>
                                        <td><?=$item->summary;?></td>
                                        <td><?=$item->accountType;?></td>
                                        <td><?=$item->amount;?></td>
                                        <td><?=$item->balance;?></td>
                                    </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php echo($pager)?>
                </div>
            </div>
            <?php $this->region(':modal');?>
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
    <script src="<?php echo $this->misc('module/fund/js/fund.js');?>"></script>
    <script type="text/javascript">
        $('#datepicker').datepicker('hide');
    </script>
<?php $this->region('Module\Common:foot') ?>