<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        </div>
        <h1>发布新版本</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="control-group">
                                <label class="control-label">App</label>
                                <div class="controls">
                                    <select name="app" class="span2">
                                        <option value="1">餐本</option>
                                        <option value="2">餐本助手</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <select name="dev" class="span2">
                                        <option value="Android">Android</option>
                                        <option value="IOS">IOS</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    版本号
                                    <?php if(! empty($item)):?>
                                        <input type="text" class="span2" name="versionName" placeholder="数字,如1.1" value="<?=$item->versionName;?>" disabled="disabled" />
                                    <?php else:?>
                                        <input type="text" class="span2" name="versionName" placeholder="数字,如1.1" />
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">强制更新</label>
                                <div class="controls">
                                    <div data-toggle="buttons-radio" class="btn-group update">
                                      <button class="btn btn-primary <?php if(! empty($item) && $item->versionForcibly == '1') echo 'active';?>" type="button" value="1">是</button>
                                      <button class="btn btn-primary <?php if(empty($item) || $item->versionForcibly == '0') echo 'active';?>" type="button" value="0">否</button>
                                      <input type="hidden" name="update" value="<?=! empty($item) ? $item->versionForcibly :0;?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">安装包上传</label>
                                <div class="controls">
                                <input type="file" name="apk" />
                            </div>
                            <div class="control-group">
                                <label class="control-label">更新介绍</label>
                                <div class="controls">
                                    <textarea class="span8" name="intro" id="intro" style="height: 300px;"><?php if(! empty($item)) echo $item->versionContent;?></textarea>
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
<?php echo $footer;?>
<!-- 配置文件 -->
<script type="text/javascript" src="/asset/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/asset/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
$(function () {
    var ue = UE.getEditor('intro',{
        toolbars: [
                      ['source', 'undo', 
                       'fullscreen',
                       'italic', //斜体
                       'redo', //重做
                       'bold', //加粗
                       'fontfamily', //字体
                       'fontsize', //字号
                       'forecolor', //字体颜色
                       
                       ],
                      [
                        'justifyleft', //居左对齐
                        'justifyright', //居右对齐
                        'justifycenter', //居中对齐
                        'justifyjustify' //两端对齐
                       ]
                   ]
    });

    $('.update').find('[type=button]').on('click', function () {
        $('[name=update]').val($(this).val());
    });
});
</script>

<script type="text/javascript">
$(function () {
    // 设备
    <?php if (! empty($item)): ?>
        $('select[name=app]').select2("val", "<?=$item->appCode;?>");
        $('select[name=dev]').select2("val", "<?=$item->devCode?>");
    <?php endif;?>
});
</script>
</body>
</html>
