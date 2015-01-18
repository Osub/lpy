/**
 * Main Javascript of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.3
 * @date      2015.1.9
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

//Smooth Scroll
function tin_smooth_scroll() {
    var defaultOptions = {
        frameRate        : 150, // [Hz]
        animationTime    : 800, // [px]
        stepSize         : 120, // [px]
        pulseAlgorithm   : true,
        pulseScale       : 8,
        pulseNormalize   : 1,
        accelerationDelta : 20,  // 20
        accelerationMax   : 1,   // 1
        keyboardSupport   : true,  // option
        arrowScroll       : 50,     // [px]
        touchpadSupport   : true,
        fixedBackground   : true,
        excluded          : ""
    };
    var options = defaultOptions;
    var isExcluded = false;
    var isFrame = false;
    var direction = { x: 0, y: 0 };
    var initDone  = false;
    var root = document.documentElement;
    var activeElement;
    var observer;
    var deltaBuffer = [ 120, 120, 120 ];
    var key = { left: 37, up: 38, right: 39, down: 40, spacebar: 32,
        pageup: 33, pagedown: 34, end: 35, home: 36 };
    if(typeof(chrome) !== 'undefined' && typeof(chrome.storage) !== 'undefined') {
        chrome.storage.sync.get(defaultOptions, function (syncedOptions) {
            options = syncedOptions;
            initTest();
        });
    }
    function initTest() {
        var disableKeyboard = false;
        if (document.URL.indexOf("google.com/reader/view") > -1) {
            disableKeyboard = true;
        }
        if (options.excluded) {
            var domains = options.excluded.split(/[,\n] ?/);
            domains.push("mail.google.com"); // exclude Gmail for now
            for (var i = domains.length; i--;) {
                if (document.URL.indexOf(domains[i]) > -1) {
                    observer && observer.disconnect();
                    removeEvent("mousewheel", wheel);
                    disableKeyboard = true;
                    isExcluded = true;
                    break;
                }
            }
        }
        if (disableKeyboard) {
            removeEvent("keydown", keydown);
        }
        if (options.keyboardSupport && !disableKeyboard) {
            addEvent("keydown", keydown);
        }
    }
    function init() {
        if (!document.body) return;
        var body = document.body;
        var html = document.documentElement;
        var windowHeight = window.innerHeight;
        var scrollHeight = body.scrollHeight;
        root = (document.compatMode.indexOf('CSS') >= 0) ? html : body;
        activeElement = body;
        initTest();
        initDone = true;
        if (top != self) {
            isFrame = true;
        }
        else if (scrollHeight > windowHeight &&
            (body.offsetHeight <= windowHeight ||
                html.offsetHeight <= windowHeight)) {
            var pending = false;
            var refresh = function () {
                if (!pending && html.scrollHeight != document.height) {
                    pending = true; // add a new pending action
                    setTimeout(function () {
                        html.style.height = document.height + 'px';
                        pending = false;
                    }, 500); // act rarely to stay fast
                }
            };
            html.style.height = 'auto';
            setTimeout(refresh, 10);
            var config = {
                attributes: true,
                childList: true,
                characterData: false
            };
            observer = new MutationObserver(refresh);
            observer.observe(body, config);
            if (root.offsetHeight <= windowHeight) {
                var underlay = document.createElement("div");
                underlay.style.clear = "both";
                body.appendChild(underlay);
            }
        }
        if (document.URL.indexOf("mail.google.com") > -1) {
            var s = document.createElement("style");
            s.innerHTML = ".iu { visibility: hidden }";
            (document.getElementsByTagName("head")[0] || html).appendChild(s);
        }
        else if (document.URL.indexOf("www.facebook.com") > -1) {
            var home_stream = document.getElementById("home_stream");
            home_stream && (home_stream.style.webkitTransform = "translateZ(0)");
        }
        if (!options.fixedBackground && !isExcluded) {
            body.style.backgroundAttachment = "scroll";
            html.style.backgroundAttachment = "scroll";
        }
    }
    var que = [];
    var pending = false;
    var lastScroll = +new Date;
    function scrollArray(elem, left, top, delay) {
        delay || (delay = 1000);
        directionCheck(left, top);
        if (options.accelerationMax != 1) {
            var now = +new Date;
            var elapsed = now - lastScroll;
            if (elapsed < options.accelerationDelta) {
                var factor = (1 + (30 / elapsed)) / 2;
                if (factor > 1) {
                    factor = Math.min(factor, options.accelerationMax);
                    left *= factor;
                    top  *= factor;
                }
            }
            lastScroll = +new Date;
        }
        que.push({
            x: left,
            y: top,
            lastX: (left < 0) ? 0.99 : -0.99,
            lastY: (top  < 0) ? 0.99 : -0.99,
            start: +new Date
        });
        if (pending) {
            return;
        }
        var scrollWindow = (elem === document.body);
        var step = function (time) {
            var now = +new Date;
            var scrollX = 0;
            var scrollY = 0;
            for (var i = 0; i < que.length; i++) {
                var item = que[i];
                var elapsed  = now - item.start;
                var finished = (elapsed >= options.animationTime);
                var position = (finished) ? 1 : elapsed / options.animationTime;
                if (options.pulseAlgorithm) {
                    position = pulse(position);
                }
                var x = (item.x * position - item.lastX) >> 0;
                var y = (item.y * position - item.lastY) >> 0;
                scrollX += x;
                scrollY += y;
                item.lastX += x;
                item.lastY += y;
                if (finished) {
                    que.splice(i, 1); i--;
                }
            }
            if (scrollWindow) {
                window.scrollBy(scrollX, scrollY);
            }
            else {
                if (scrollX) elem.scrollLeft += scrollX;
                if (scrollY) elem.scrollTop  += scrollY;
            }
            if (!left && !top) {
                que = [];
            }
            if (que.length) {
                requestFrame(step, elem, (delay / options.frameRate + 1));
            } else {
                pending = false;
            }
        };
        requestFrame(step, elem, 0);
        pending = true;
    }
    function wheel(event) {
        if (!initDone) {
            init();
        }
        var target = event.target;
        var overflowing = overflowingAncestor(target);
        if (!overflowing || event.defaultPrevented ||
            isNodeName(activeElement, "embed") ||
            (isNodeName(target, "embed") && /\.pdf/i.test(target.src))) {
            return true;
        }
        var deltaX = event.wheelDeltaX || 0;
        var deltaY = event.wheelDeltaY || 0;
        if (!deltaX && !deltaY) {
            deltaY = event.wheelDelta || 0;
        }
        if (!options.touchpadSupport && isTouchpad(deltaY)) {
            return true;
        }
        if (Math.abs(deltaX) > 1.2) {
            deltaX *= options.stepSize / 120;
        }
        if (Math.abs(deltaY) > 1.2) {
            deltaY *= options.stepSize / 120;
        }
        scrollArray(overflowing, -deltaX, -deltaY);
        event.preventDefault();
    }
    function keydown(event) {
        var target   = event.target;
        var modifier = event.ctrlKey || event.altKey || event.metaKey ||
            (event.shiftKey && event.keyCode !== key.spacebar);
        if ( /input|textarea|select|embed/i.test(target.nodeName) ||
            target.isContentEditable ||
            event.defaultPrevented   ||
            modifier ) {
            return true;
        }
        if (isNodeName(target, "button") &&
            event.keyCode === key.spacebar) {
            return true;
        }
        var shift, x = 0, y = 0;
        var elem = overflowingAncestor(activeElement);
        var clientHeight = elem.clientHeight;
        if (elem == document.body) {
            clientHeight = window.innerHeight;
        }
        switch (event.keyCode) {
            case key.up:
                y = -options.arrowScroll;
                break;
            case key.down:
                y = options.arrowScroll;
                break;
            case key.spacebar: // (+ shift)
                shift = event.shiftKey ? 1 : -1;
                y = -shift * clientHeight * 0.9;
                break;
            case key.pageup:
                y = -clientHeight * 0.9;
                break;
            case key.pagedown:
                y = clientHeight * 0.9;
                break;
            case key.home:
                y = -elem.scrollTop;
                break;
            case key.end:
                var damt = elem.scrollHeight - elem.scrollTop - clientHeight;
                y = (damt > 0) ? damt+10 : 0;
                break;
            case key.left:
                x = -options.arrowScroll;
                break;
            case key.right:
                x = options.arrowScroll;
                break;
            default:
                return true; // a key we don't care about
        }
        scrollArray(elem, x, y);
        event.preventDefault();
    }
    function mousedown(event) {
        activeElement = event.target;
    }
    var cache = {}; // cleared out every once in while
    setInterval(function () { cache = {}; }, 10 * 1000);
    var uniqueID = (function () {
        var i = 0;
        return function (el) {
            return el.uniqueID || (el.uniqueID = i++);
        };
    })();
    function setCache(elems, overflowing) {
        for (var i = elems.length; i--;)
            cache[uniqueID(elems[i])] = overflowing;
        return overflowing;
    }
    function overflowingAncestor(el) {
        var elems = [];
        var rootScrollHeight = root.scrollHeight;
        do {
            var cached = cache[uniqueID(el)];
            if (cached) {
                return setCache(elems, cached);
            }
            elems.push(el);
            if (rootScrollHeight === el.scrollHeight) {
                if (!isFrame || root.clientHeight + 10 < rootScrollHeight) {
                    return setCache(elems, document.body); // scrolling root in WebKit
                }
            } else if (el.clientHeight + 10 < el.scrollHeight) {
                overflow = getComputedStyle(el, "").getPropertyValue("overflow-y");
                if (overflow === "scroll" || overflow === "auto") {
                    return setCache(elems, el);
                }
            }
        } while (el = el.parentNode);
    }
    function addEvent(type, fn, bubble) {
        window.addEventListener(type, fn, (bubble||false));
    }
    function removeEvent(type, fn, bubble) {
        window.removeEventListener(type, fn, (bubble||false));
    }
    function isNodeName(el, tag) {
        return (el.nodeName||"").toLowerCase() === tag.toLowerCase();
    }
    function directionCheck(x, y) {
        x = (x > 0) ? 1 : -1;
        y = (y > 0) ? 1 : -1;
        if (direction.x !== x || direction.y !== y) {
            direction.x = x;
            direction.y = y;
            que = [];
            lastScroll = 0;
        }
    }
    var deltaBufferTimer;
    function isTouchpad(deltaY) {
        if (!deltaY) return;
        deltaY = Math.abs(deltaY)
        deltaBuffer.push(deltaY);
        deltaBuffer.shift();
        clearTimeout(deltaBufferTimer);
        deltaBufferTimer = setTimeout(function () {
            chrome.storage.local.set({ deltaBuffer: deltaBuffer });
        }, 1000);
        var allEquals    = (deltaBuffer[0] == deltaBuffer[1] &&
            deltaBuffer[1] == deltaBuffer[2]);
        var allDivisable = (isDivisible(deltaBuffer[0], 120) &&
            isDivisible(deltaBuffer[1], 120) &&
            isDivisible(deltaBuffer[2], 120));
        return !(allEquals || allDivisable);
    }
    function isDivisible(n, divisor) {
        return (Math.floor(n / divisor) == n / divisor);
    }
    if(typeof(chrome) !== 'undefined' && typeof(chrome.storage) !== 'undefined') {
        chrome.storage.local.get('deltaBuffer', function (stored) {
            if (stored.deltaBuffer) {
                deltaBuffer = stored.deltaBuffer;
            }
        });
    }
    var requestFrame = (function () {
        return  window.requestAnimationFrame       ||
            window.webkitRequestAnimationFrame ||
            function (callback, element, delay) {
                window.setTimeout(callback, delay || (1000/60));
            };
    })();
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
    function pulse_(x) {
        var val, start, expx;
        // test
        x = x * options.pulseScale;
        if (x < 1) { // acceleartion
            val = x - (1 - Math.exp(-x));
        } else {     // tail
            // the previous animation ended here:
            start = Math.exp(-1);
            // simple viscous drag
            x -= 1;
            expx = 1 - Math.exp(-x);
            val = start + (expx * (1 - start));
        }
        return val * options.pulseNormalize;
    }
    function pulse(x) {
        if (x >= 1) return 1;
        if (x <= 0) return 0;

        if (options.pulseNormalize == 1) {
            options.pulseNormalize /= pulse_(1);
        }
        return pulse_(x);
    }
    addEvent("mousedown", mousedown);
    addEvent("mousewheel", wheel);
    addEvent("load", init);
} //end smooth scroll
var tin_detect = new function () {
    this.is_ie8 = false;
    this.is_ie9 = false;
    this.is_ie10 = false;
    this.is_ie11 = false;
    this.is_ie = false;
    this.is_safary = false;
    this.is_chrome = false;
    this.is_ipad = false;
    this.is_touch_device = false;
    this.has_history = false;
    this.is_phone_screen = false;
    this.is_ios = false;
    this.is_touch_device = !!('ontouchstart' in window);
    this.is_mobile_device = false;
    if (jQuery('html').is('.ie8')) {
        this.is_ie8 = true;
        this.is_ie = true;
    }
    if (jQuery('html').is('.ie9')) {
        this.is_ie9 = true;
        this.is_ie = true;
    }
    if(navigator.userAgent.indexOf("MSIE 10.0") > -1){
        jQuery("html").addClass("ie10");
        this.is_ie10 = true;
        this.is_ie = true;
    }
    if(!!navigator.userAgent.match(/Trident.*rv\:11\./)){
        jQuery("html").addClass("ie11");
        this.is_ie11 = true;
     }
    if (window.history && window.history.pushState) {
        this.has_history = true;
    }
    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
        this.is_safary = true;
    }
    this.is_chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase());
    this.is_ipad = navigator.userAgent.match(/iPad/i) != null;
    this.is_ios = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        this.is_mobile_device = true;
    }
    this.run_is_phone_screen = function () {
        if ((jQuery(window).width() < 768 || jQuery(window).height() < 768) && this.is_ipad === false) {
            this.is_phone_screen = true;
        } else {
            this.is_phone_screen = false;
        }
    }
    this.run_is_phone_screen();
};

//Tooltip
 $(function(){$(".tooltip-trigger").each(function(b){
 	if(this.title){
 		var c=this.title;
 		var a=5; 
 		$(this).mouseover(function(d){
 			this.title="";
 			$(this).append('<div class="tooltip top" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">'+c+'</div></div>');
 			$(".tooltip").css({left:(($(this).width()-$('.tooltip').width())/2)+"px",bottom:($(this).height()+a)+"px"}).addClass('in').fadeIn(250)
 		}).mouseout(function(){this.title=c;$(".tooltip").remove()});
 	}
 })});

// Content Index
$(document).on("click","#content-index-control",
  function(){
    if($('#content-index-control').hasClass('open')){
      $('#content-index-control').html('[展开]');
      $('#content-index-control').removeClass('open');
      $('#index-ul').css('display','none');
    }else{
      $('#content-index-control').html('[收起]');
      $('#content-index-control').addClass('open');
      $('#index-ul').css('display','block');
    }
  })

//Scroll to top
$(function(){
  showScroll();
  function showScroll(){
    $(window).scroll( function() { 
      var scrollValue=$(window).scrollTop();
	  if($('.floatwidget').length>0) {
        var fwbo = $('.floatwidget').offset(),
	       fwh = $('.floatwidget').height(),
           wdis = fwbo.top + fwh + 60;
        var mbh = $('#main-wrap').height(),
            mbo = $('#main-wrap').offset(),
            mb = mbo.top + mbh;
           maxh = fwh + scrollValue+110;
           if(scrollValue > wdis){
                if($('.floatwidget-container').html()==''){
                    $('.floatwidget').clone().prependTo($('.floatwidget-container'));
                }
                $('.floatwidget-container').fadeIn('slow');
                if(maxh > mb){
                  var newtop = mb-maxh+80;
                  $('.floatwidget-container').css('top',newtop);  
                }else{
                  $('.floatwidget-container').css('top',80);  
                }
            }else{
                $('.floatwidget-container').html('').fadeOut('slow');
            }
       }
      scrollValue > 60 ? $('div[id=nav-scroll]').css('box-shadow:','0 5px 5px rgba(0,0,0,0.25)'):$('div[id=nav-scroll]').css('box-shadow:','none');
      scrollValue > 200 ? $('span[id=back-to-top]').fadeIn('slow'):$('span[id=back-to-top]').fadeOut('slow');
      //fixnav
      (scrollValue > 60 && screen.width>640) ? $('#nav-scroll').addClass('tofix'):$('#nav-scroll').removeClass('tofix');

    } );  
    $('#back-to-top').click(function(){
       $("html,body").animate({scrollTop:0},1000);  
    }); 
  }
})

/*  Tabs widget
/* ------------------------------------ */	
$(function() {
		var $tabsNav       = $('.tin-tabs-nav'),
			$tabsNavLis    = $tabsNav.children('li'),
			$tabsContainer = $('.tin-tabs-container');

		$tabsNav.each(function() {
			var $this = $(this);
			$this.next().children('.tin-tab').stop(true,true).hide()
			.siblings( $this.find('a').attr('href') ).show();
			$this.children('li').first().addClass('active').stop(true,true).show();
		});

		$tabsNavLis.on('mouseover', function(e) {
			var $this = $(this);
			if($this.hasClass('active')) return;
			$this.siblings().removeClass('active').end()
			.addClass('active');
			
			$this.parent().next().children('.tin-tab').stop(true,true).hide()
			.siblings( $this.find('a').attr('href') ).fadeIn();
			e.preventDefault();
		}).children( window.location.hash ? 'a[href=' + window.location.hash + ']' : 'a:first' ).trigger('click');
		$tabsNavLis.on('click', function(e) {
			e.preventDefault();
		})

})

