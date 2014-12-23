<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?=$_USER->store_name ?> - 食通商家后台</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php $this->region('Module\Common:styles') ?>
</head>
<body>
<!--Header-part-->
<div id="header">
    <h1><a href="dashboard.html">Matrix Admin</a></h1>
</div>
<!--close-Header-part-->


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
        <li class=""><a title="" href="javascript:;"><i class="icon icon-user"></i> <span class="text">欢迎：<?=$_USER->name ?>&nbsp;(<?=$_USER->type === 'merchant' ? '商户' : '经营者';?>)</span></a></li>
        <li class=""><a title="" href="/account/setting"><i class="icon icon-user"></i> <span class="text">账号设置</span></a></li>
        <li class=""><a title="" href="/account/logout"><i class="icon icon-share-alt"></i> <span class="text">退出</span></a></li>
    </ul>
</div>
<!--close-top-Header-menu-->
