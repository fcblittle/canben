<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#">订货管理</a> <a href="#" class="current">商户结算</a>
        </div>
        <h1>商户结算</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <form id="genKitchenDailyList" action="<?=site_url('admini/merchant/genlist') ?>/" method="get">
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
                                        <option>全部商户</option>
                                        <?php foreach ($kitchens as $v): ?>
                                            <option value="<?=$v->id ?>" <?php if (isset($_GET['kitchen']) && $_GET['kitchen'] == $v->id):?>selected<?php endif ?>><?=$v->name ?></option>
                                        <?php endforeach ?>
                                    </select></td>
                                <td><select style="width: 120px" name="status">
                                        <?php $statuses = array('0' => '未结算', '1' => '已结算') ?>
                                        <option value="-1">全部状态</option>
                                        <?php foreach ($statuses as $k => $v): ?>
                                            <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                        <?php endforeach ?>
                                    </select></td>
                                <td width="100">
                                    <input type="text" name="date_start" data-date-format="yyyy-mm-dd" value="<?php echo isset( $_GET['date_start']) ? $_GET['date_start'] : date('Y-m-d') ?>" class="datepicker span11">
                                </td>
                                <td><label class="control-label">至</label></td>
                                <td width="100">
                                    <input type="text" name="date_end" data-date-format="yyyy-mm-dd" value="<?php echo isset( $_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d') ?>" class="datepicker span11">
                                </td>
                                <td><input type="submit" class="btn btn-primary"/></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>清单列表</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>清单日期</th>
                                <th>商户</th>
                                <th>总金额(元)</th>
                                <th>生成时间</th>
                                <th>更新时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($list['list'] as $item): ?>
                            <?php $total += $item->amount ?>
                            <tr class="gradeA">
                                <td><?=$item->id ?></td>
                                <td><?=$item->date ?></td>
                                <td><?=isset($merchants[$item->merchant_id]) ? $merchants[$item->merchant_id]->merchant_name : '' ?></td>
                                <td><?=$item->amount ?></td>
                                <td style="text-align: center"><?=date('Y-m-d H:i:s', $item->time_created) ?></td>
                                <td style="text-align: center"><?=date('Y-m-d H:i:s', $item->time_created) ?></td>
                                <td style="text-align: center"><?=$item->status ? '已结算' : '未结算' ?></td>
                                <td align="center" style="text-align: center">
                                    <?php if ($item->status == 0): ?>
                                        <a class="btn btn-primary btn-mini" href="/index.php?/admini/merchant/cashup/<?=$item->id ?>">结算</a>
                                    <?php endif ?>
                                    <a class="btn btn-link btn-mini" href="/index.php?/admini/merchant/cashup/<?=$item->id ?>">详情</a>
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
