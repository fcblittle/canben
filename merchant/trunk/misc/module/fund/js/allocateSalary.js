$(function () {
    var amount;

    // 显示模态框
    $('.doAllocate').on('click', function () {
        var dateString;

        // 设置总额
        window.amount = $(this).attr('data-amount');
        $('.effectiveSalary').text('￥' + window.amount);

        // 设置时间
        $('input[name=date]').val($(this).attr('data-date'));
        dateString = getDateInfo($(this).attr('data-date'));
        $('.modal-header').find('h3').text(dateString + ' 工资分配');

        // 模态框
        $('#allocate').modal({
            backdrop:true
        });
    });

    // 计算余额
    $('.control-group').find('input[type=text]').on('blur', function () {
        var val;

        if (/^[0-9]+(.[0-9]{2})?$/.test($(this).val()) === false) {
            $(this).val(0);
        }

        var sum = getSum();

        if (getSurplus(sum) >= 0) {
            surplus = '￥' + getSurplus(sum).toFixed(2);
            $('#surplus').hasClass('badge-important') 
            && $('#surplus').removeClass('badge-important').addClass('badge-info');
        } else {
            surplus = '余额不足';
            $('#surplus').removeClass('badge-info').addClass('badge-important');
        }
        $('#surplus').text(surplus);
    });

    // 提交分配项
    $('#allocation').submit(function () {
        $.ajax({
            url: '/api/fund/allocateSalary/allocate',
            type: 'post',
            data: $(this).serialize(),
            success: function (data) {
                if (data.code != 'OK') {
                    alert(data.message);
                } else {
                    window.location.reload();
                }
            }
        });

        return false;
    });

    // 获取工资分配明细
    $('.allocationDetail').on('click', function () {
        var $modal, role;

        var dataDinerId = $(this).attr('data-diner');
        var dataDate    = $(this).attr('data-date');

        $.ajax({
            url: '/fund/salary/allocationList',
            type: 'post',
            dataType: 'json',
            data: {diner_id: dataDinerId, date: dataDate},
            success: function (data) {
                if (data.code !== 'OK') {
                    alert(data.message);
                }
                clearModalFormData('#allocationList');

                $modal = $('#allocationList');
                // 设置头部
                // $modal.find('.modal-header').find('h3').text(data.content.diner.diner_name + ' ' + getDateInfo(dataDate) + ' ' + '工资明细');
                setModalHeader('#allocationList', {
                    diner: data.content.diner,
                    date:  dataDate,
                    title: '工资明细'
                });

                // 设置主体
                $.each(data.content.allocation, function(i, v) {
                    var role = (v.role == 1) ? '店长' : '店小二';

                    setModalFormData('#allocationList', {
                                        name: role + '(' + v.realname + ')',
                                        amount: v.salary
                                    });
                });

                // 模态框
                $('#allocationList').modal({
                    backdrop:true
                });
            }
        });
    });

    // 获取扣项明细
    $('.deduction').on('click', function () {
        var dataDinerId = $(this).attr('data-diner');
        var dataDate    = $(this).attr('data-date');

        $.ajax({
            url: '/fund/salary/deductionList',
            type: 'post',
            dataType: 'json',
            data: {diner_id: dataDinerId, date: dataDate},
            success: function (data) {
                if (data.code !== 'OK') {
                    alert(data.message);
                }
                clearModalFormData('#allocationList');

                // 设置头部
                setModalHeader('#allocationList', {
                    diner: data.content.diner,
                    date:  dataDate,
                    title: '扣项明细'
                });

                // 设置主体
                $.each(data.content.deduction, function(i, v) {
                    setModalFormData('#allocationList', v);
                });

                // 模态框
                $('#allocationList').modal({
                    backdrop:true
                });
            }
        });
    });

    // 设置模态框头部
    function setModalHeader(selector, data)
    {
        $(selector)
        .find('.modal-header')
        .find('h3')
        .text(data.diner.diner_name + ' ' + getDateInfo(data.date) + ' ' + data.title);
    }

    // 设置表单数据
    function setModalFormData(selector, data)
    {
        var $dataModel, $dataBody, $controls;

        $dataModel = $('<div class="control-group"></div>');

        // label
        $dataBody = $('<lable class="control-label">' + data.name + '：' + '</lable>');

        // controls
        $controls = $('<div class="controls"><div class="input-append"></div></div>');
        $('<input />', {
            type: 'text',
            class: 'form-control text-center allocationItem',
            value: data.amount,
            disabled: true
        }).add('<span class="add-on">￥</span>').appendTo($controls.find('.input-append'));

        $dataBody.add($controls).appendTo($dataModel);
        $dataModel.appendTo($(selector).find('.modal-body'));
    }

    // 清除表单数据
    function clearModalFormData(selector)
    {
        $(selector).find('.modal-body').empty();
    }

    function getDateInfo(date)
    {
        var dateArr = date.split('-');

        return dateArr[0] + '年' + dateArr[1] + '月';
    }

    function getSum()
    {
        var sum = 0;

        $('.control-group').find('input[type=text]').each(function(i, obj){
            if (! $(obj).val()) {
                return true;
            };
            val = parseFloat($(obj).val());
            sum = sum + val;
        });

        return sum;
    }

    function getSurplus(sum)
    {   
        return parseFloat(window.amount) - sum;
    }
});