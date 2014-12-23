<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#">订货管理</a> <a href="#" class="current">厨房日清单</a>
        </div>
        <h1>用餐时段</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <a href="<?=site_url('admini/Dish_mealtime/add')?>" class="btn btn-primary">新增</a>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>用餐时段</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 30%">名称</th>
                                <th>开始时间</th>
                                <th>结束时间</th>
                                <th style="width: 20%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list as $item): ?>
                            <tr>
                                <td><?=$item->name ?></td>
                                <td><?=$item->start ?></td>
                                <td><?=$item->end ?></td>
                                <td>
                                    <a class="btn btn-primary btn-mini" href="/index.php?/admini/dish_mealtime/edit/<?=$item->id ?>">编辑</a>
                                    <a class="btn btn-danger btn-mini confirm" href="/index.php?/admini/dish_mealtime/delete/<?=$item->id ?>">删除</a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
