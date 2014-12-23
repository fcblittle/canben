$(function() {
    // 查询
    $('#search-date').click(function () {
        path = window.location.href.split('?');

        if (typeof path[1] !== 'undefined' && path[1] != '') {
            queries = path[1].split('&');

            queryParam = [];
            isDate = false;
            $.each(queries, function(index, v) {
                /*console.log(v);
                console.log(v.indexOf('date='));*/
                if(v.indexOf('date=') > -1) {
                    // queryParam.concat('date=' + $(this).siblings('[name=date]').val());
                    queryParam.push('date=' + $('[name=date]').val());
                    isDate = true;
                } else {
                    queryParam.push(v);
                }
            });
            if (! isDate) {
                queryParam.push('date=' + $(this).siblings('[name=date]').val());
            }

            queryStr = queryParam.join("&");
        } else {
            queryStr = 'date=' + $(this).siblings('[name=date]').val()
        }
        /*console.log(queryStr);
        return false;*/
        window.location.href = '?' + queryStr;
    });

    // 选中转账摘要
    $('[name=variationType]').on('click', selectType);

    $('#operator').submit(function () {
        $.ajax({
            url: '/api/fund/fund/transferAccounts',
            data: $(this).serialize(),
            success: function(data) {
                if (data.code == 200) {
                    alert('转账成功！');
                    $('#operation').modal('hide');
                    window.location.reload();
                } else if (data.code == 400) {
                    $.each(data.message, function (index, v) {
                        alert(v);
                    });
               } else {
                    alert(data.message);
               }
            }
        });

        return false;
    });
});

function selectType(e) {
    id = $(this).select2("val");
    switch(id)
    {
        case '1':
            showFundVariation(3, 1);
            break;
        case '2':
            showFundVariation(1, 3);
            break;
        case '3':
            showFundVariation(1, 2);
            break;
        case '4':
            showFundVariation(2, 1);
            break;
        default:
            showFundVariation(3, 1);
    }
}

function showFundVariation(from, to)
{
    $("[name=accountType]").parents('.control-group').show();
    $("[name=accountType]").select2("val", from);
    // $("[name=accountType]").parent().append('<input type="hidden" name="accountType" value="'+ from +'" />');

    $("[name=accountTo]").parents('.control-group').show();
    $("[name=accountTo]").select2("val", to);
    // $("[name=accountTo]").parent().append('<input type="hidden" name="accountTo" value="'+ to +'" />');
}