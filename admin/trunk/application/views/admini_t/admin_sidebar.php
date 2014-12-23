<div id="sidebar"> 
<input type="hidden" id="act_css" value="<?php echo $active;?>"  />
  <ul>
    <li tag="1"><a href="<?php echo site_url('/admini/dashboard');?>"><i class="icon icon-home"></i> <span>控制面板</span></a> </li>
<!--      <li tag="12" class="submenu"><a href="--><?php //echo site_url('/admini/dashboard');?><!--"><i class="icon icon-home"></i> <span>系统设置</span></a>-->
<!--          <ul>-->
<!--              <li tag="120"><a href="--><?php //echo site_url('/admini/admin/index');?><!--"><i class="icon icon-caret-right"></i> 管理员</a></li>-->
<!--              <li tag="121"><a href="--><?php //echo site_url('/admini/role/index');?><!--"><i class="icon icon-caret-right"></i> 角色</a></li>-->
<!--          </ul>-->
<!--      </li>-->
      <li tag="11" class="submenu"><a href="<?php echo site_url('/admini/order/index');?>"><i class="icon icon-th-list"></i> 官方服务</a>
          <ul>
              <li tag="140"><a href="<?php echo site_url('/admini/cityfood/index');?>"><i class="icon icon-caret-right"></i> 城市菜品管理</a></li>
              <li tag="141"><a href="<?php echo site_url('/admini/citymaterial/index');?>"><i class="icon icon-caret-right"></i> 城市原料管理</a></li>
              <li tag="142"><a href="<?php echo site_url('/admini/kitchen/index');?>"><i class="icon icon-home"></i> <span>城市厨房管理</span></a> </li>
              <!--<li tag="100"><a href="<?php echo site_url('/admini/dish/index');?>"><i class="icon icon-caret-right"></i> 菜品管理</a></li>-->
              <li tag="101"><a href="<?php echo site_url('/admini/dashboard/foodclassllist');?>"><i class="icon icon-caret-right" ></i> 菜品分类</a></li>
              <li tag="102"><a href="<?php echo site_url('/admini/dishTag/index');?>"><i class="icon icon-caret-right"></i> 标签管理</a></li>
              <!--<li tag="103"><a href="<?php echo site_url('/admini/dish_material/index');?>"><i class="icon icon-caret-right"></i> 原料管理</a></li>-->
              <li tag="104"><a href="<?php echo site_url('/admini/dish_material_category/index');?>"><i class="icon icon-caret-right"></i> 原料分类</a></li>
              <!--<li tag="105"><a href="<?php echo site_url('/admini/dish_mealtime/index');?>"><i class="icon icon-caret-right"></i> 用餐时段</a></li>
              <li tag="12"><a href="<?php echo site_url('/admini/kitchen/index');?>"><i class="icon icon-home"></i> <span>厨房管理</span></a> </li>-->
              <li tag="111"><a href="<?php echo site_url('/admini/order/kitchen');?>"><i class="icon icon-caret-right"></i> 加工订单</a></li>
              <li tag="112"><a href="<?php echo site_url('/admini/order/packing');?>"><i class="icon icon-caret-right"></i> 分装清单</a></li>
              <li tag="113"><a href="<?php echo site_url('/admini/order/merchant');?>"><i class="icon icon-caret-right"></i> 商户清单</a></li>
              <li tag="115"> <a href="<?php echo site_url('/admini/dashboard/ad_management');?>"><i class="icon icon-bullhorn"></i> <span>广告管理</span></a> </li>
              <li tag="117"><a href="<?php echo site_url('/admini/dashboard/get_city');?>"><i class="icon icon-caret-right"></i>城市管理</a></li>
              <li tag="116"><a href="<?php echo site_url('/admini/dashboard/get_area');?>"><i class="icon icon-caret-right"></i> 区域管理</a></li>
          </ul>
      </li>
    <li tag="3" class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>商家服务</span> <span class="label label-important">6</span></a>
      <ul>
        <li tag="30"><a href="<?php echo site_url('/admini/dashboard/merchantlist');?>"><i class="icon icon-caret-right"></i> 商户列表</a></li>
        <!-- <li tag="31"><a href="<?php echo site_url('/admini/dashboard/storelist');?>"><i class="icon icon-caret-right"></i> 餐厅列表</a></li> -->
          <li tag="36"><a href="<?php echo site_url('/admini/merchant/settlementlist');?>"><i class="icon icon-caret-right"></i> 商户结算</a></li>
          <li tag="38"><a href="<?php echo site_url('/admini/merchant/withdrawal');?>"><i class="icon icon-caret-right"></i> 商户/经营者 提现</a></li>
        <!-- <li tag="34"><a href="<?php echo site_url('/admini/dashboard/categorylist');?>"><i class="icon icon-caret-right"></i> 餐厅菜系</a></li> -->
        <!-- <li tag="35"><a href="<?php echo site_url('/admini/dashboard/storelabellist');?>"><i class="icon icon-caret-right"></i> 餐厅特点</a></li> -->
        <li tag="32"><a href="<?php echo site_url('/admini/diner/dinerlist');?>"><i class="icon icon-caret-right"></i> 餐车列表</a></li>
          <li tag="33"><a href="<?php echo site_url('/admini/diner/location');?>"><i class="icon icon-caret-right"></i> 车辆定位</a></li>

