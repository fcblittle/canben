;(function() {
    // 全局命名空间
    this.App = this.A = {
        path: "",
        args: [],
        queries: {}
    };
    this.d = document;
    this.de = this.d.documentElement;
    this.isIE6 = !!window.ActiveXObject && !window.XMLHttpRequest;
    // 时间戳
    A.REQUEST_TIME = Math.round(new Date().getTime() / 1000);
    // 预定义模板
    A.TPL = TPL = {};
    // 工具集
    A.Util = Util = {};

    /**
     * 模板引擎
     */
    this.tpl = function(ID) {
        function Tpl(ID) {
            this.ID = ID;
            this.tpl = $("#" + ID).html();

            return this;
        }

        Tpl.prototype.render = function(data) {
            return data ? tpl.parse(this.tpl, data) : this.tpl;
        };

        if (! tpl.cache[ID]) {
            tpl.cache[ID] = new Tpl(ID);
        }

        return tpl.cache[ID];
    };

    tpl.cache = {};

    tpl.parse = function(str, data) {
        if (! data) return str;
        var fn = new Function("obj",
            "var p=[],print=function(){p.push.apply(p,arguments);};" +
                "with(obj){p.push('" +
                str
                    .replace(/[\r\t\n]/g, " ")
                    .split("<%").join("\t")
                    .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                    .replace(/\t=(.*?)%>/g, "',$1,'")
                    .split("\t").join("');")
                    .split("%>").join("p.push('")
                    .split("\r").join("\\'")
                + "');}return p.join('');");

        return fn(data);
    };

    // 释放ajax资源
    $.ajaxSetup({
        complete: function(XHR, TS) {
            XHR = null;
        }
    });

    /**
     * 初始化
     */
    A.Util.init = function() {
        var pairs = location.search.substring(1).split("&");
        for(var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('=');
            if (pos == -1) continue;
            var argname = pairs[i].substring(0,pos);
            var value = pairs[i].substring(pos+1);
            value = decodeURIComponent(value);
            A.queries[argname] = value;
        }
        A.path = window.location.pathname.substr(1);
        A.args = A.path ? A.path.split('/') : [];
    };

    /**
     * 加载js文件
     *
     * @param array queue 要加载的队列
     * @param bool async 是否为异步,
     * @param function callback 回调
     */
    A.Util.load = function(queue, async, callback) {
        var self = this, i, queued = queue.length, elem;
        this.scripts = this.scripts || [];
        $.each(queue, function(k, v) {
            if (typeof self.scripts[v[0]] !== "undefined") {
                v[1] && v[1](self.scripts[v[0]]);
                ! (--queued) && callback && callback();
            } else {
                $.ajax({
                    url: v[0], async: !! async, cache: true,
                    dataType: "script",
                    success: function(data) {
                        self.scripts[v[0]] = data;
                        v[1] && v[1](data);
                        ! (--queued) && callback && callback();
                    }
                });
            }
        });
    };

    /**
     * 获取path分段
     */
    A.Util.arg = function(index) {
        if (typeof index === 'undefined') {
            return A.args;
        }
        return typeof A.args[index] !== "undefined" ? A.args[index] : '';
    };

    /**
     * 获取路径
     */
    A.Util.getPath = function() {
        return A.path;
    };

    /**
     * 获取get参数
     */
    Util.getQuery = function(name) {
        return A.queries[name] || "";
    };

    /**
     * 生成url
     */
    A.Util.url = function(path) {
        return "/" + A.config.instances[A.instance] + "/" + path;
    };

    A.Util.init();
})();

