
$(document).ready(function(){

    $(".form-wizard").formwizard({
        formPluginEnabled: true,
        validationEnabled: true,
        focusFirstInput : true,
        disableUIStyles : true,

        formOptions :{
            success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("<span>表单被提交!</span>").fadeTo(5000, 0); })},
            beforeSubmit: function(data){$("#submitted").html("<span>表单提交了Ajax。发送到服务器的数据是: " + $.param(data) + "</span>");},
            dataType: 'json',
            resetForm: true
        },
        validationOptions : {
            rules: {
                username: "required",
                password: "required",
                password2: {
                    equalTo: "#password"
                },
                email: { required: true, email: true },
                eula: "required"
            },
            messages: {
                username: "请输入您的姓名或用户名",
                password: "您必须输入密码",
                password2: { equalTo: "密码不匹配" },
                email: { required: "请输入您的电子邮件", email: "正确的邮件格式为 name@domain.com" },
                eula: "您必须接受最终用户许可协议"
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
            }
        }
    });
});