// 点击喜欢
$(".like-btn").click(function(){
      var _this = $(this);
      var pid = _this.attr('pid');
      if(_this.hasClass('love-yes')) return;
	  $.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=like&pid=' + pid, cache: false, success: function(){var num = _this.children("span").text();_this.children("span").text(Number(num)+1);_this.addClass("love-animate").attr("title","已喜欢");setTimeout(function(){_this.removeClass('love-animate').addClass('love-yes');},500);}});
});

//点击收藏或取消收藏
$('.collect').click(function(){
	var _this = $(this);
	var pid = Number(_this.attr('pid')); 
	if(_this.attr('uid')&&_this.hasClass('collect-no')){
		var uid = Number(_this.attr('uid'));
		$.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=collect&uid=' + uid + '&pid=' + pid + '&act=add', cache: false,success: function(){_this.children("span").text("已收藏");_this.addClass("collect-animate").attr("title","已收藏");setTimeout(function(){_this.removeClass('collect-animate').removeClass('collect-no').addClass('collect-yes');},500);}});		
		return false;
	}else if(_this.attr('uid')&&_this.hasClass('remove-collect')){
		var uid = Number(_this.attr('uid'));
		$.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=collect&uid=' + uid + '&pid=' + pid + '&act=remove', cache: false,success: function(){_this.children("span").text("点击收藏");_this.addClass("collect-animate").attr("title","点击收藏");setTimeout(function(){_this.removeClass('collect-animate').removeClass('remove-collect').removeClass('collect-yes').addClass('collect-no');},500);}});
		return false;
	}else{
		return;
	}   	
})

