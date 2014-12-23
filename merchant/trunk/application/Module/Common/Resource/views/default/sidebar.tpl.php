<!--sidebar-menu-->
<div id="sidebar">
    <input type="hidden" name="active" value="<?=$active;?>">
    <ul>
        <li class="<?php if ($path == ''): ?>active<?php endif ?>" ><a href="/" ><i class="icon icon-home"></i> 控制面板</a></li>
        <li class="submenu"> <a href="#"><i class="icon icon-money"></i> <span>商家服务</span></a>
            <ul>
<!--                 <li><a href="/merchant/restaurant"><i class="icon icon-caret-right"></i> 餐厅管理</a></li> -->
                <li><a href="/merchant/dishRelation"><i class="icon icon-caret-right"></i> 菜品管理</a></li>
<!--                 <li><a href="/merchant/dishCategory"><i class="icon icon-caret-right"></i> 菜品分类</a></li> -->
                <?php if($_USER->type == 'merchant'){?>
                <li><a href="/merchant/diningcar"><i class="icon icon-caret-right"></i> 餐车管理</a></li>
                <?php }?>
                <li><a href="/merchant/diningcar/location"><i class="icon icon-caret-right"></i> <span>餐车定位</span></a></li>
<!--                <li><a href="/merchant/promotion"><i class="icon icon-caret-right"></i> 促销管理</a></li>-->
                <li><a href="/merchant/staff"><i class="icon icon-caret-right"></i> 店员管理</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-user"></i> <span>客户服务</span></a>
            <ul>
                <li><a href="/customer/order"><i class="icon icon-caret-right"></i> <span>销售管理</span></a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-tag"></i> <span>菜品订购</span></a>
            <ul>
            <?php if ($_USER->type === 'manager'):?>
                <li><a href="/merchant/supplier/order"><i class="icon icon-caret-right"></i> 在线订购</a></li>
            <?php endif;?>
                <li><a href="/merchant/supplier/orderList"><i class="icon icon-caret-right"></i> 采购订单</a></li>
                <li><a href="/merchant/supplier/packing"><i class="icon icon-caret-right"></i> 分装清单</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-money"></i><span>资金管理</span></a>
            <ul>
            <?php if($_USER->type === 'merchant'):?>
                <li><a href="/fund/merchant/index"><i class="icon icon-caret-right"></i>资金管理</a></li>
            <?php elseif ($_USER->type === 'manager'):?>
                <li><a href="/fund/manager/index"><i class="icon icon-caret-right"></i>资金管理</a></li>
            <?php endif;?>
                <li><a href="/fund/report"><i class="icon icon-caret-right"></i>线下销售提报</a></li>
            <?php if($_USER->type == 'merchant'){?>
                <li><a href="/fund/purchase/index"><i class="icon icon-caret-right"></i>商户采购结算</a></li>
            <?php }?>
            <?php if($_USER->type == 'merchant' || $_USER->role == 2):?>
                <li><a href="/fund/salary/index"><i class="icon icon-caret-right"></i>工资结算</a></li>
                <li><a href="/fund/profit"><i class="icon icon-caret-right"></i>利润分配明细</a></li>
            <?php endif;?>
            </ul>
        </li>
    </ul>
</div>
<!--sidebar-menu-->