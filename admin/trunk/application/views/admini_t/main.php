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
    <div id="breadcrumb"> <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <li class="bg_lb "> <a href="<?php echo site_url('/admini/diner/location');?>"> <i class="icon-dashboard"></i> <!--<span class="label label-important">20</span>-->餐车定位</a> </li>
        <li class="bg_lg "> <a href="<?php echo site_url('/admini/dashboard/userinfo_list');?>"> <i class="icon-th"></i> 客户列表</a> </li>
        <li class="bg_ly"> <a href="<?php echo site_url('/admini/dish/index');?>"> <i class="icon-th"></i>官方菜品 </a> </li>
        <li class="bg_lo"> <a href="<?php echo site_url('/admini/dish_material/index');?>"> <i class="icon-th-list"></i>原料管理 </a> </li>
        <li class="bg_ls"> <a href="<?php echo site_url('/admini/diner/dinerlist');?>"> <i class="icon-fullscreen"></i> 车辆管理</a> </li>
        <li class="bg_lo"> <a href="<?php echo site_url('/admini/dashboard/merchantlist');?>"> <i class="icon-th-list"></i>商户管理</a> </li>
         <!--<li class="bg_ls"> <a href="buttons.html"> <i class="icon-tint"></i> Buttons</a> </li>
       <li class="bg_lb"> <a href="interface.html"> <i class="icon-pencil"></i>Elements</a> </li>
        <li class="bg_lg"> <a href="calendar.html"> <i class="icon-calendar"></i> Calendar</a> </li>
        <li class="bg_lr"> <a href="error404.html"> <i class="icon-info-sign"></i> Error</a> </li>-->

      </ul>
    </div>
<!--End-Action boxes-->

<!--Chart-box-->
    <div class="row-fluid">
      <div class="widget-box">
        <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
          <h5>Site Analytics</h5>
        </div>
        <div class="widget-content" >
          <div class="row-fluid">
            <div class="span9">
              <div class="chart"></div>
            </div>
            <div class="span3">
              <ul class="site-stats">
                <li class="bg_lh"><i class="icon-user"></i> <strong>2540</strong> <small>全部用户</small></li>
                <li class="bg_lh"><i class="icon-plus"></i> <strong>120</strong> <small>新用户 </small></li>
                <li class="bg_lh"><i class="icon-shopping-cart"></i> <strong>656</strong> <small>总的销售额</small></li>
                <li class="bg_lh"><i class="icon-tag"></i> <strong>9540</strong> <small>总的订单数量</small></li>
                <li class="bg_lh"><i class="icon-repeat"></i> <strong>10</strong> <small>挂单数量</small></li>
                <li class="bg_lh"><i class="icon-globe"></i> <strong>8540</strong> <small>在线订单数量</small></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
<!--End-Chart-box-->
    <hr/>

  </div>
</div>

<!--end-main-container-part-->

<div class="row-fluid">
  <div id="footer" class="span12"> 2014 &copy; 吃在身边 版权所有 © 青岛德高软件开发有限公司 2005-2011 鲁ICP备08015072号 </div>
</div>

<!--<script src="<?php echo base_url()?>asset/js/excanvas.min.js"></script>-->

<!--ok-->
<script src="<?php echo base_url()?>asset/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.ui.custom.js"></script>
<script src="<?php echo base_url()?>asset/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.flot.min.js"></script>
<!--/ok-->

<!--<script src="<?php echo base_url()?>asset/js/jquery.flot.resize.min.js"></script>-->

<!--ok-->
<script src="<?php echo base_url()?>asset/js/jquery.peity.min.js"></script>
<script src="<?php echo base_url()?>asset/js/fullcalendar.min.js"></script>
<!--/ok-->

<script src="<?php echo base_url()?>asset/js/matrix.js"></script><!--左侧菜单手风琴-->

<!--ok-->
<script src="<?php echo base_url()?>asset/js/matrix.dashboard.js"></script>
<script src="<?php echo base_url()?>asset/js/jquery.gritter.min.js"></script>
<script src="<?php echo base_url()?>asset/js/matrix.interface.js"></script>
<!--/ok-->

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {

          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();
          }
          // else, send page to designated URL
          else {
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>