// 微信二维码浮入
var weixinTimer = null;
$('.weixin-btn').hover(function(){
  clearTimeout(weixinTimer);
  $('#weixin-qt').css('display','block').stop().animate({
    top : 40 ,
    opacity : 1 
  },500);
},function(){
  weixinTimer = setTimeout(function(){
     $('#weixin-qt').fadeOut(100,function(){
      $(this).css('top',80);
    });
  },100);
 
});

var asweixinTimer = null;
$('.as-weixin').hover(function(){
  clearTimeout(asweixinTimer);
  $('#as-weixin-qr').css('display','block').stop().animate({
    bottom : 30 ,
    opacity : 1 
  },500);
},function(){
  asweixinTimer = setTimeout(function(){
     $('#as-weixin-qr').fadeOut(100,function(){
      $(this).css('bottom',60);
    });
  },100);
 
});

var floatbtnqrTimer = null;
$('#qr').hover(function(){
  clearTimeout(floatbtnqrTimer);
  $('#floatbtn-qr').css('display','block').stop().animate({
    left : -140 ,
    opacity : 1 
  },500);
},function(){
  floatbtnqrTimer = setTimeout(function(){
     $('#floatbtn-qr').fadeOut(100,function(){
      $(this).css('left',-180);
    });
  },100);
 
});

