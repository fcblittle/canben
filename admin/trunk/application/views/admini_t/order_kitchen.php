<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#">订货管理</a> <a href="#" class="current">厨房清单</a>
        </div>
        <h1>厨房清单</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <form id="genKitchenDailyList" action="/index.php?/admini/order/genKitchenDailyList/" method="get">
                    <table width="100%" class="controls table-nav controls-row span12">
                        <tr>

                            <td width="100" colspan="7" ><input type="text" data-date="<?=date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" value="<?=date('Y-m-d') ?>" class="datepicker span11"></td>
                            <td><input type="submit" value="生成清单" class="btn"/></td>

                        </tr>
                    </table>
                </form>
                <div class="widget-box">
                <form class="form-search widget-content" style="overflow: hidden">
                <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                    <tr>
                        <td><select style="width: 120px">
                                <option>所有厨房</option>
                                <?php foreach ($kitchens as $v): ?>
                                <option value="<?=$v->id ?>" <?php if (isset($_GET['kitchen']) && $_GET['kitchen'] == $v->id):?>selected<?php endif ?>><?=$v->name ?></option>
                                <?php endforeach ?>
                        </select></td>
                        <td width="100">
                            <input type="text" data-date="2014-01-02" data-date-format="yyyy-mm-dd" value="2014-01-02" class="datepicker span11">
                        </td>
                        <td><label class="control-label">至</label></td>
                        <td width="100">
                            <input type="text" data-date="2014-01-02" data-date-format="yyyy-mm-dd" value="2014-01-02" class="datepicker span11">
                        </td>
                        <td><input type="submit" class="btn"/></td>
                    </tr>
                </table>
                </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>日清单列表</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>厨房</th>
                                <th>清单日期</th>
                                <th>总金额(元)</th>
                                <th>生成时间</th>
                                <th>更新时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total = 0 ?>
                            <?php foreach ($list['list'] as $item): ?>
                            <?php
                                $year = substr($item->date, 0, 4);
                                $month = substr($item->date, 4, 2);
                                $day = substr($item->date, 6, 2);
                                $total += $item->total;
                            ?>
                            <tr class="gradeA">
                                <td><?=$item->id ?></td>
                                <td><?=isset($kitchens[$item->kitchen_id]) ? $kitchens[$item->kitchen_id]->name : '' ?></td>
                                <td><?=$year . '年' . $month . '月' . $day . '日' ?></td>
                                <td><?=$item->total ?></td>
                                <td style="text-align: center"><?=date('Y-m-d H:i:s', $item->created) ?></td>
                                <td style="text-align: center"><?=date('Y-m-d H:i:s', $item->updated) ?></td>
                                <td style="text-align: center"><?=$item->status ? '正常' : '' ?></td>
                                <td align="center" style="text-align: center">
                                    <a class="btn btn-primary btn-mini" href="/index.php?/admini/order/kitchenDailyDetail/<?=$item->id ?>">查看</a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div>
                    总金额： <span class="price"><?=number_format($total, 2) ?> 元</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-Footer-part-->
<?php echo $footer;?>
</body>
</html>
