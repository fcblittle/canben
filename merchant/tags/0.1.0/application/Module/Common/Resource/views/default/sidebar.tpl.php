<!--sidebar-menu-->
<div id="sidebar">
    <ul>
        <li class="<?php if ($path == ''): ?>active<?php endif ?>" ><a href="/" ><i class="icon icon-home"></i> 控制面板</a></li>
        <li class="submenu open"> <a href="#"><i class="icon icon-money"></i> <span>商家服务</span></a>
            <ul>
                <li><a href="/merchant/restaurant"><i class="icon icon-caret-right"></i> 餐厅管理</a></li>
                <li><a href="/merchant/dishRelation"><i class="icon icon-caret-right"></i> 菜品管理</a></li>
                <li><a href="/merchant/dishCategory"><i class="icon icon-caret-right"></i> 菜品分类</a></li>
                <li><a href="/merchant/diningcar"><i class="icon icon-caret-right"></i> 餐车管理</a></li>
                <li><a href="/merchant/diningcar/location"><i class="icon icon-caret-right"></i> <span>餐车定位</span></a></li>
<!--                <li><a href="/merchant/promotion"><i class="icon icon-caret-right"></i> 促销管理</a></li>-->
                <li><a href="/merchant/staff"><i class="icon icon-caret-right"></i> 店员管理</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-user"></i> <span>客户服务</span></a>
            <ul>
                <li><a href="/customer/order"><i class="icon icon-caret-right"></i> <span>订单管理</span></a></li>
                <li><a href="/customer/refund"><i class="icon icon-caret-right"></i> <span>退款管理</span></a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-tag"></i> <span>菜品订购</span></a>
            <ul>
                <li><a href="/merchant/supplier/order"><i class="icon icon-caret-right"></i> 在线订购</a></li>
                <li><a href="/merchant/supplier/orderList"><i class="icon icon-caret-right"></i> 采购订单</a></li>
                <li><a href="/merchant/supplier/packing"><i class="icon icon-caret-right"></i> 分装清单</a></li>
            </ul>
        </li>
    </ul>
</div>
<!--sidebar-menu-->