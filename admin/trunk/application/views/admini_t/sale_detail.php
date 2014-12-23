<?php echo $admini_head;?>
<!--close-Header-part-->
<!--top-Header-menu-->
<?php echo $admini_nav;?>
<!--close-top-serch-->

<!--sidebar-menu-->
<?php echo $admin_sidebar;?>

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">销售数据明细</a>
    </div>
  </div>

  <!--Action boxes-->
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        
                        <div class="widget-content">
                            <div><h5>线下销售：<?=$offline_sum?></h5></div>
                            <table class="table table-bordered">
                                <thead>
	                                <tr>
	                                	<th>餐品名称</th>
	                                	<th>单价</th>
	                                	<th>数量</th>
	                                	<th>小计</th>
	                                </tr>
                                </thead>
                                <tbody>
                                    <?php if (is_array($offline_list)) {
                                      foreach ($offline_list as $value) {
                                    ?>   
                                      <tr>
                                        <td><?=$value->food_name ?></td>
                                        <td><?=$value->sale_price ?></td>
                                        <td><?=$value->count?></td>
                                        <td><?=$value->sale_price * $value->count?></td>
                                      </tr>
                                    <?php }}?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="widget-box">
                            <form method="get" class="form-inline widget-content">
                                
                                <select style="width: 150px" name="order_type">
                                    <option value="-1" <?php if ((!isset($_GET['order_type']))||($_GET['order_type']==-1)) echo 'selected';?>>订单类别</option>
                                    <option value="1" <?php if ($_GET['order_type']==1) echo 'selected';?>>普通订单</option>
                                    <option value="2" <?php if ($_GET['order_type']==2) echo 'selected';?>>预定订单</option>
                                </select>
                                <select style="width: 150px" name="delivery_methods">
                                    <option value="-1" <?php if ((!isset($_GET['delivery_methods']))||($_GET['delivery_methods']==-1)) echo 'selected';?>>送餐方式</option>
                                    <option value="1" <?php if ($_GET['delivery_methods']==1) echo 'selected';?>>配送</option>
                                    <option value="2" <?php if ($_GET['delivery_methods']==2) echo 'selected';?>>自提</option>
                                </select> 
                                <input type="hidden" name="diner_id" value="<?=$diner_id?>">
                                <input type="hidden" name="date" value="<?=$date?>">
                                <input type="submit" class="btn btn-info" value="查询" style="margin-left: 10px;">
                            </form>
                        </div>

                        <div class="widget-content">
                            <div><h5>线上销售：<?=$online_sum?></h5></div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>订单号</th>
                                        <th>客户信息</th>
                                        <th>订单类别</th>
                                        <th>送餐方式</th>
                                        <th>订单金额</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (is_array($online_list)) {
                                      foreach ($online_list as $value) {
                                    ?>   
                                      <tr>
                                        <td><?=$value->id ?></td>
                                        <td><?=$value->order_person_tel ?></td>
                                        <td><?php if($value->order_type == 1){
                                                        echo "普通订单";
                                                     }else{echo "预定订单";}?></td>
                                        <td><?php if($value->delivery_methods == 1){
                                                        echo "配送";
                                                     }else{echo "自提";}?></td>
                                        <td><?=$value->order_amount ?></td>
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

    
<?php echo $footer;?>
</body>
</html>