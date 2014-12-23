<?php echo $admini_head;?>
<!--close-Header-part-->

<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="<?php echo site_url('/admini/dishTag/index');?>">原料分类列表</a> <a href="#" class="current"><?php echo isset($item) ? '编辑' : '添加' ?>菜品分类</a>
        </div>
        <h1><?php echo isset($item) ? '编辑' : '添加' ?>原料分类</h1>
    </div>
    <div class="container-fluid"><hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    </div>
                    <form class="form-horizontal" method="post">
                    <div class="widget-content nopadding">
                        <div class="control-group">
                            <label class="control-label">分类名称:</label>
                            <div class="controls">
                                <input type="text" class="span4" placeholder="分类名称" name="name" value="<?php echo isset($item->name) ? $item->name : '';?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">排序 :</label>
                            <div class="controls">
                                <input type="text" class="span4" placeholder="输入排序数字，越小的数字排行越靠前" name="weight" value="<?php echo isset($item->weight) ? $item->weight : '0';?>"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">保存</button>
                            <a class="btn btn-info" href="<?php echo site_url('/admini/dish_material_category/index');?>" >返回</a>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer;?>

</body>
</html>