//首页布局切换
$('#layoutswt').click(function(){
	if($('#layoutswt i').hasClass('is_blog')){
		window.location.href = $('#layoutswt i').attr('src') + '?layout=cms';
	}else if($('#layoutswt i').hasClass('is_cms')){
		window.location.href = $('#layoutswt i').attr('src') + '?layout=blocks';
	}else{
		window.location.href = $('#layoutswt i').attr('src') + '?layout=blog';
	}
})
//头像旋转
$("#main-wrap img.avatar").mouseover(function(){
	$(this).addClass("avatar-rotate");
});

$("#main-wrap img.avatar").mouseout(function(){
	$(this).removeClass("avatar-rotate");
});

// 清理百度分享多余代码
window.onload=function(){
  $('#bdshare_s').html('');
};

// 评论框快速代码标签
/* comment editor
-----------------------------------------------*/
$(function() {
    function addEditor(a, b, c) {
        if (document.selection) {
            a.focus();
            sel = document.selection.createRange();
            c ? sel.text = b + sel.text + c: sel.text = b;
            a.focus()
        } else if (a.selectionStart || a.selectionStart == '0') {
            var d = a.selectionStart;
            var e = a.selectionEnd;
            var f = e;
            c ? a.value = a.value.substring(0, d) + b + a.value.substring(d, e) + c + a.value.substring(e, a.value.length) : a.value = a.value.substring(0, d) + b + a.value.substring(e, a.value.length);
            c ? f += b.length + c.length: f += b.length - e + d;
            if (d == e && c) f -= c.length;
            a.focus();
            a.selectionStart = f;
            a.selectionEnd = f
        } else {
            a.value += b + c;
            a.focus()
        }
    }
    var g = document.getElementById('comment') || 0;
    var h = {
        strong: function() {
            addEditor(g, '<strong>', '</strong>')
        },
        em: function() {
            addEditor(g, '<em>', '</em>')
        },
        del: function() {
            addEditor(g, '<del>', '</del>')
        },
        underline: function() {
            addEditor(g, '<u>', '</u>')
        },
        quote: function() {
            addEditor(g, '<blockquote>', '</blockquote>')
        },
        private: function() {
            addEditor(g, '[private]','[/private]')
        },
        ahref: function() {
            var a = prompt('请输入链接地址', 'http://');
            var b = prompt('请输入链接描述','');
            if (a) {
                addEditor(g, '<a target="_blank" href=”' + a + '" rel="external”>' + b + '</a>','')
            }
        },
        img: function() {
            var a = prompt('请输入图片地址', 'http://');
            if (a) {
                addEditor(g, '<img src="' + a + '" alt="" />','')
            }
        },
        code: function() {
            addEditor(g, '<code>', '</code>')
        },
        php: function() {
            addEditor(g, '<pre class="lang:php decode:true " >', '</pre>')
        },
        js: function() {
            addEditor(g, '<pre class="lang:js decode:true " >', '</pre>')
        },
        css: function() {
            addEditor(g, '<pre class="lang:css decode:true " >', '</pre>')
        }
    };
    window['SIMPALED'] = {};
    window['SIMPALED']['Editor'] = h
});

