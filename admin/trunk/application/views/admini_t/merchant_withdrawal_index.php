<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '1' => '审核中',
    '2' => '通过',
    '3' => '未通过'
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">用户提现列表</a>
        </div>
        <h1>用户提现列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box filter">
                    <form method="get" action="<?php echo site_url('/admini/merchant/withdrawal');?>" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td>
                                    <select style="width: 120px" name="status">
                                        <option value="0">全部状态</option>
                                        <option value="1">受理中</option>
                                        <option value="2">提现成功</option>
                                    </select>
                                </td>
                                <td>
                                    <select style="width: 120px" name="userType">
                                        <option value="0">所有用户</option>
                                        <option value="manager">经营者</option>
                                        <option value="merchant">商户</option>
                                    </select>
                                </td>
                                <td width="100" >
                                    <input type="text" name="mobile" value="<?=isset($_GET['kw']) ? $_GET['kw'] : '' ?>" placeholder="手机号...">
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-primary" value="查询"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>提现列表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>用户类型</th>
                                <th>开户行</th>
                                <th>银行账号</th>
                                <th>开户名</th>
                                <th>提现金额</th>
                                <th>手机号</th>
                                <th>提现时间</th>
                                <th>受理时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($data): ?>
                            <?php foreach($data as $item): ?>
                                <tr>
                                    <td><?=$item->uid;?></td>
                                    <td><?=$item->userType;?></td>
                                    <td><?=$item->bank;?></td>
                                    <td><?=$item->bank_account;?></td>
                                    <td><?=$item->bank_account_name;?></td>
                                    <td><?=$item->money;?></td>
                                    <td><?=$item->mobile;?></td>
                                    <td><?=$item->submit_time?></td>
                                    <td><?=$item->accepted_time;?></td>
                                    <td><?=$item->status;?></td>
                                    <td>
                                    <?php if($item->status === '受理中'):?>
                                        <button class="btn btn-mini btn-info pass"  data-id="<?=$item->id;?>">确认</button>
                                    <?php else:?>
                                        <lable class="label"><?=$item->status;?></lable>
                                    <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif ?>
                            </tbody>
          
                        </table>
                    </div>
                </div>
                <div class="pager"><?=$page ?></div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer;?>
<script type="text/javascript">
$(function () {
    $('select[name=status]').val("<?=$_GET['status'];?>");
    $('select[name=userType]').val("<?=$_GET['userType'];?>");

    $('.pass').on('click', function () {
        id = $(this).attr('data-id');

        withdrawal({id: id, status: 2});
    });

    function withdrawal(option) {
        $.ajax({
            url: "<?php echo site_url('/fundapi/withdrawalHandler');?>",
            type: 'post',
            data: option,
            dataType: 'json',
            success: function (data) {
                alert(data.message);

                if (data.code == 'OK') {
                    window.location.reload();
                };
            }
        });
    }
});
</script>
</body>
</html>


