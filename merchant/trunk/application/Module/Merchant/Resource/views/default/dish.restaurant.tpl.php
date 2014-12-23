<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">菜品管理</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title">
                            <ul class="nav nav-tabs">
                                <li><a href="/merchant/dishRelation">餐车菜品</a></li>
<!--                                 <li class="active"><a data-toggle="tab" href="/merchant/dish/restaurant">餐厅菜品</a></li> -->
                            </ul>
                        </div>

                        <!-- dish-restaurant -->
<!--                         <div class="widget-content tab-content"> -->
<!--                             <a href="/merchant/dish/add" class="btn btn-info btn">添加菜品</a> -->
                            <!-- 菜品 表格 -->
<!--                             <div class="widget-box"> -->
<!--                                 <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span> -->
<!--                                     <h5>菜品列表</h5> -->
<!--                                 </div> -->
<!--                                 <div class="widget-content"> -->
<!--                                     <div class="widget-box"> -->
<!--                                         <form action="" method="get" class="form-inline widget-content"> -->
<!--                                             <div class="controls controls-row"> -->
<!--                                                 <select name="category" required id="required" class="span1"> -->
<!--                                                     <option  value="-1" >所有分类</option> -->
                                                    <?php foreach ($categories as $v): ?>
                                                    <option  value="<?=$v->id ?>"><?=$v->name ?></option>
    <?php endforeach ?>
<!--                                                 </select> -->
<!--                                                 <select name="status" required id="required" class="span1"> -->
<!--                                                        <option  value="-1" <?php if (! isset($_GET['status']) || $_GET['status'] == -1): ?>selected<?php endif ?>>所有状态</option> -->
<!--                                                        <option  value="1" <?php if ($_GET['status'] == 1): ?>selected<?php endif ?>>上架</option> -->
<!--                                                        <option  value="0" <?php if (isset($_GET['status']) && $_GET['status'] == 0): ?>selected<?php endif ?>>下架</option> -->
<!--                                                 </select> -->
<!--                                                 <select name="type" required id="required" class="span1"> -->
<!--                                                         <option  value="foodname" >菜品名</option> -->
<!--                                                 </select> -->
<!--                                                <input type="text" name="key" value="<?=$_GET['key'] ?>" placeholder="请输入要查询的内容…" class="span3 m-wrap"> -->
<!--                                                <button style="margin-left: 1em" class="btn btn-primary" type="submit">查询</button> -->
<!--                                             </div> -->
<!--                                         </form> -->
<!--                                     </div> -->
<!--                                     <table class="table table-bordered"> -->
<!--                                         <thead> -->
<!--                                         <tr> -->
<!--                                             <th>菜品名称</th> -->
<!--                                             <th>菜品价格</th> -->
<!--                                             <th>单位</th> -->
<!--                                             <th>菜品状态</th> -->
<!--                                             <th>菜品分类</th> -->
<!--                                             <th>操作</th> -->
<!--                                         </tr> -->
<!--                                         </thead> -->
<!--                                         <tbody> -->

                                        <?php
//                                         if ($list):
//                                             foreach ($list as $item):
//                                                 ?>
<!--                                                 <tr class="gradeX"> -->
<!--                                                    <td><?=$item->food_name ?></td> -->
<!--                                                    <td><?=$item->price ?></td> -->
<!--                                                    <td><?=$item->unit ?></td> -->
<!--                                                    <td><?=($item->foodstatus ? '上架' : '<span style="color: #bbb">下架</span>') ?></td> -->
<!--                                                    <td><?=$categories[$item->cate_id]->name?></td> -->
<!--                                                     <td class="center"> -->
<!--                                                        <a href="/merchant/dish/edit?id=<?=$item->id?>" class="btn btn-info btn-mini">编辑</a> -->
<!--                                                        <a href="/merchant/dish/delete?id=<?=$item->id?>" class="btn btn-danger btn-delete btn-mini">删除</a> -->
<!--                                                     </td> -->
<!--                                                 </tr> -->
                                            <?php
//                                             endforeach;
//                                         endif;
//                                         ?>
<!--                                         </tbody> -->
<!--                                     </table> -->
<!--                                 </div> -->
<!--                             </div> -->
                            <?php echo($pager)?>
<!--                         </div> -->
                        <!-- dish-restaurant -->
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