//Pirobox lightbox
$(function() {
  $().piroBox({
      my_speed: 400, //animation speed
      bg_alpha: 0.4, //background opacity
      slideShow : true, // true == slideshow on, false == slideshow off
      slideSpeed : 4, //slideshow duration in seconds(3 to 6 Recommended)
      close_all : '.piro_close,.piro_overlay'// add class .piro_overlay(with comma)if you want overlay click close piroBox
  });
});

//Rating
$('.stars').mouseover(function(){
	if($('.rates').hasClass('rated')){
		return;
	}else{
	$('.stars').children('i').removeClass('fa-star').addClass('fa-star-o');
	$(this).prevAll().children('i').removeClass('fa-star-o').addClass('fa-star');
	$(this).children('i').removeClass('fa-star-o').addClass('fa-star');	
}});
$('.stars').click(function(){
	if($('.rates').hasClass('rated')){
		return;
	}else{
	$('.rates').addClass('rated');
	var sid = $(this).attr('id');
	var pid = $('.rates').attr('pid');
	var tt = $(this).attr('times');
	tt = Number(tt)+1;
	$(this).attr('times',tt);
	var t1 = $('#starone').attr('times');
	var t2 = $('#startwo').attr('times');
	var t3 = $('#starthree').attr('times');
	var t4 = $('#starfour').attr('times');
	var t5 = $('#starfive').attr('times');
	var alltimes = Number(t1)+Number(t2)+Number(t3)+Number(t4)+Number(t5);
	var allscore = Number(t1)*1+Number(t2)*2+Number(t3)*3+Number(t4)*4+Number(t5)*5;
	ra = allscore/alltimes;
	ra = ra.toFixed(1);
	function refresh_star(ra){
		allt = $('.ratingCount').html();
		allt = Number(allt) + 1;
		$('.ratingCount').html(allt);
		$('.ratingValue').html(ra);
	}
	jQuery.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=rating&sid=' + sid + '&pid=' + pid, cache: false, success:refresh_star(ra)});
}});
$('.stars').mouseout(function(){
function back_star(id){
	if($(id).attr('solid')=='y'){
		$(id).children().attr('class','fa fa-star');
	}else{
		$(id).children().attr('class','fa fa-star-o');
	}
};
	if($('.rates').hasClass('rated')){
		return;
	}else{
		back_star('#starone');back_star('#startwo');back_star('#starthree');back_star('#starfour');back_star('#starfive');
	}
});

//Send mail message
var errTimer = null;
$('#submit-mail').click(function(){
	$('.err').hide();
	name = $('#t-name').val();
	mail = $('#t-email').val();
	comment = $('#t-comment').val();
	num1 = $('input#t-num1').val();
	num2 = $('input#t-num2').val();
	sum = $('input#captcha2').val();
	clearTimeout(errTimer);
	if(name==''){
		$('.err').html('姓名不能为空').slideToggle(1000);
		$("input#t-name").focus();
		errTimer = setTimeout(function(){
			$('.err').slideToggle(1000);
		},3000);
		return false;
	}
	if(mail==''){
		$('.err').html('邮箱地址不能为空').slideToggle(1000);
		$("input#t-email").focus();
		errTimer = setTimeout(function(){
			$('.err').slideToggle(1000);
		},3000);
		return false;
	}
	if(!mail.match('^([a-zA-Z0-9_-])+((\.)?([a-zA-Z0-9_-])+)+@([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)+$')){
		$('.err').html('邮箱地址格式不正确').slideToggle(1000);
		$("input#t-email").focus();
		errTimer = setTimeout(function(){
			$('.err').slideToggle(1000);
		},3000);
		return false;
	}
	if(sum==''){
		$('.err').html('验证码不能为空').slideToggle(1000);
		$("input#captcha2").focus();
		errTimer = setTimeout(function(){
			$('.err').slideToggle(1000);
		},3000);
		return false;
	}
	if(Number(num1)+Number(num2)!=Number(sum)){
		$('.err').html('验证码不正确').slideToggle(1000);
		$("input#captcha2").focus();
		errTimer = setTimeout(function(){
			$('.err').slideToggle(1000);
		},3000);
		return false;
	}
	if(comment==''){
		$('.err').html('消息内容不能为空').slideToggle(1000);
		$("textarea#t-comment").focus();
		errTimer = setTimeout(function(){
			$('.err').slideToggle(1000);
		},3000);
		return false;
	}
	$('input#submit-mail').css({'color':'#fff','background':'#1cbdc5'}).val('正在发送');
 	$.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=message&tm=' + mail + '&tn=' + name + '&tc=' + comment, cache: false,success: mail_success()});
	function mail_success(){
		errTimer = setTimeout(function(){
			$('#mailmessage').html('<p class="mail-success"><i class="fa fa-check"></i>消息发送成功,你也可以扫描下方微信二维码并随时与我联系.</p><div><img src="'+tin.tin_url+'/images/weixin.png"></div>');
		},2000)
	}
    return false;
})

//mail send download link
var dlmsgTimer = null;
$('.mail-dl-btn').click(function(){
	$('.dl-msg').hide();
	mail = $('.mail-dl').val();
	pid = $('.dl-mail').attr('pid');
	clearTimeout(dlmsgTimer);	
	if(mail==''){
		$('.dl-msg').html('邮箱地址不能为空').slideToggle(1000);
		$("input.mail-dl").focus();
		dlmsgTimer = setTimeout(function(){
			$('.dl-msg').slideToggle(1000);
		},3000);
		return false;
	}
	if(!mail.match('^([a-zA-Z0-9_-])+((\.)?([a-zA-Z0-9_-])+)+@([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)+$')){
		$('.dl-msg').html('邮箱地址格式不正确').slideToggle(1000);
		$("input.mail-dl").focus();
		dlmsgTimer = setTimeout(function(){
			$('.dl-msg').slideToggle(1000);
		},3000);
		return false;
	}
	if(!document.getElementById('dl-terms-chk').checked){
		$('.dl-msg').html('你必须同意勾选下载条款').slideToggle(1000);
		dlmsgTimer = setTimeout(function(){
			$('.dl-msg').slideToggle(1000);
		},3000);
		return false;
	}
 	$.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=maildownload&mail=' + mail + '&pid=' + pid, cache: false,success: dlmail_success()});
	function dlmail_success(){
		dlmsgTimer = setTimeout(function(){
			$('.single-download').html('<span><p class="mail-dl-success"><i class="fa fa-check"></i>请求发送成功,请稍候检查你的收件箱.如果长时间未收到,请检查垃圾箱或者直接通过下方网站下方消息框发送包含该文章链接的邮件消息给我，我会及时处理回复.你也可以扫描下方微信二维码并随时与我联系.</p></span><div><img src="'+tin.tin_url+'/images/weixin.png"></div>');
			$.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=whodownload&mail=' + mail + '&pid=' + pid, cache: false});
		},2000);       
	}
    return false;	
})

