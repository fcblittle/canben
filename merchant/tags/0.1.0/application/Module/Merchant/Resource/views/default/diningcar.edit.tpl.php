<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">编辑餐车</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages;
            ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">餐车名称</label>
                                    <div class="controls">
                                        <input type="text" name="diner_name" maxlength="50" required id="" value="<?php echo $_POST['diner_name'] ?: $item->diner_name ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">第一联系人</label>
                                    <div class="controls">
                                        <input type="text" name="first_person" required id="required" value="<?php echo $_POST['first_person'] ?: $item->first_person ?>">
                                    </div>
                                    <label class="control-label">第一联系人电话</label>
                                    <div class="controls">
                                        <input type="text" name="first_person_tel" required id="required" value="<?php echo $_POST['first_person_tel'] ?: $item->first_person_tel ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">第二联系人</label>
                                    <div class="controls">
                                        <input type="text" name="second_person"  id="required" value="<?php echo $_POST['second_person'] ?: $item->second_person ?>">
                                    </div>
                                    <label class="control-label">第二联系人电话</label>
                                    <div class="controls">
                                        <input type="text" name="second_person_tel"  id="required" value="<?php echo $_POST['second_person_tel'] ?: $item->second_person_tel ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">车牌号</label>
                                    <div class="controls">
                                        <input type="text" disabled name="car_license_plate" required id="" value="<?php echo $_POST['car_license_plate'] ?: $item->car_license_plate ?>"> <span class="help-inline">请联系客服修改</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">经营区域</label>
                                    <div class="controls">
                                        <input type="text" disabled name="car_license_plate" required id="" value="<?php echo $areas[$item->area]->area ?>"> <span class="help-inline">请联系客服修改</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">早餐</label>
                                    <div class="controls">
                                        时间：<div class="input-append bootstrap-timepicker"><input type="text" class="timepicker" name="trip_time1_start" id="" value="<?php echo $_POST['trip_time1_start'] ?: $item->trip_time1_start ?>"><span class="add-on"><i class="icon-time"></i></span></div>
                                         -
                                        <div class="input-append bootstrap-timepicker"><input type="text" class="timepicker" name="trip_time1_end" id="" value="<?php echo $_POST['trip_time1_end'] ?: $item->trip_time1_end ?>"><span class="add-on"><i class="icon-time"></i></span></div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">午餐</label>
                                    <div class="controls">
                                        时间：<div class="input-append bootstrap-timepicker"><input type="text" class="timepicker" name="trip_time2_start" id="" value="<?php echo $_POST['trip_time2_start'] ?: $item->trip_time2_start ?>"><span class="add-on"><i class="icon-time"></i></span></div>
                                        -
                                        <div class="input-append bootstrap-timepicker"><input type="text" class="timepicker" name="trip_time2_end" id="" value="<?php echo $_POST['trip_time2_end'] ?: $item->trip_time2_end ?>"><span class="add-on"><i class="icon-time"></i></span></div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">晚餐</label>
                                    <div class="controls">
                                        时间：<div class="input-append bootstrap-timepicker"><input type="text" class="timepicker" name="trip_time3_start" id="" value="<?php echo $_POST['trip_time3_start'] ?: $item->trip_time3_start ?>"><span class="add-on"><i class="icon-time"></i></span></div>
                                        -
                                        <div class="input-append bootstrap-timepicker"><input type="text" class="timepicker" name="trip_time3_end"  id="" value="<?php echo $_POST['trip_time3_end'] ?: $item->trip_time3_end ?>"><span class="add-on"><i class="icon-time"></i></span></div>
                                        <span class="help-block">开始时间需小于结束时间</span>
                                    </div>

                                </div>
                                <div class="control-group">
                                    <label class="control-label">简介</label>
                                    <div class="controls">
                                        <textarea class="span6" name="description" rows="5"><?php echo $_POST['description'] ?: $item->description ?></textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">是否支持送餐</label>
                                    <div class="controls">
                                        <label>
                                            <input type="radio" value="1" <?php if ($item->delivery_yorn): echo 'checked'; endif; ?> name="delivery_yorn" />
                                            是</label>
                                        <label>
                                            <label>
                                                <input type="radio" value="0" <?php if (! $item->delivery_yorn): echo 'checked'; endif; ?> name="delivery_yorn" />
                                                否</label>
                                            <label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">送餐条件及范围</label>
                                    <div class="controls">
                                        <input type="text" name="condition_and_range" id="" class="span6" value="<?php echo $_POST['condition_and_range'] ?: $item->condition_and_range ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">商家建议人均</label>
                                    <div class="controls">
                                        <input type="text" name="per_capita" id="" class="span2" value="<?php echo $_POST['per_capita'] ?: $item->per_capita ?>"> 元
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" value="提交" class="btn btn-success">
                                </div>
                            </form>
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
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>、
    <script>
        $('.timepicker').timepicker({showMeridian:false});
    </script>
<?php $this->region('Module\Common:foot') ?>