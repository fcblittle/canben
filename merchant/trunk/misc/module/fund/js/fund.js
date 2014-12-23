$(function() {
    var Alert = function (option) {
        this.options = {
            title: '提示',
            btnOk: '确定',
            btnCancel: '取消',
        };

        this.modal = $('#alert');
        this.formData = this.modal.find('#alertForm');
        this.header = this.modal.find('.modal-header');
        this.body = this.modal.find('.modal-body');
        this.footer = this.modal.find('.modal-footer');

        this.init(option);
    }

    Alert.prototype = {
        init: function (option) {
            $.extend(this.options, option);
            this.setTitle(this.options.title);
            this.setBtnText({ok: this.options.btnOk, cancel: this.options.btnCancel});

            this.clear('.ok', 'click');
            this.clear('.cancel', 'click');
        },
        show: function () {
            this.modal.modal('show');
        },
        hide: function () {
            this.modal.modal('hide');
        },
        setTitle: function (title) {
            this.header.find('h3').text(title);
        },
        setBody: function (text) {
            this.body.empty();

            this.body.html(text);
        },
        setBtnText: function (obj) {
            this.footer.find('.ok').text(obj.ok);
            this.footer.find('.cancel').text(obj.cancel);
        },
        setBtnHandler: function (selector, callback) {
            this.clear(selector, 'click');

            this.footer.find(selector).on('click', function () {
                callback && callback();
            });
        },
        clear: function (selector, event) {
            this.footer.find(selector).off(event);
        }
    };
    
    // 查询
    $('#search-date').click(function () {
        path = window.location.href.split('?');

        if (typeof path[1] !== 'undefined' && path[1] != '') {
            queries = path[1].split('&');

            queryParam = [];
            isDate = false;
            $.each(queries, function(index, v) {
                if(v.indexOf('date=') > -1) {
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
        window.location.href = '?' + queryStr;
    });

    // 选中转账摘要
    $('[name=variationType]').on('click', selectType);

    $('#operation').find('.ok').on('click', function() {
        
        $operation = $('#operation');
        // 验证转账类型
        transferId = $operation.find('[name=variationType]').val();

        actModal($operation, transferId);
    });

    function actModal(obj, id) {
        switch (id)
        {
            case '1':
                doTransefer(obj);
                obj.modal('hide');

                alertModal = new Alert({title: '银联充值', btnOk: '完成充值', btnCancel: '遇到问题？'});
                alertModal.setBody('充值成功？');
                alertModal.setBtnHandler('.ok', function () {
                    alertModal.hide();
                    window.location.reload();
                });
                alertModal.setBtnHandler('.cancel', function () {
                    alertModal.setBody('请拨打我们的服务电话 ');
                });
                alertModal.show();
                return;
            case '2':
                obj.modal('hide');

                alertModal = new Alert({title: '钱包提现', btnOk: '确定', btnCancel: '取消'});

                setWithdrawText(alertModal.body, [
                    {id: 'bank', name: '开户银行'},
                    {id: 'account', name: '银行账号'},
                    {id: 'name', name: '银行户名'},
                    {id: 'mobile', name: '银行预留电话'},
                    {id: 'amount', name: '金额', value: obj.find('input[name=amount]').val()},
                ]);

                alertModal.setBtnHandler('.ok', function () {
                    doTransefer(alertModal.modal, alertModal.formData.serialize());
                });
                alertModal.setBtnHandler('.cancel', function () {
                    alertModal.hide();
                });
                alertModal.show();
                return;
            default:
                doTransefer(obj);
                break;
        }
    }

    function setWithdrawText(container, params)
    {
        var lable, input;

        container.empty();

        var $group = $('<div class="control-group"><lable class="control-label"></lable><div class="controls"></div></div>');

        $.each(params, function (i, v) {

            if (v.id == 'amount') {
                input = '<div class="input-append"><input type="text" name="'+v.id+'" value="'+ v.value +'" /> <span class="add-on">￥</span></div>';
            } else {
                input = '<input type="text" name="' + v.id + '" style="width: 250px;" />';
            }

            $group.find('lable').text(v.name + '：');
            $group.find('.controls').html(input);

            $group.clone().appendTo(container).end();
        });

        $('<input type="hidden" name="variationType" value="2" />').appendTo(container).end();
        $('<input type="hidden" name="ajax" value="1" />').appendTo(container).end();
    }

    function doTransefer(obj, formData)
    {
        var checkPaid = false;
        var data = (typeof formData !== 'undefined') ? formData : obj.find('#operator').serialize();

        $.ajax({
            url: '/api/fund/fund/transferAccounts',
            data: data,
            type: 'post',
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data.code == 200) {
                    alert(data.message);
                    window.location.reload();
                } else if (data.code == 400) {
                    $.each(data.message, function (index, v) {
                        alert(v);
                    });
                    return false;
               } else if (data.code == 600) {
                    $('#pay_form').attr("action", data.url);
                    $.each(data.content, function (i, v) {
                        $('#pay_form').find("input[name=" + i +"]").val(v);
                    });
                    checkPaid = true;
                    obj.modal('hide');
                } else {
                    alert(data.message);
                    return false;
               }
            }
        });

        checkPaid && ($("select[name=variationType]").val() == 1) && $('#pay_form').submit();
    }

    function selectType(e) {
        id = $(this).select2("val");
        switch(id)
        {
            case '1':
                $('.modal-footer').find('.ok').text('去网银转账');
                showFundVariation(3, 1);
                break;
            case '2':
                $('.modal-footer').find('.ok').text('申请提现');
                showFundVariation(1, 3);
                break;
            case '3':
                $('.modal-footer').find('.ok').text('转账');
                showFundVariation(1, 2);
                break;
            case '4':
                $('.modal-footer').find('.ok').text('转账');
                showFundVariation(2, 1);
                break;
            default:
                $('.modal-footer').find('.ok').text('确定');
                showFundVariation(3, 1);
        }
    }

    function showFundVariation(from, to)
    {
        $("[name=accountType]").parents('.control-group').show();
        $("[name=accountType]").select2("val", from);

        $("[name=accountTo]").parents('.control-group').show();
        $("[name=accountTo]").select2("val", to);
    }
});