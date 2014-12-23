
$(document).ready(function(){



    // === Prepare peity charts === //
    maruti.peity();
    // === make charts === //
    var  makeCharts = function(month){
        nMonth = (!month)?$("select#year").val() + $("select#month").val():month;
        var $chart = $(".chart");
        $.get(
            "api/customer/order/countMonthly",
            {month:nMonth},
            function(data){
                if(data.code===200){
                    var plot = $.plot(
                        $chart,
                        [{
                            data: data.content.list,
                            label: "订单数",
                            lines: {show: true},
                            points: {show: true, symbol: "circle"}
                        }],
                        {
                            xaxis: {
                                tickSize: 1,
                                tickFormatter: function(a) {
                                    return (a < 10 ? "0" : "") + a;
                                }
                            },
                            yaxis: {
                                min: 0,
                                max:  ++data.content.max,
                                tickSize: 1,
                                tickFormatter: function(a) {
                                    return a + "份";
                                }
                            },
                            grid: {
                                hoverable: true,
                                clickable: true
                            }
                        });
                    $chart.bind("plothover", function (event, pos, item) {
                        if (item) {
                            $("#tooltip").html(item.datapoint[1] + "份")
                                .css({top: item.pageY+5, left: item.pageX+5})
                                .fadeIn(200);
                        } else {
                            $("#tooltip").hide();
                        }
                        return false;
                    });
                    $('<span>(日)</span>').appendTo($chart).css({
                        "position": "absolute",
                        "right": "-2em", "bottom": "1px"
                    });
                }
            }
        );
    };
    makeCharts();
    $(".select_month").change(function(){
        var month = ($(this).attr("id") == "year")? $(this).val()+$("select#month").val():$("select#year").val()+$(this).val();
        makeCharts(month);
    });


    // === Point hover in chart === //
    var previousPoint = null;
    $(".chart").bind("plothover", function (event, pos, item) {

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $('#tooltip').fadeOut(200,function(){
                    $(this).remove();
                });
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                maruti.flot_tooltip(item.pageX, item.pageY,item.series.label + " of " + x + " = " + y);
            }

        } else {
            $('#tooltip').fadeOut(200,function(){
                $(this).remove();
            });
            previousPoint = null;
        }
    });




    // === Calendar === //    
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('.calendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        editable: true,
        events: [
            {
                title: 'All day event',
                start: new Date(y, m, 1)
            },
            {
                title: 'Long event',
                start: new Date(y, m, 5),
                end: new Date(y, m, 8)
            },
            {
                id: 999,
                title: 'Repeating event',
                start: new Date(y, m, 2, 16, 0),
                end: new Date(y, m, 3, 18, 0),
                allDay: false
            },
            {
                id: 999,
                title: 'Repeating event',
                start: new Date(y, m, 9, 16, 0),
                end: new Date(y, m, 10, 18, 0),
                allDay: false
            },
            {
                title: 'Lunch',
                start: new Date(y, m, 14, 12, 0),
                end: new Date(y, m, 15, 14, 0),
                allDay: false
            },
            {
                title: 'Birthday PARTY',
                start: new Date(y, m, 18),
                end: new Date(y, m, 20),
                allDay: false
            },
            {
                title: 'Click for Google',
                start: new Date(y, m, 27),
                end: new Date(y, m, 29),
                url: 'http://www.google.com'
            }
        ]
    });
});


maruti = {
    // === Peity charts === //
    peity: function(){
        $.fn.peity.defaults.line = {
            strokeWidth: 1,
            delimeter: ",",
            height: 24,
            max: null,
            min: 0,
            width: 50
        };
        $.fn.peity.defaults.bar = {
            delimeter: ",",
            height: 24,
            max: null,
            min: 0,
            width: 50
        };
        $(".peity_line_good span").peity("line", {
            colour: "#57a532",
            strokeColour: "#459D1C"
        });
        $(".peity_line_bad span").peity("line", {
            colour: "#FFC4C7",
            strokeColour: "#BA1E20"
        });
        $(".peity_line_neutral span").peity("line", {
            colour: "#CCCCCC",
            strokeColour: "#757575"
        });
        $(".peity_bar_good span").peity("bar", {
            colour: "#459D1C"
        });
        $(".peity_bar_bad span").peity("bar", {
            colour: "#BA1E20"
        });
        $(".peity_bar_neutral span").peity("bar", {
            colour: "#4fb9f0"
        });
    },

    // === Tooltip for flot charts === //
    flot_tooltip: function(x, y, contents) {

        $('<div id="tooltip">' + contents + '</div>').css( {
            top: y + 5,
            left: x + 5
        }).appendTo("body").fadeIn(200);
    }
}
