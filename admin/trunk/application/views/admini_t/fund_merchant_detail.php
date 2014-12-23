<?php echo $admini_head;?>
<!--close-Header-part-->
<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> 
              <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
              <a href="<?php echo site_url('/admini/fund/merchant');?>">商户资金管理</a>
              <a href="#" class="current">商户(<?=$merchant_name;?>)往来明细</a>
            </div>
            <h1>商户(<?=$merchant_name;?>)账户往来明细</h1>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title">
                            <ul class="nav nav-tabs">
                                <li <?php if(empty($_GET['type']) || $_GET['type'] == 'wallet') echo 'class="active"';?>><a href="<?php echo site_url('/admini/fund/merchant_detail/'.$merchant_id);?>?type=wallet">我的钱包</a></li>
                                <li <?php if($_GET['type'] == 'account') echo 'class="active"';?>><a href="<?php echo site_url('/admini/fund/merchant_detail/'.$merchant_id);?>?type=account">经营账户</a></li>
                            </ul>
                        </div>
                        <div class="widget-content">
                            <div class="widget-box">
                                <div class="widget-content">
                                    <div class="controls controls-row">
                                        <span class="span3">账户所属：<font style="font-weight: bold;"><?php if(empty($_GET['type']) || $_GET['type'] == 'wallet') echo '我的钱包'; else echo '经营账户';?></font></span>
                                        <span class="span3">账户余额：
                                            <font style="font-weight: bold;"><?php if(empty($_GET['type']) || $_GET['type'] == 'wallet') echo $wallet; else echo $account;?></font>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box">
                                <form method="get" class="form-inline widget-content">
                                    <div class="controls controls-row">

                                        <select name="variationtype" id="variationtype" style="width:200px;">
                                          <option value="-1" <?php if (empty($_GET['variationtype']) || $_GET['variationtype'] == -1) echo 'selected';?>>全部摘要</option>
                                        <?php foreach ($variation_type as $key => $value) {?>
                                          <option value="<?=$key;?>" <?php if ($_GET['variationtype'] == $key) echo 'selected';?>><?=$value;?></option>
                                        <?php
                                        }?>
                                          


                                        </select>
                                        <input type="text" data-date-format="yyyy-mm-dd" class="datepicker" id="datepicker" name="date" value="<?=$_GET['date'];?>" style="margin-left: 10px;">
                                         <input id="search-date" type="button" class="btn btn-info" value="查询" style="margin-left: 10px;">
                                     </div>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                  <tr>
                                        <th>时间</th>
                                        <th>摘要</th>
                                        <th>账户所属</th>
                                        <th>收/支</th>
                                        <th>余额</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php if (is_array($data_list)) {
                                    foreach ($data_list as $value) {
                                ?>
                                  <tr>
                                   <td><?=date("Y-m-d H:i", $value->created);?></td>
                                   <td><?=$variation_type[$value->variationTypeId];?></td>
                                   <td><?php if(empty($_GET['type']) || $_GET['type'] == 'wallet') echo '我的钱包'; else echo '经营账户';?></td>
                                   <td><?=$value->amount;?></td>
                                   <td><?=$value->balance;?></td>
                                  </tr>
                                <?php }}?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pager"><?=$pager ?></div>
                </div>
            </div>
            
        </div>
    </div>

<!--end-main-container-part-->
<?php echo $footer;?>

<script type="text/javascript">
// $('#datepicker').datepicker({});

$(function() {
    $('#search-date').click(function () {
        path = window.location.href.split('?');

        if (typeof path[1] !== 'undefined' && path[1] != '') {
            queries = path[1].split('&');

            queryParam = [];
            isDate = false;
            isType = false;
            console.log(queries);
            $.each(queries, function(index, v) {
                if(v.indexOf('date=') > -1) {
                    queryParam.push('date=' + $('[name=date]').val());
                    isDate = true;
                } else if (v.indexOf('variationtype=') > -1) {
                    queryParam.push('variationtype=' + $('[name=variationtype]').val());
                    isType = true;
                } else {
                    queryParam.push(v);
                }
            });
            if (! isDate) {
                queryParam.push('date=' + $(this).siblings('[name=date]').val());
            }
            if (! isType) {
                queryParam.push('variationtype=' + $(this).siblings('[name=variationtype]').val());
            };

            queryStr = queryParam.join("&");
        } else {
            queryStr = 'date=' + $(this).siblings('[name=date]').val() + '&variationtype=' + $('[name=variationtype]').val()
        }
        window.location.href = '?' + queryStr;
    });

});

</script>
</body>
</html>
