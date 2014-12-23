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
    <div class="main" style="height:auto;">
		<div class="mainBox float_l">
         <div class="mainHead">
         	<h2 class="shadowText float_l mar_r10">餐品展示</h2>           
         	<span class="English float_l shadowText">/ Dishes Show</span>
         	<span class="more float_r shadowText"><a href="<?php echo base_url()?>">首页 ></a><a href="/website/zhinan">餐品展示</a></span>
         </div>
         <div class="mainCnt" style="height:auto;">
          <!--<ul class="area caipin " id="myTab">        
            <li class="active"><a href="#mor" data-toggle="tab">早餐</a></li>
            <li><a href="#lau" data-toggle="tab">午餐</a></li>
            <li><a href="#suit_mor" data-toggle="tab">套餐（早餐）</a></li>
            <li><a href="#suit_lau" data-toggle="tab">套餐（午餐）</a></li>
          </ul>-->
          <div class="w_top">
            <div class="tab-content" id="myTab_content">
              <div class="tab-pane active" id="mor">
                        <ul>
                        <li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/1.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理猪肉韭菜包</a>
                            <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：35g<br />
                        配料：小麦粉、水、韭菜、猪肉、酱油、芝麻油、香辛料、酵母、盐
                    </p>
                        </li>
                        <li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/2.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理酱肉包</a>
                            <input name="" type="checkbox" value="" style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：35g<br />
                        配料：小麦粉、水、猪肉、谷朊粉、韭菜、笋丁、酱油、芝麻油、酵母、盐
                    </p>
                        </li>          
			<li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/3.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理猪肉白菜包</a>
                            <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：35g<br />
                        配料：小麦粉、水、圆白菜、猪肉、酱油、芝麻油、香辛料、酵母、盐
                        </p>
                        </li>
                        <li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/4.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理猪肉野菜包</a>
                            <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：35g<br />
                        配料：小麦粉、水、黄须菜、猪肉、酱油、芝麻油、酵母、盐、料酒
                        </p>
                        </li>
                       <li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/5.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理猪肉包</a>
                            <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：33g<br />
                        配料：小麦粉、水、猪肉、小麦蛋白、酱油、香油、酵母、白糖、盐、料酒
                    </p>
                        </li>
                        <li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/6.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理三鲜包</a>
                            <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：33g<br />
                        配料：小麦粉、水、猪肉、谷朊粉、虾仁、黑木耳、酱油、芝麻油、料酒
                        </p>
                        </li>  
                        <li>
                        <a href="#"><img src="<?php echo base_url()?>asset/website/images/7.jpg" /></a>
                        <div class="yz">
                            <a href="#">狗不理全素包</a>
                            <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                            <div class="clb"></div>
                        </div>
                        <p>
                        规格：35g<br />
                        配料：圆白菜、水、胡萝卜、豆腐干、香菇、香菜、粉丝、黑木耳、虾米
                        </p>
                        </li>  
                  
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/8.jpg" /></a>
                      <div class="yz">
                          <a href="#">庆丰猪肉梅干菜包子</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：37g<br />
                      配料：小麦粉、水、猪肉、梅干菜、猪油、酱油、芝麻油、葱、姜、鸡粉
                  </p>
                      </li>
                                <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/9.jpg" /></a>
                      <div class="yz">
                          <a href="#">庆丰素三鲜包</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：37g<br />
                      配料：小麦粉、水、香菇、青菜、植物油、芝麻油、盐、酵母、味精、鸡粉
                  </p>
                      </li>          <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/10.jpg" /></a>
                      <div class="yz">
                          <a href="#">庆丰猪肉三鲜包</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：37g<br />
                      配料：小麦粉、水、猪肉、鸡蛋、皮冻、酱油、料酒、芝麻油、葱、姜
                      </p>
                      </li>          <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/11.jpg" /></a>
                      <div class="yz">
                          <a href="#">庆丰猪肉大葱包</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：37g<br />
                      配料：小麦粉、水、香菇、青菜、植物油、芝麻油、盐、酵母、味精、鸡粉
                      </p>
                      </li>          <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/12.jpg" /></a>
                      <div class="yz">
                          <a href="#">香菇素菜包</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：30g<br />
                      配料：小麦粉、水、香菇、青菜、植物油、芝麻油、盐、酵母、味精、鸡粉
                  </p>
                      </li>
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/13.jpg" /></a>
                      <div class="yz">
                          <a href="#">豆沙包</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：30g<br />
                      配料：面粉 红豆沙 酵母 糖
                      </p>
                      </li>   
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/14.jpg" /></a>
                      <div class="yz">
                          <a href="#">奶黄包</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：25g<br />
                      配料表：粟粉、淡奶、椰汁、奶粉、牛油、粉、干发酵粉、糖、泡打粉、牛奶
                      </p>
                      </li> 
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/15.jpg" /></a>
                      <div class="yz">
                          <a href="#">粥</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：310g<br />
                      配料：水、糖、红小豆、花生、莲子、红枣等
                      </p>
                      </li> 
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/16.jpg" /></a>
                      <div class="yz">
                          <a href="#">荞麦面</a>
                          <input name="" type="checkbox" value="" style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                      规格：200g<br />
                      配料：荞麦面粉 水 青菜 海鲜汤汁
                  </p>
                      </li>
                      
                       <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/17.jpg" /></a>
                      <div class="yz">
                          <a href="#">乌冬面</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                    <p>
                      规格：250g<br />
                      配料：专用小麦粉 水 淀粉 食用盐
                  </p>
                      </li>
                      
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/18.jpg" /></a>
                      <div class="yz">
                          <a href="#">鸡汁味乌冬面</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                     <p>
                      规格：250g<br />
                      配料：专用小麦粉 水 淀粉 食用盐等
                  </p>
                      </li>
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/19.jpg" /></a>
                      <div class="yz">
                          <a href="#">酱油味乌冬面</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                     <p>
                      规格：250g<br />
                      配料：专用小麦粉 水 淀粉 食用盐等
                  </p>
                      </li>
                       <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/20.jpg" /></a>
                      <div class="yz">
                          <a href="#">排骨味日式拉面</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                     <p>
                      规格：200g<br />
                      配料：小麦粉 水 淀粉 食用盐 栀子黄 等
                  </p>
                      </li>
                       <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/21.jpg" /></a>
                      <div class="yz">
                          <a href="#">酱油味日式拉面</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                     <p>
                      规格：200g<br />
                      配料：小麦粉 水 淀粉 食用盐 栀子黄等
                  </p>
                      </li>
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/22.jpg" /></a>
                      <div class="yz">
                          <a href="#">风味酱香手抓饼</a>
                          <input name="" type="checkbox" value=""  style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                     <p>
                      规格：120g<br />
                      配料：培根、火腿片、煎蛋、生菜<br />秘制风味酱：辣酱、五香酱
                  </p>
                      </li>
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/23.jpg" /></a>
                      <div class="yz">
                          <a href="#">鸡蛋灌饼</a>
                          <input name="" type="checkbox" value="" style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                      <p>
                        规格：120g<br />                     
                      </p>
                      </li>
                      <li>
                      <a href="#"><img src="<?php echo base_url()?>asset/website/images/24.jpg" /></a>
                      <div class="yz">
                          <a href="#">豆浆</a>
                          <input name="" type="checkbox" value="" style="float:right; margin-top:15px; margin-right:5px;">
                          <div class="clb"></div>
                      </div>
                     <p>
                      规格：250ml<br />
                      配料：黄豆
                  </p>
                      </li>
                  </ul>
                <div class="clb"></div>
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
<script src="<?php echo base_url()?>asset/website/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo base_url()?>asset/website/js/bootstrap.min.js"></script>
<script>
  $('#myTab a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
    $(this).parent().siblings().removeClass("now")
  })
</script>
</body>

</html>