// Download times
$('.downldlink').click(function(){
    metakey = $(this).attr('id');
    pid = $('.downldlinks-inner').attr('pid');
    $.ajax({type: 'POST', xhrFields: {withCredentials: true}, dataType: 'html', url: tin.ajax_url, data: 'action=downldtimes&key=' + metakey + '&pid=' + pid, cache: false});
})

// Buy resources
$('.buysaledl').click(function(){
    sid = $(this).attr('id');
    pid = $('.downldlinks-inner').attr('pid');
    uid = $('.downldlinks-inner').attr('uid');
    var login_in = 0;
    $.ajax({type: 'POST', dataType: 'json', url: tin.ajax_url, data: 'action=checklogin', cache: false, success: function(check){login_in = check.status;
        if (login_in!=0){
            $.ajax({type: 'POST', dataType: 'json', url: tin.ajax_url, data: 'action=popsaledl&sid=' + sid + '&pid=' + pid + '&uid=' + uid, cache: false, success: function(msg){
                $('.buy-pop-out').fadeIn('fast');
                $('.confirm-buy').html('<button class="cancel-to-back btn btn-warning">取消</button>');
                $(".cancel-to-back").click(function(){$('.buy-pop-out').fadeOut();})
                $('.dl-price').html(msg.price);$('.all-credits').html(msg.credit);
                enough = Number(msg.enough);
                if(enough==0){$('.saledl-msg').html('抱歉，你当前的积分不足以支付该资源！ <a href="http://www.zhiyanblog.com/how-to-earn-credits.html" title="赚取积分" target="_blank">如何赚取积分?</a>');
                }else{
					$('.saledl-msg').html('');
                    $('.confirm-buy').prepend('<button class="yes-to-buy btn btn-success" sid="' + msg.sid + '">确定</button>');
                    $('.yes-to-buy').click(Confirm_to_buy);
                }
            }});            
        }else{
            $('.buy-pop-out').fadeIn('fast');
            $('.confirm-buy').html('<button class="cancel-to-back btn btn-warning">返回</button>');
            $(".cancel-to-back").click(function(){$('.buy-pop-out').fadeOut();});
            $('.buy-des').html('<p>出错了！</p><p>Wordpress未检测到登录，可能你已从别的本站页面注销，请刷新网页并重新登录！</p>');
        }
    }});
})

// Confirm to buy
function Confirm_to_buy(){
    sid = $(this).attr('sid');
    pid = $('.downldlinks-inner').attr('pid');
    uid = $('.downldlinks-inner').attr('uid');
    $.ajax({type: 'POST', dataType: 'json', url: tin.ajax_url, data: 'action=confirmbuy&sid=' + sid + '&pid=' + pid + '&uid=' + uid, cache: false, success: function(msg){
            msg.success = Number(msg.success);
            if(msg.success==1){
                info = '<p>购买成功，已扣除你<span>' + msg.price + '</span>积分，当前你还剩余<span>' + msg.credit + '</span>积分</p><p>同时系统也通过邮件发送下载链接至你在本站资料中所保留的邮箱，以备不时之需！';
            }else if(msg.success==2){
                info = '<p>你似乎已购买过该资源，请刷新页面查看！</p><p>请不要多次点击购买，以免重复扣除积分，如果有积分错误，请站内信或通过邮件消息工具通知管理员！</p>';
            }else{
                info = '<p>购买失败，请重新再试！</p><p>如果你发现积分重复扣除或多扣等错误，请站内信或通过邮件消息工具通知管理员！</p>'
            }
            $('.buy-des').html(info);
            $('.confirm-buy').html('<button class="cancel-to-back btn btn-success">返回</button>');
            $(".cancel-to-back").click(function(){$('.buy-pop-out').fadeOut();})
            setTimeout(function(){
                $('.buy-pop-out').fadeOut('fast');
                location.reload();
            },3000);
    }});
}

// Close pop-up
$(".buy-close, .cancel-to-back").click(function(){
    $('.buy-pop-out').fadeOut();
})

// Ajax post basic
function tin_do_post(formid, posturl, postdata, contentid){
	var tinRefreshIcon = '<i class="fa fa-spinner fa-spin" style="margin-left:4px;line-height:20px;font-size:20px;font-size:2rem;"></i>';
	$(formid).find('[type="submit"]').addClass('disabled').append(tinRefreshIcon);
	$.ajax({
		type: 'POST', 
		url: posturl,
		data: postdata,
		success: function(response) {
			$(contentid).html($(response).find(contentid).html());
		},
		error: function(){
			tin_do_post(formid, posturl, postdata, contentid);
		}
	});
}

//Submit
$('#pmform').submit(function(){
	var formid = '#pmform';
	var p = $(formid);
	tin_do_post(
		formid, 
		location.href, 
		{
		'pmNonce' : p.find('[name="pmNonce"]').val(),
		'pm' : p.find('[name="pm"]').val()
		},
		'.content'
	);
	return false;
});
$('#creditform').submit(function(){
	var formid = '#creditform';
	var p = $(formid);
    var obj;
    var checked;       
    obj=document.getElementsByName('creditChange');   
    if(obj){
        for (var i = 0; i < obj.length; i++){
            if(obj[i].checked){
                checked = obj[i].getAttribute('value');
            }else{checked = 'add';}
        }      
    }else{checked = 'add';}
	tin_do_post(
		formid, 
		location.href, 
		{
		'creditNonce' : p.find('[name="creditNonce"]').val(),
		'creditChange' : checked,
		'creditNum' : p.find('[name="creditNum"]').val(),
		'creditDesc' : p.find('[name="creditDesc"]').val()
		},
		'.content'
	);
	return false;
});

