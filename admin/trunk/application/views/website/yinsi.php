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
         	<h2 class="shadowText float_l mar_r10">法律隐私</h2>
         	<span class="English float_l shadowText">/ lAW & PRIVACY

</span>
         	<span class="more float_r shadowText"><a href="<?php echo base_url()?>">首页 ></a><a href="#">法律隐私</a></span>
         </div>
         <div class="mainCnt float_l" style="height:auto;">
         <div class="area float_l yinsi">
           <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;欢迎您浏览和使用餐本官网（以下称"本网站"），请在使用本网站时，遵从下列的条款；现请仔细阅读和理解本页内容。<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如果您从本网站下载您所需的材料作非商业用途（含有版权和其他专有权的所属信息除外），您已被视为接受和同意遵从这些条款。本网站出现的商标、服务标志、设计及本网站中述及的任何其他知识产权，均属青岛联合食通餐饮管理有限公司或其关联公司所有或已取得所有人的正式授权，在未取得青岛联合食通餐饮管理有限公司或有关第三方的正式书面授权之前，任何人不得擅自使用，包括但不限于复制、复印、修改、出版、公布、传送、分发本网站上所载的文本、图象、影音、镜像等内容，违者将被依法追究民事乃至刑事责任。<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;虽然本网站将努力提供准确和及时的信息和内容，但这些信息和内容仅限于其现有状况，对其准确性和及时性，本网站不给予任何明示或默示的保证。本网站不承担因您进入或使用本网站而导致的任何直接的、间接的、意外的、因果性的损害责任。请小心使用您的软件和设备。<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;此外，本网站对为您提供便利而设置的外部衔接不负任何责任。您可以自愿选择是否在本网站进行注册或登记成为会员。如果您在本网站注册，您将向本网站提供一些您的个人资料，对于您的个人资料和隐私权本网站将予以尊重和保密。您传送至本网站的任何其他通讯或材料，包括但不限于意见、客户反馈、喜好、建议、支持、请求、问题等内容，将被当作非保密资料和非专有资料处理；而当您将这些资料传送至本网站并被接收时，即被视为您同意这些资料用作本网站的调查、统计或作内部整体无偿使用。在您的信息被用于本网站的调查、统计或作内部整体使用的情况下，本网站可能会自动收集不足以使他人辨认您个人身份的技术性资料或在您的硬盘上存储少量的数据，上述行为的目的旨在为您提供更完善的服务；除非经您同意本网站不会向任何第三方透露足以辨认您身份的信息。本网站对网站内的讨论、传送、聊天及公布板上的内容不承担任何责任，但本网站禁止您传送和发放带有中伤、诽谤、造谣、色情及其他违法或不道德的资料和言论，本网站有权对此进行管理和监督，但并不对此承担任何责任。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本网站无意向[16岁]以下未成年人搜集任何个人资料，也不会向任何第三方透露任何未成年人的信息，并提请家长对其子女在使用互联网时个人信息的使用进行监管和负责，如未成年人通过公告版或类似形式自愿提供及公开的资料被他人使用或发放邮件，与本网站无关。</p>
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