$(document).ready(function(){

	// === Sidebar navigation === //
	
	$('.submenu > a').click(function(e)
	{
		e.preventDefault();
		var submenu = $(this).siblings('ul');
		var li = $(this).parents('li');
		var submenus = $('#sidebar li.submenu ul');
		var submenus_parents = $('#sidebar li.submenu');
		if(li.hasClass('open'))
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenu.slideUp();
			} else {
				submenu.fadeOut(250);
			}
			li.removeClass('open');
		} else 
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenus.slideUp();			
				submenu.slideDown();
			} else {
				submenus.fadeOut(250);			
				submenu.fadeIn(250);
			}
			submenus_parents.removeClass('open');		
			li.addClass('open');	
		}
	});
	
	var ul = $('#sidebar > ul');
	
	$('#sidebar > a').click(function(e)
	{
		e.preventDefault();
		var sidebar = $('#sidebar');
		if(sidebar.hasClass('open'))
		{
			sidebar.removeClass('open');
			ul.slideUp(250);
		} else 
		{
			sidebar.addClass('open');
			ul.slideDown(250);
		}
	});
	
	// === Resize window related === //
	$(window).resize(function()
	{
		if($(window).width() > 479)
		{
			ul.css({'display':'block'});	
			$('#content-header .btn-group').css({width:'auto'});		
		}
		if($(window).width() < 479)
		{
			ul.css({'display':'none'});
			fix_position();
		}
		if($(window).width() > 768)
		{
			$('#user-nav > ul').css({width:'auto',margin:'0'});
            $('#content-header .btn-group').css({width:'auto'});
		}
	});
	
	if($(window).width() < 468)
	{
		ul.css({'display':'none'});
		fix_position();
	}
	
	if($(window).width() > 479)
	{
	   $('#content-header .btn-group').css({width:'auto'});
		ul.css({'display':'block'});
	}
	
	// === Tooltips === //
	$('.tip').tooltip();	
	$('.tip-left').tooltip({ placement: 'left' });	
	$('.tip-right').tooltip({ placement: 'right' });	
	$('.tip-top').tooltip({ placement: 'top' });	
	$('.tip-bottom').tooltip({ placement: 'bottom' });	
	
	// === Search input typeahead === //
	$('#search input[type=text]').typeahead({
		source: ['Dashboard','Form elements','Common Elements','Validation','Wizard','Buttons','Icons','Interface elements','Support','Calendar','Gallery','Reports','Charts','Graphs','Widgets'],
		items: 4
	});
	
	// === Fixes the position of buttons group in content header and top user navigation === //
	function fix_position()
	{
		var uwidth = $('#user-nav > ul').width();
		$('#user-nav > ul').css({width:uwidth,'margin-left':'-' + uwidth / 2 + 'px'});
        
        var cwidth = $('#content-header .btn-group').width();
        $('#content-header .btn-group').css({width:cwidth,'margin-left':'-' + uwidth / 2 + 'px'});
	}
	
	// === Style switcher === //
	$('#style-switcher i').click(function()
	{
		if($(this).hasClass('open'))
		{
			$(this).parent().animate({marginRight:'-=190'});
			$(this).removeClass('open');
		} else 
		{
			$(this).parent().animate({marginRight:'+=190'});
			$(this).addClass('open');
		}
		$(this).toggleClass('icon-arrow-left');
		$(this).toggleClass('icon-arrow-right');
	});
	
	$('#style-switcher a').click(function()
	{
		var style = $(this).attr('href').replace('#','');
		$('.skin-color').attr('href','css/maruti.'+style+'.css');
		$(this).siblings('a').css({'border-color':'transparent'});
		$(this).css({'border-color':'#aaaaaa'});
	});
	
	$('.lightbox_trigger').click(function(e) {
		
		e.preventDefault();
		
		var image_href = $(this).attr("href");
		
		if ($('#lightbox').length > 0) {
			
			$('#imgbox').html('<img src="' + image_href + '" /><p><i class="icon-remove icon-white"></i></p>');
		   	
			$('#lightbox').slideDown(500);
		}
		
		else { 
			var lightbox = 
			'<div id="lightbox" style="display:none;">' +
				'<div id="imgbox"><img src="' + image_href +'" />' + 
					'<p><i class="icon-remove icon-white"></i></p>' +
				'</div>' +	
			'</div>';
				
			$('body').append(lightbox);
			$('#lightbox').slideDown(500);
		}
		
	});
	

	$('#lightbox').live('click', function() { 
		$('#lightbox').hide(200);
	});


    $(".btn-delete").click(function(){
        if(confirm("确定要删除此条目？"))
        {
            return ture;
        }
        else
        {
            return false;
        }
    });

});

