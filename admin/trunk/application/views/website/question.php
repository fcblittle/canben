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
         	<h2 class="shadowText float_l mar_r10">常见问题</h2>
         	<span class="English float_l shadowText">/ Frequently Asked Questions</span>
         	<span class="more float_r shadowText"><a href="<?php echo base_url()?>">首页 ></a><a href="#">常见问题</a></span>
         </div>
         <div class="mainCnt float_l" style="height:auto;">
         <ul class="area float_l question">
           <li>
            1、<span>如何注册餐本 ？</span>
            <div class="cont">
             进入餐本首页→我的→注册→填写注册信息→短信获取验证码→输入验证码→确认提交完成注册
            </div>
           </li>
           <li>
           2、<span>餐本可以预储值么？</span>
           <div class="cont">
            可以，您可以通过餐本首页→我的→我的账户→充值→选择充值额度→选择银行并支付→完成充值
            </div> 
           </li>
           <li>
           3、<span>预储值有什么好处？</span>
           <div class="cont">
            方便、快捷、可以参与更多优惠活动
           </div>
           </li>
           <li>
          4、<span>餐本是否可以退款？如何退款 ？</span>
           <div class="cont">
            可以退款。进入餐本→已付款订单→退款申请提交，为保证退款的准确性和安全性，提交后会由客服人员与您联系确认退款人及相关信息，退款将会在7个工作日内退还，款项将退还到您的账户余额中
           </div>
           </li>
           <li>
           5、<span>预储值可以提现么 ？</span>
           <div class="cont">
            进入餐本首页→我的→我的账户→提现按钮→输入提现金额提交，提交后会由客服人员与您联系确认，提现金额将会在7个工作日内退还到您的账户中
           </div>
           </li>
           <li>
           6、<span>付款后如何取餐？</span>
           <div class="cont">
            在付款后系统会推送给您一个订单确认码，您只要凭订单确认码到您订餐餐车上出示，由商家确认后即可取餐
           </div>
           </li>
           <li>
           7、<span>餐本经营时间？</span>
           <div class="cont">
            根据法律规定，早餐9点前结束，午餐13：30结束，为保障商户和消费者的利益，餐本分别于早餐的8：50和午餐的13：20关闭预定下单功能
           </div>
           </li>
           <li>
           8、<span>餐厅有什么作用？</span>
           <div class="cont">
           餐厅做为一个展示窗口，通过小编的介绍，让用户能够了解到身边的品质比较好的餐厅。餐厅暂不提供订餐功能
           </div>
           </li>
           <li>
           9、<span>餐本食品安全是如何解决的？</span>
           <div class="cont">
           餐本中全部菜品由联合食通提供，且每种菜品都由国家检验检疫局提供餐品出餐证明，保证食品安全卫生
           </div>
           </li>
         </ul>
         </div>
		</div>
    </div>
  </div>
        <?php echo $foot;?>
</div> 
</body>
<!--<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
  $('.question li').click(function (e) {
    if($(this).find(".cont").is(":visible")==false){
      $(this).find(".cont").slideDown();
    }else{
      $(this).find(".cont").slideUp();
    }
    
  })
</script>-->
</html>
