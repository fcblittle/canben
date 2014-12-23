<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>系统错误</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<style>
body{
	font-family: 'Microsoft Yahei', Verdana, arial, sans-serif;
	font-size:14px;
	margin:20px;
}
.http-error h3{
    color:#666;
}
.http-error .code{
    font-size:18em;
    color:#ddd;
    position:fixed;
    bottom:.5em;
    right: .5em;
}
a{text-decoration:none;color:#174B73;}
a:hover{ text-decoration:none;color:#FF6600;}
h2.title{
	border-bottom:1px solid #DDD;
	padding:10px 0;
    font-size:25px;
    color:#5f5f5f;
    margin-bottom:0
}
.message,#trace{
	padding:1em;
	border:solid 1px #000;
	margin:10px 0;
	background:#FFD;
	line-height:150%;
}
.message{
	background:#FFD;
	color:#2E2E2E;
		border:1px solid #E0E0E0;
}
#trace{
	background:#E7F7FF;
	border:1px solid #E0E0E0;
	color:#535353;
}
.notice{
    padding:10px;
	margin:5px;
	color:#666;
	background:#FCFCFC;
	border:1px solid #E0E0E0;
}
.red{
	color:red;
	font-weight:bold;
}
</style>
</head>
<body>
<h2 class="title">由于系统错误，程序无法继续执行</h2>
 <div id="maincontent">
    <div class="wrap">
    <div class="main http-error http-error-404">
        <h3>提示：<?php echo isset($e['message']) ? $e['message'] : '请检查您的操作是否有误。' ?></h3>
        <p>您可以：[<a href="javascript:window.location.reload();">重试</a>] 或 [<a href="<?php echo url('<front>') ?>">返回首页</a>]</p>
        <span class="code">400</span?>
    </div>
    </div>
</div>
</body>
</html>
