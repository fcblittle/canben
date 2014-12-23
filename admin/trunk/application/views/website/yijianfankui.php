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
    <div class="main">
		<div class="mainBox float_l">
         <div class="mainHead">
         	<h2 class="shadowText float_l mar_r10">意见反馈</h2>
         	<span class="English float_l shadowText">/ Feedback

</span>
         	<span class="more float_r shadowText"><a href="<?php echo base_url()?>">首页 ></a><a href="#">意见反馈</a></span>
         </div>
         <div class="mainCnt float_l" style="height:700px;">
         <div class="area float_l yinsi">
           <div class="yijian">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您宝贵的意见是对我们最大的支持； 您细心的关注是对我们最大的肯定； 与您长期合作是我们自始至终的目标； 
			我们会用心来解决您提出的每个宝贵意见。<br/>
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;为保证您的意见能够直接反馈到相关的业务部门，保持沟通渠道的畅通和有效，请您直接拨打下方的客服联系电话，
			由我们的客服人员直接与您沟通，了解第一手资料，避免沟通出现信息错位。对于您的意见我们进行细分，归类，您的
			意见可能无法马上得到解决，但我们保证在您每个版本中都能够发现它的改变。<br />
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;再次感谢您的无私的支持和热心帮助！<br />
			 </div>
           <img src="<?php echo base_url()?>asset/website/images/tell.jpg" />
         </div>
         </div>
		</div>
    </div>   
  </div>   
   
    <?php echo $foot;?>
</div> 
</body>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
  $('.question li').click(function (e) {
    if($(this).find(".cont").is(":visible")==false){
      $(this).find(".cont").slideDown();
    }else{
      $(this).find(".cont").slideUp();
    }
    
  })
</script>
</html>
