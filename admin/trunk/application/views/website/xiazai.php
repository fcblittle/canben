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
        
  <div class="area float_l" style="background:url(<?php echo base_url()?>asset/website/images/bg.jpg) repeat;">
    <div class="main" style="height:1060px;">
		<div class="mainBox float_l">
         <div class="mainHead">
         	<h2 class="shadowText float_l mar_r10">下载专区</h2>           
         	<span class="English float_l shadowText">/ Download</span>
         </div>
         <div class="mainCnt">
          
          <div class="wx_top">
          <div class="wxt_top">
          <h2>更快捷、更丰富、更健康</h2>
          <span>
          随时下单随时取餐，餐品月月更新美味不断，引导健康营养饮食！
          </span>
          <p>这不仅仅是一款app，这是懒人的节奏！</p>
          </div>
          <div class="wxt_bot">
          <a href="https://itunes.apple.com/us/app/gao-can-ben-can-che-mei-shi/id877431241?mt=8" style="padding-right:13px"><img src="<?php echo base_url()?>asset/website/images/a_w.jpg" /></a>
           <a href="<?php echo base_url()?>api/downapk"><img src="<?php echo base_url()?>asset/website/images/a_e.jpg" /></a>
          </div>      
          <em></em>
          </div>
          <div class="wx_bot">
           <h2>扫描二维码下载安装，您就可以轻松点餐了！</h2>
           <em>
            <ul>
             <li>
             <img src="<?php echo base_url()?>asset/website/images/ma.jpg" width="180" height="180" />
             <span style=" color:#72b611; text-align:center">餐本app</span>
             </li>
             <li>
             <img src="<?php echo base_url()?>asset/website/images/zhushouma.jpg" width="180" height="180" />
             <span style=" color:#4183d4;text-align:center">餐本助手（商户端）</span>
             </li>
            </ul>
            <div class="clb"></div>
           </em>
            <h2 style="background:#f7f7f7 url(<?php echo base_url()?>asset/website/images/jianpan.jpg) no-repeat 14px center;">
              <ul>
                <li class="active" ><a href="#ios" data-toggle="tab">iOS版更新记录</a></li>
                <li>
                  <a href="#and" data-toggle="tab" >Android版更新记录</a>
                </li>
              </ul>
              
              
            </h2>
            <div class="w_ttt">
            <div class="tab-content">
              <div class="tab-pane active" id="ios">
                  发布IOS（BETA）版<br />
                  <span style="font-size:17px; color:#393939">2014年6月</span>
                  <div class="yyl">
                    发布IOS（BETA）版<br />
                    <span style="font-size:17px; color:#393939">2014年6月</span>
                  </div>
                </div>
                <div class="tab-pane" id="and">
                  发布Android（BETA）版<br />
                  <span style="font-size:17px; color:#393939">2014年6月</span>
                  <div class="yyl">
                    发布Android（BETA）版<br />
                    <span style="font-size:17px; color:#393939">2014年6月</span>
                  </div>
                </div>
            </div>
                
            </div>
          </div>         
		</div>
    </div>   
  </div>     
        <?php echo $foot;?>
</div> 
</body>
<script src="<?php echo base_url()?>asset/website/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo base_url()?>asset/website/js/bootstrap.min.js"></script>
</html>