<!--        <li tag="35"><a href="--><?php //echo site_url('/admini/dashboard/foodclass');?><!--"><i class="icon icon-caret-right"></i> 商户分类</a></li>-->
      </ul>
    </li>
    <li tag="13" class="submenu"><a href="javascript:;"><i class="icon icon-home"></i> <span>客户服务</span></a>
        <ul>
            <li tag="130"><a href="<?php echo site_url('/admini/withdraw/index');?>"><i class="icon icon-caret-right"></i> 用户提现</a></li>
            <li tag="131"><a href="<?php echo site_url('/admini/recharge/index');?>"><i class="icon icon-caret-right"></i> 用户充值</a></li>
            <li tag="132"><a href="<?php echo site_url('/admini/refund/index');?>"><i class="icon icon-caret-right"></i> 用户退款</a></li>
            <li tag="133"><a href="<?php echo site_url('/admini/dashboard/userinfo_list');?>"><i class="icon icon-caret-right"></i> 客户列表</a></li>
          <li tag="42"><a href="<?php echo site_url('/admini/feedback/index');?>"><i class="icon icon-caret-right"></i> 用户反馈</a></li>
<!--              <li tag="43"><a href="form-wizard.html"><i class="icon icon-caret-right"></i> 用户行为分析</a></li>-->
        </ul>
    </li>

    <li tag="24" class="submenu"><a href="javascript:;"><i class="icon icon-money"></i> <span>资金管理</span></a>
        <ul>
            <li tag="240"><a href="<?php echo site_url('/admini/fund/merchant');?>"><i class="icon icon-caret-right"></i> 商户资金管理</a></li>
            <li tag="241"><a href="<?php echo site_url('/admini/fund/manager');?>"><i class="icon icon-caret-right"></i> 经营者资金管理</a></li>
            <li tag="242"><a href="<?=site_url('/admini/salary/index');?>"><i class="icon icon-caret-right"></i> 经营者工资结算</a></li>
            <li tag="243"><a href="<?=site_url('/admini/sale/index');?>"><i class="icon icon-caret-right"></i> 销售数据查看</a></li>
            <li tag="244"><a href="<?=site_url('/admini/deduction/index');?>"><i class="icon icon-caret-right"></i> 经营扣项提报</a></li>
            <li tag="245"><a href="<?=site_url('/admini/shipping/index');?>"><i class="icon icon-caret-right"></i> 物流费用提报</a></li>
        </ul>
    </li>

    <li tag="44" class="submenu"><a href="javascript:;"><i class="icon icon-th-list"></i> <span>APP 包管理</span></a>
        <ul>
            <li tag="440"><a href="<?php echo site_url('/admini/app/index');?>"><i class="icon icon-caret-right"></i> APP 包管理</a></li>
        </ul>
    </li>
  </ul>
</div>