// Subscribe
$('button#subscribe').click(function(){
	email = $('input#subscribe').val();
	if(email==''||(!email.match('^([a-zA-Z0-9_-])+((\.)?([a-zA-Z0-9_-])+)+@([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)+$'))){
		$('#subscribe-msg').html('请输入正确邮箱').slideToggle('slow');
		setTimeout(function(){$('#subscribe-msg').slideToggle('slow');},2000);
	}else{
		$.ajax({type: 'POST', dataType: 'json', url: tin.ajax_url, data: 'action=subscribe&email=' + email, cache: false, success:function(){
			$('#subscribe-span').html('你已成功订阅该栏目，同时你也会收到一封提醒邮件.');
		}})
	}
})
	
// Unsubscribe
$('button#unsubscribe').click(function(){
	email = $('input#unsubscribe').val();
	if(email==''||(!email.match('^([a-zA-Z0-9_-])+((\.)?([a-zA-Z0-9_-])+)+@([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)+$'))){
		$('#unsubscribe-msg').html('请输入正确邮箱').slideToggle('slow');
		setTimeout(function(){$('#unsubscribe-msg').slideToggle('slow');},2000);
	}else{
		$.ajax({type: 'POST', dataType: 'json', url: tin.ajax_url, data: 'action=unsubscribe&email=' + email, cache: false, success:function(data){
			$('#unsubscribe-span').html(data.msg);
		}})
	}
})
	
// Cookie
// function set cookie
function tinSetCookie(c_name,value,expire,path){
	var exdate=new Date();
	exdate.setTime(exdate.getTime()+expire*1000);
	document.cookie=c_name+ "=" +escape(value)+((expire==null) ? "" : ";expires="+exdate.toGMTString())+((path==null) ? "" : ";path="+path);
}
// function get cookie
function tinGetCookie(c_name){
	if (document.cookie.length>0){
		c_start=document.cookie.indexOf(c_name + "=");
		if (c_start!=-1){ 
			c_start=c_start + c_name.length+1;
			c_end=document.cookie.indexOf(";",c_start);
			if (c_end==-1) c_end=document.cookie.length;
			return unescape(document.cookie.substring(c_start,c_end));
		}
	}
	return ""
}
// function set wp nonce cookie
function set_tin_nonce(){
	$.ajax({
		type: 'POST', url: tin.ajax_url, data: { 'action' : 'tin_create_nonce' },
		success: function(response) {
			tinSetCookie('tin_check_nonce',$.trim(response),3600,tin.home);
		},
		error: function(){
			set_tin_nonce();
		}
	});
}
// var get wp nonce cookie
var wpnonce = tinGetCookie('tin_check_nonce');
// action set wp nonce cookie ( if wp nonce is null or empty )
if (wpnonce==null || wpnonce=="") set_tin_nonce();

// Ajax update traffic
function update_tin_traffic(t,p){
	$.ajax({
		type: 'POST', 
		url: tin.ajax_url, 
		data: {
			'action' : 'tin_tracker_ajax',
			'type' : t,
			'pid' : p,
			'wp_nonce' : tinGetCookie('tin_check_nonce')
		},
		success: function(response) {
			//~ @action reset wp nonce ( if response invalid ) and try again
			if($.trim(response)==='NonceIsInvalid'){
				set_tin_nonce();
				update_tin_traffic(t,p);
			}
		},
		error: function(){
			//~ @action try again ( if error )
			update_tin_traffic(t,p);
		}
	});
}

