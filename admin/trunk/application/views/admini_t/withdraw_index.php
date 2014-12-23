<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<?php $statuses = array(
    '-1' => '全部状态',
    '1' => '审核中',
    '2' => '通过',
    '3' => '未通过'
) ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
            <a href="#" class="current">用户提现列表</a>
        </div>
        <h1>用户提现列表</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box filter">
                    <form method="get" action="<?php echo site_url('/admini/withdraw/index');?>" class="form-search widget-content" style="overflow: hidden">
                        <table width="100%" class="controls table-nav controls-row span12" style="margin: 0px;">
                            <tr>
                                <td><select style="width: 120px" name="status">
                                        <?php foreach ($statuses as $k => $v): ?>
                                        <option value="<?=$k ?>" <?php if (isset($_GET['status']) && $_GET['status'] == $k):?>selected<?php endif ?>><?=$v ?></option>
                                    <?php endforeach ?>
                                    </select></td>
                               <td><select style="width: 150px" name="type">
                              <option value="1" <?php if (isset($_GET['type']) && $_GET['type'] == 1): ?>selected<?php endif ?>>用户名称</option>
                              <option value="2" <?php if (isset($_GET['type']) && $_GET['type'] == 2): ?>selected<?php endif ?>>手机号</option>
                              <option value="3" <?php if (isset($_GET['type']) && $_GET['type'] == 3): ?>selected<?php endif ?>>银行帐号</option>
                          </select></td>
                      <td width="100" ><input type="text" name="keywords" value="<?=isset($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="关键字…"></td>
                                
                                <td>
                                    <input type="submit" class="btn btn-primary" value="查询"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>提现列表</h5> &nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>开户行</th>
                                <th>银行账号</th>
                                <th>开户名</th>
                                <th>提现金额</th>
                                <th>手机号</th>
                                <th>流水号</th>
                                <th>提现时间</th>
                                <th>受理时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($data_list): ?>
                            <?php foreach($data_list as $k => $v): ?>
                                <tr class="gradeA">
                                    <td><?php echo isset($v->user_id) ? $v->nickname : '[用户不存在]'; ?></td>
                                    <td><?php echo $v->bank; ?></td>
                                    <td><?php echo $v->bank_account; ?></td>
                                    <td><?php echo $v->bank_account_name; ?></td>
                                    <td><?php echo $v->money; ?></td>
                                    <td><?php echo $v->mobile; ?></td>
                                    <td><?php echo $v->order_no; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $v->submit_time); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $v->accepted_time); ?></td>
                                    <td>
                                        <?=$statuses[$v->status] ?>
                                    <td>

                                        <?php if ($v->status == 1): ?>
                                        <a class="btn btn-info btn-mini" href="<?php echo site_url('/admini/withdraw/approval/'.$v->id);?>">通过</a>
                                       <a class="btn btn-danger btn-mini"  onClick="loadBox.show()" >未通过</a>
                                       <!--<input type="button" value="未通过" onClick="loadBox.show()"/>-->
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif ?>
                            </tbody>
          
                        </table>
                    </div>
                </div>
                <div class="pager"><?=$pager ?></div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
body,div{padding:0;margin:0;border:0;}
body{height:100%;font-size:12px;}
.bgLayer{ background:#000; opacity:0.5; filter:alpha(opacity=50);z-index:10001;position:absolute;left:0;top:0;}
.boxLayer{ background:#fff; border:4px solid #ccc; overflow:hidden; zoom:1; z-index:10002; position:absolute;padding:8px;}
.boxLayer p{padding:5px 0;text-align:center;}
.alldenglu{
    width:260px;}
.denglu {
font-size: 14px;
margin-bottom:2px;
color: #999999;
}
</style>
<script type="text/javascript">
//<![CDATA[
//弹出层 by ChenLiang v1.0
function LightBox(boxWidth,boxHeight,boxContent)
{
this.boxWidth=boxWidth;
this.boxHeight=boxHeight;
this.boxContent=boxContent;
var bgLayer,boxLayer;
var documentHtml=document.documentElement;
this.createBgLayer=function()
{
bgLayer = document.createElement("div");
with (bgLayer)
{
className="bgLayer";
style.width=documentHtml.scrollWidth+"px";
style.height=documentHtml.scrollHeight+"px";
style.display="none";
}
document.body.insertBefore(bgLayer,document.body.firstChild);
};
this.createBox=function(){
boxLayer = document.createElement("div");
with (boxLayer)
{
className = "boxLayer";
style.width=this.boxWidth + "px";
style.height = this.boxHeight +"px";
style.display="none";
};
document.body.insertBefore(boxLayer,document.body.firstChild);
};
this.init= function()
{
this.createBgLayer();
this.createBox();
}
// if IE 6.0
function hideShowSelect(obj)
{
if (window.navigator.userAgent.indexOf("MSIE 6.0") > 0)
{
var selectDom = document.getElementsByTagName("select");
for (var i = 0; i < selectDom.length ; i++)
{
if (obj)
selectDom[i].style.display="none";
else
selectDom[i].style.display="";
}
}
};
this.show = function()
{
hideShowSelect(true);
boxLayer.innerHTML=this.boxContent;
bgLayer.style.display = "block";
boxLayer.style.display = "block";
boxLayer.style.left = documentHtml.offsetWidth /2 - boxLayer.offsetWidth/2 +"px";
boxLayer.style.top = documentHtml.scrollTop + documentHtml.offsetHeight/2 - this.boxHeight/2 -30 + "px";
}
this.hide = function()
{
hideShowSelect(false);
bgLayer.style.display = "none";
boxLayer.style.display = "none";
}
}
//]]>
</script>
<script type="text/javascript">
//调用方法
var loadBox= new LightBox(260,150);
window.onload=function(){
loadBox.init();
loadBox.boxContent='<div class="alldenglu"><form action="<?php echo site_url('/admini/withdraw/notpass/'.$v->id);?>" method="post" enctype="multipart/form-data" name="indexForm"><div class="wenji">请输入理由！</div><div align="center" class="denglu">理由:<textarea name=remark cols=5 rows=3 wrap=*></textarea></div><div align="center" class="denglu"><input type="submit" name="button" id="button" value="提交" onclick="return checkform()" /> <input name="" type="reset" value="重置" /><input type="button" value="关闭" onclick="loadBox.hide()" /></div></form></div>';
}
</script>
<?php echo $footer;?>
</body>
</html>


