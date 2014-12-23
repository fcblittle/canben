<!doctype html>
<html lang="zh" id="login">
<head>
<meta charset="UTF-8">
<title>登陆</title>
<link rel="stylesheet" href="<?php echo $this->misc('css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/bootstrap-responsive.min.css'); ?>" />
<link rel="stylesheet" href="<?php echo $this->misc('css/matrix-login.css'); ?>" />
<link href="<?php echo $this->misc('font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" />
</head>
<body>
<div id="loginbox">
    <form id="loginform" method="post" class="form-vertical" action="/account/login/auth">
        <div class="control-group normal_text"> <h3><img src="<?php echo $this->misc('img/logo.png'); ?>" alt="Logo" /></h3></div>
        <div class="text-error alert" id="login-hint" style="display: none"></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"></i></span><input name="name" type="text" placeholder="用户名" />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span><input name="pass" type="password" placeholder="密码" />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">忘记密码?</a></span>
            <span class="pull-right"><button type="submit"   class="btn btn-success" /> 登陆</button></span>
        </div>
    </form>
    <form id="recoverform" action="#" class="form-vertical">
        <p class="normal_text">输入您的电子邮箱，您将收到重置密码的邮件.</p>

        <div class="controls">
            <div class="main_input_box">
                <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="邮件地址" />
            </div>
        </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; 返回登陆</a></span>
            <span class="pull-right"><button type="submit" class="btn btn-info"/>找回</button></span>
        </div>
    </form>
</div>

<script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
<script src="<?php echo $this->misc('js/matrix.login.js'); ?>"></script>
</body>
</html>