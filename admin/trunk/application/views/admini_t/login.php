<!DOCTYPE html>
<html lang="en">
<head>
    <title>吃在身边|官方后台管理系统</title><meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url()?>asset/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>asset/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>asset/css/matrix-login.css" />
    <link href="<?php echo base_url()?>asset/font-awesome/css/font-awesome.css" rel="stylesheet" />
    

</head>
<body>
    <div id="loginbox">
    <form id="loginform" class="form-vertical" action="<?php echo site_url('/admini/admini/do_login');?>" name="loginform" method="post">
         <div class="control-group normal_text"> <h3><img src="<?php echo base_url()?>asset/img/logo2.png" alt="Logo" /></h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"></i></span>
                    <input type="text" placeholder="Username" name="m_user" id="m_user" value=""/>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                    <input type="password" placeholder="Password" name="m_pwd" id="m_pwd" value="" />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span>
            <span class="pull-right"><a type="submit"  class="btn btn-success" onClick="check_login()"/> Login</a></span>
        </div>
    </form>
    <form id="recoverform" action="#" class="form-vertical">
        <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
    
        <div class="controls">
            <div class="main_input_box">
                <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
            </div>
        </div>
    
        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
            <span class="pull-right"><a class="btn btn-info"/>Reecover</a></span>
        </div>
    </form>
    </div>

    <script src="<?php echo base_url()?>asset/js/jquery.min.js"></script>
    <script src="<?php echo base_url()?>asset/js/matrix.login.js"></script>
    <script>
        function check_login(){
            var user = $("#m_user").val();
            var pwd = $("#m_pwd").val();
            if(user == "" || user == "Username"){
                alert("登录名不能为空！");
                return false;
            }
            if(pwd == "" || pwd == "Password"){
                alert("密码不能为空！");
                return false;
            }
            $("#loginform").submit();
        }
    </script>
</body>
</html>
