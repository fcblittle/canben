<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="德高软件" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link href="<?php echo base_url()?>asset/website/css/zixuncss.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="<?php echo base_url()?>asset/website/images/logoIcon.ico" />
<title>餐本官网</title>
</head>
<body>
<div class="Container">
<?php echo $head;?>         
    <div class="bannerW area float_l">
		<div class="bannerTop"></div>
	 </div>
     
     <div class="area float_l" style="background:url(<?php echo base_url()?>asset/website/images/bg.jpg) repeat;">
    <div class="main">
      <div class="main_left area float_l">
       <div class="ml_top">
          <div class="mlt_top"><span>关于我们</span></div> 
          <div class="left_headbg"></div> 
            <ul class="mlt_bot">
              <!--<li><a href="/website/about">技术团队</a></li>-->
              <li><a href="/website/culture">团队文化</a></li>
              <li style="border-bottom:none"><a href="/website/contact"  style=" color:#ff6666">联系我们</a></li>
            </ul>
       </div> 
        <div class="ml_bot">
		<a href="/website/xiazai"><img src="<?php echo base_url()?>asset/website/images/xiazai_btn.png" width="95" height="40" alt="下载专区" /></a> 
		<a href="/website/zhinan"><img src="<?php echo base_url()?>asset/website/images/zhinan_btn.png" width="114" height="40" alt="餐本指南" /></a></div>        
      </div> 
      
      <div class="main_right area float_r">
        <div class="mr_top">
          <h2 class="mrt_left float_l">联系我们</h2>
          <div class="mrt_right float_r"><a href="<?php echo base_url()?>">首页</a>><a href="/website/contact">联系我们</a></div>
        </div>
        <div class="cont_bot"><img src="<?php echo base_url()?>asset/website/images/map.png" width="680" height="350" alt="地理位置"/><p>地址：山东省青岛市市北区连云港路33号万达商务楼B座22楼2227室
<br />邮编：266000<br />  传真：0532-55662923<br />
          电话：0532-55662932     0532-55662923</p></div>
      </div>
    </div>
  </div>
   
    <?php echo $foot;?>
</div> 
</body>
</html>
