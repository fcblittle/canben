
$(document).ready(function(){

    $('input[type=checkbox],input[type=radio],input[type=file]').uniform();

    $('select').select2();
    jQuery.validator.addMethod("compareDate", function(value,element,param) {
        var startDate = jQuery(param).val();
        var date1 = parseInt(startDate.replace(":", ""));
        var date2 = parseInt(value.replace(":", ""));
        console.log(date1);
        console.log(date2);
        console.log(date1 < date2);
        return date1 < date2;
    }, "请输入正确的时间");
    // Form Validation
    $("#basic_validate").validate({
        rules:{
            required:{
                required:true
            },
            email:{
                required:true,
                email: true
            },
            date:{
                required:true,
                date: true
            },
            url:{
                required:true,
                url: true
            },
            end:{
                compareDate:"[name='start']"
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    $("#number_validate").validate({
        rules:{
            min:{
                required: true,
                min:10
            },
            max:{
                required:true,
                max:24
            },
            number:{
                required:true,
                number:true
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    $("#password_validate").validate({
        rules:{
            pwd:{
                required: true,
                minlength:6,
                maxlength:20
            },
            pwd2:{
                required:true,
                minlength:6,
                maxlength:20,
                equalTo:"#pwd"
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });
});
