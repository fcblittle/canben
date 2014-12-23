<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '1' => '充值中',
    '2' => '充值成功',
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">用户充值列表</a>
        </div>
        <h1>用户充值列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box filter">
                    <form method="get" action="<?php echo site_url('/admini/recharge/index');?>" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td><select style="width: 120px" name="status">
                                        <?php foreach ($statuses as $k => $v): ?>
                                        <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                    </select></td>
                               <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>用户名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>手机号</option>
                              <option value="3" <?php if (isset($_GET['type']) && $_GET['type'] == 3): ?>selected<?php endif ?>>流水号</option>
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                                
                                <td>
                                    <input type="submit" class="btn btn-primary" value="查询"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>数据表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered ">
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>手机号</th>
                                <th>流水号</th>
                                <th>充值金额(元)</th>
                                <th>充值前金额(元)</th>
                                <th>充值后金额(元)</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($data_list): ?>
                            <?php foreach($data_list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo isset($v->user_id) ? $v->nickname : '[用户不存在]'; ?></td>
                                    <td><?php echo $v->mobile; ?></td>
                                    <td><?php echo $v->order_no; ?></td>
                                    <td><?php echo $v->recharge_money; ?></td>
                                    <td><?php echo $v->pre_money; ?></td>
                                    <td><?php echo $v->current_money; ?></td>
                                    <td>
                                        <?=$statuses[$v->status] ?>
                                    </td>
                                   
                                </tr>
                            <?php endforeach; ?>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pager"><?=$pager ?></div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer;?>
</body>
</html>
