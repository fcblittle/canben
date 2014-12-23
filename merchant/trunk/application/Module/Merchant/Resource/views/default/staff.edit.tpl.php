<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom"><?php if (empty($_GET['id'])):?>新增店员<?php else:?>编辑店员信息<?php endif;?></a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages;?>
            <div class="row-fluid">
                <div class="span12">
                    <!--  促销 表单 -->
                    <div class="widget-box">
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">登录名/手机号码</label>
                                    <div class="controls">
                                        <input type="text" name="username" maxlength="11" required id="" value="<?php echo $_POST['username'] ?: $item->username ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">姓名</label>
                                    <div class="controls">
                                        <input type="text" name="realname" maxlength="10" required id="" value="<?php echo $_POST['realname'] ?: $item->realname ?>">
                                    </div>
                                </div>
                                <!-- 电话号码已经废弃
                                <div class="control-group">
                                    <label class="control-label">电话号码</label>
                                    <div class="controls">
                                        <input type="text" name="phone" maxlength="11" required id="" value="<?php echo $_POST['phone'] ?: $item->phone ?>">
                                    </div>
                                </div>
                                -->
                                <div class="control-group">
                                    <label class="control-label">密码</label>
                                    <div class="controls">
                                        <input type="password" name="pass" maxlength="20" minlength="6"  id="" value="">
                                        <?php if (! empty($_GET['id'])):?>
                                        <span class="help-block">修改密码请直接填写新密码，为空则不修改当前密码</span>
                                        <?php endif;?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">确认密码</label>
                                    <div class="controls">
                                        <input type="password" name="passV" maxlength="20" minlength="6"  id="" value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">角色</label>
                                    <div class="controls">
                                    <?php 
                                    
                                    if ($_USER->type === 'merchant') {
                                    ?>
                                        <label>
                                            <input type="radio" name="role" value="1" <?php if (!$item->role || $item->role == 1): echo 'checked'; endif; ?> />
                                            餐车管理员</label>
                                    <?php 
                                    }
                                    ?>
                                        <label>
                                            <input type="radio" name="role" value="2" required <?php if ($item->role == 2 || $_USER->type === 'manager'): echo 'checked'; endif; ?>/>
                                            店小二</label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">所在餐车</label>
                                    <div class="controls">
                                        <select name="diner_id" style="width: 218px" <?php if ($_USER->type === "manager") echo "disabled"?>>
                                            <?php if ($cars): foreach ($cars as $v): ?>
                                            <option value="<?=$v->id ?>" <?php if ($v->id == $item->diner_id) echo "selected";?>><?=$v->diner_name ?></option>
                                            <?php endforeach;endif ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">状态</label>
                                    <div class="controls">
                                        <label>
                                            <input type="radio" name="status" value="1" <?php if (! isset($item->status) || $item->status == 1): echo 'checked'; endif; ?> />
                                            启用</label>
                                        <label>
                                            <input type="radio" name="status" value="0" <?php if (is_numeric($item->status) && $item->status == 0): echo 'checked'; endif; ?>/>
                                            禁用</label>
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
    <script src="<?php echo $this->misc('js/jquery.datetimepicker.js'); ?>"></script>
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
    <script>
        $(function(){
            $('.datepicker').datetimepicker({
                format:'Y-m-d H:i:00'
            });
        });
    </script>
<?php $this->region('Module\Common:foot') ?>