// Get promoter name in url
function tinGetQueryString(name){
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

// Header search slide
	$('.search-btn-click').bind('click',function(){
        if($(this).children('.header-search-slide').css('display')=='none'){
            $(this).css({'background':'#fafafa'});
            $(this).children('.header-search-slide').slideDown();
            $(this).children('.header-search-slide').children().children('input').focus();
        }
	})
	$('.header-search-slide').children().children('input').bind('blur',function(){
		$('.search-btn-click').css({'background':'transparent'});
		$('.header-search-slide').slideUp();
	})
	
// Toggle mobile menu
    var opened=false;
    $('.toggle-menu').bind('click',function(event){
        $('#content-container').toggleClass('push');
        $('#navmenu-mobile-wraper').toggleClass('push');
        if(opened){
            opened=false;
            setTimeout(function(){
                $('#navmenu-mobile').removeClass('push');
            },500)
        }else{
            $('#navmenu-mobile').addClass('push');
            opened=true
        }
    });
    $('#main-wrap').bind('click',function(){
        if(screen.width<=640 && opened==true){
            $('#content-container').toggleClass('push');
            $('#navmenu-mobile-wraper').toggleClass('push');
            setTimeout(function(){
                $('#navmenu-mobile').removeClass('push');
            },500);
            opened=false;
        }
    })

// Toggle sortpage menu
$('#page-sort-menu-btn a').click(function(){
	$('.pagesidebar ul').slideToggle();
})	
	
// Slide nav
	$(".menu-item-has-children").bind('mouseover',function(){
		if(screen.width>640&&!$(this).children('ul').is(":animated")) $(this).children('ul').slideDown(); 
	}).bind('mouseleave',function(){
		if(screen.width>640) $(this).children('ul').slideUp(); 
	});

	$('.login-yet-click').bind('mouseover',function(){
		if(!$(this).children('.user-tabs').is(":animated"))$(this).children('.user-tabs').slideDown();
	}).bind('mouseleave',function(){
		$(this).children('.user-tabs').slideUp();
	})

// Slide focus-us
$('#focus-us').bind('mouseover',function(){
	if(!$(this).children('#focus-slide').is(":animated"))$(this).children('#focus-slide').slideDown();
}).bind('mouseleave',function(){
	$(this).children('#focus-slide').slideUp();
})

//Toggle smiles
$('.comt-smilie').bind('click',function(){
	$('#comt-format').hide();
    $('#comt-smilie').toggle();
})
$('.comt-format').bind('click',function(){
    $('#comt-smilie').hide();
    $('#comt-format').toggle();
})

//Upload avatar
$('#edit-avatar').click(function(){
    $('#upload-input').slideToggle();
})
$('#upload-avatar').click(function(){
    var file = $('#upload-input input[type=file]').val();
    if(file==''){
        $('#upload-avatar-msg').html('请选择一个图片').slideDown();
        setTimeout(function(){$('#upload-avatar-msg').html('').slideUp();},2000);
    }else{
       document.getElementById('info-form').enctype = "multipart/form-data";
        $('form#info-form').submit();
    } 
})

// Document ready
// --------------------
// -------------------- //
$(document).ready(function(){
    //QR img
    var qcode = {
      api : "http://qr.liantu.com/api.php?text=",
      url :  window.location.href,
      exist : false,
      create : function(){
      if(!this.exist){
        var image = document.createElement('img');
        image.src = this.api + this.url;
        image.width = 120;
        this.exist = true;
        return image;
        }
      }
    };
    document.getElementById('floatbtn-qr').insertBefore(qcode.create(),document.getElementById('floatbtn-qr-msg'));

	//Begin Smooth Scroll
    if (tin_detect.is_chrome === true || tin_detect.is_ie10 || tin_detect.is_ie11) {
        tin_smooth_scroll();
	}
	
	//Toggle Content
	$('.toggle-click-btn').click(function(){
		$(this).next('.toggle-content').slideToggle('slow');
        if($(this).hasClass('yes')){
            $(this).removeClass('yes');
            $(this).addClass('no');
        }else{
            $(this).removeClass('no');
            $(this).addClass('yes');
        }
	});

    //Archves page
    (function(){
         $('#archives span.al_mon').each(function(){
             var num=$(this).next().children('li').size();
             var text=$(this).text();
             $(this).html(text+'<em> ( '+num+' 篇文章 )</em>');
         });
         var $al_post_list=$('#archives ul.al_post_list'),
             $al_post_list_f=$('#archives ul.al_post_list:first');
         $al_post_list.hide(1,function(){
             $al_post_list_f.show();
         });
         $('#archives span.al_mon').click(function(){
             $(this).next().slideToggle('slow');
             return false;
         });
         $('#al_expand_collapse').toggle(function(){
             $al_post_list.show();
         },function(){
             $al_post_list.hide();
         });
     })();

     //Title loading 
     $('h3 a').click(function(){
        myloadoriginal = this.text;
        $(this).text('请稍等，正在努力加载中...');
        var myload = this;
        setTimeout(function() { $(myload).text(myloadoriginal); }, 2000);
    });
	
	//Infobg close
	$('.infobg-close').click(function(){
		$(this).parent('.contextual').fadeOut('slow');
	})

    //Marquee site news
    function startmarquee(lh,speed,delay,index){ 
        var t; 
        var p=false; 
        var o=document.getElementById("news-scroll-zone"+index); 
        o.innerHTML+=o.innerHTML; 
        o.onmouseover=function(){p=true} 
        o.onmouseout=function(){p=false} 
        o.scrollTop = 0; 
        function start(){ 
            t=setInterval(scrolling,speed); 
            if(!p){ o.scrollTop += 1;} 
        } 
        function scrolling(){ 
            if(o.scrollTop%lh!=0){ 
            o.scrollTop += 1; 
            if(o.scrollTop>=o.scrollHeight/2)
                o.scrollTop = 0; 
            }else{ 
                clearInterval(t); 
                setTimeout(start,delay); 
            } 
        } 
        setTimeout(start,delay); 
    }
    if($('#news-scroll-zone').length>0){
        startmarquee(20,30,5000,'');
    }  
	
	//Lazyload
	$(".fancyimg img, .post-item-thumbnail img, tab-item-thumbnail img .avatar img, .newsletter-thumbnail img").lazyload({
        	placeholder:tin.tin_url+"/images/image-pending.gif",
            effect:"fadeIn"
    });
	
	// action tin affiliate url and trackback url
	$('.tin_aff_url,.trackback-url,input[name=rss]').click(function(){
		$(this).select();
	});
	$('.quick-copy-btn').click(function(){
		$(this).parent().children('input').select();
		alert('请用CTRL+C复制');
	})
	
	// action set affiliate cookie ( credit )
	if(tinGetQueryString('aff')) tinSetCookie('tin_aff',tinGetQueryString('aff'),86400,tin.home);
	
	// action update traffic
	if(!(typeof(tin.Tracker) == "undefined")) update_tin_traffic(tin.Tracker.type,tin.Tracker.pid);
	
	// go to comment
	$('.commentbtn').click(function(){$('html,body').animate({scrollTop:$('#comments').offset().top}, 800);});
	
	// loading complete
	$('.site_loading').fadeOut();

    // comments tabs
    $('.commenttitle span').click(function(e){
        $('.commenttitle span').removeClass('active');
        $(this).addClass('active');
        $('.commentlist').hide();
        $($(this).parent('a').attr('href')).fadeIn();
        e.preventDefault();
    })
	
	// stickys & latest tabs
    $('.stickys span.heading-text-cms').click(function(e){
        $('.stickys span.heading-text-cms').removeClass('active');
        $(this).addClass('active');
        $('.stickys-latest-list').hide();
        $($(this).attr('id')).fadeIn();
        e.preventDefault();
    })
	
    // Mobile nav append
    $('#menu-mobile li.menu-item-has-children').prepend('<span class="child-menu-block"></span>');
    // Mobile menu click toggle
    $('.child-menu-block').live('click',function(){
        $(this).parent().children('.sub-menu').slideToggle();
        if($(this).parent().hasClass('expand')){
            $(this).parent().removeClass('expand');
        }else{
            $(this).parent().addClass('expand');
        }
    })
	
	// href add _blank
	var titles = $('h1 a,h2 a,h3 a');
	titles.each(function(){
		$(this).attr('target','_blank');
	})
	
}); 