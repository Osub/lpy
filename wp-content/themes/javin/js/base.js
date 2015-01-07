jQuery(function($){
	
// menu
$(document).ready(function(){
  $('#nav .menu-item').mousemove(function(){
  $(this).find('ul').slideDown(100);//give it a speed
  });
  $('#nav .menu-item').mouseleave(function(){
  $(this).find('ul').slideUp(0);
  });
  $('#nav .menu-item').hover(function(){
  $(this).find('a').addClass('nav_main_hover');
  },function(){
  $(this).find('a').removeClass('nav_main_hover')
  });
});

// add weibo
$("#connect_add").css({'opacity':'.6'});
$("#load_icon").css({'opacity':'.6'});
$('#connect li').hover(function(){
$(this).find('.add_menu_list').slideDown(200);
$(this).find('#custom-loginform-box').slideDown(200);
$(this).find('.add_menu').css("opacity","1");
},function(){
$(this).find('.add_menu_list').hide(0);
$(this).find('#custom-loginform-box').hide(0);
$(this).find('.add_menu').css("opacity",".6");
});


// add weixin
$('#icons li').hover(function(){
$(this).find('.weixin_content').slideDown(200);
$(this).find('.about_icon_weixin').css("background-position","-84px -28px");
},function(){
$(this).find('.weixin_content').hide(0);
$(this).find('.about_icon_weixin').css("background-position","-84px 0px");
});

// moble-nav
$(".mobile_nav_buttons").toggle(function () {
	$(this).addClass("mobile_nav_buttons_close")
    $(".sub_title ul").fadeIn();
  },function () {
	$(this).removeClass("mobile_nav_buttons_close")
    $(".sub_title ul").fadeOut();
  }
);

// preview-post
$('.preview_post').live('click',function(){
		$(this).toggle(function(){
		$(this).parent("div").siblings(".meta_content").hide();
		$(this).parent("div").siblings(".thumb").hide();
		$(this).parent("div").siblings(".read_more").hide();
	    $(this).parent("div").siblings(".meta_content_all").fadeIn(600);
		$(this).siblings(".more_link").text("查看文章>>");
	$(this).text("向上收起");
			
  },function () {
		$(this).parent("div").siblings(".meta_content").show();
		$(this).parent("div").siblings(".thumb").show();
		$(this).parent("div").siblings(".read_more").show();
		$(this).parent("div").siblings(".meta_content_all").fadeOut(600);
		$(this).siblings(".more_link").html("<em>Read more &gt;&gt;</em>");
	$(this).text("预览全文");
	$('html, body').animate({
		scrollTop: $(this).parents(".post").offset().top - 20
				 }, 600);
			
		}).trigger('click');;
	});

// back to top
$(window).scroll(function(){
if ($(document.documentElement).scrollTop() > 0 || $(document.body).scrollTop() > 0) {
            $("#backtotop a").css("right","0px");
            $("#backtotop a").die().live("click",
            function() {
                $("body,html").animate({
                    scrollTop: 0
                },
                500)
            })
        } else {
            $("#backtotop a").css("right","-133px")
        }
});

// search
$(document).ready(function(){
    $('#search_text').focus(function(){
		$('#search_bon').addClass('search_bon_active');
		$('#search_text').css("background-position","0 -26px");
	});
	$('#search_text').blur(function(){
		$('#search_bon').removeClass('search_bon_active');
		$('#search_text').css("background-position","0 0");
	});
});

// sidebar box
$(document).ready(function(){
	// connect
	$("#connect_rss").css({'opacity':'.6'})
	$("#connect_rss").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	$("#connect_email").css({'opacity':'.6'})
	$("#connect_email").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// about
	$("#about").css({'opacity':'.6'})
	$("#about").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// hot
	$("#hot ul li").css({'opacity':'.6'})
	$("#hot ul li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// feat
	$("#feat ul li").css({'opacity':'.6'})
	$("#feat ul li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// new
	$("#new ul li").css({'opacity':'.6'})
	$("#new ul li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// comment
	$("#rcslider li").css({'opacity':'.6'})
	$("#rcslider li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// tag
	$(".tagcloud ul li a").css({'opacity':'.6'})
	$(".tagcloud ul li a").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// category
	$("#same_category ul li").css({'opacity':'.6'})
	$("#same_category ul li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// link
	$("#link li").css({'opacity':'.6'})
	$("#link li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// login
	$("#log_register li").css({'opacity':'.6'})
	$("#log_register li").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// footer
	$(".footer").css({'opacity':'.6'})
	$(".footer").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	// back to top
	$("#backtotop a").css({'opacity':'.6'})
	$("#backtotop a").hover(
	    function() {
	       $(this).stop().fadeTo(300, 1);
	    },
	    function() {
	       $(this).stop().fadeTo(300, .6);
	    }
	);
	
});

// submit disabled
$('#commentform').submit(function(){
	$('#submit').attr("disabled",true).css("cursor","not-allowed").fadeTo('slow', 0.4);
});

// author back
$(document).ready(function() {
if($('#author').val()!=""){
$("#author_info").css('display','none');
var change='<span id="show_author_info" style="cursor: pointer; color:#39f;">修改 &raquo;</span>';
var close='<span id="hide_author_info" style="cursor: pointer;color:#39f;">完成 &raquo;</span>';
$('#welcome').append(change);
$('#welcome').append(close);
$('#hide_author_info').css('display','none');
$('#show_author_info').click(function() {
$('#author_info').slideDown('slow');
$('#show_author_info').css('display','none');
$('#hide_author_info').css('display','inline');
$('#hide_author_info').click(function() {
$('#author_info').slideUp('slow');
$('#hide_author_info').css('display','none');
$('#show_author_info').css('display','inline'); })})}})

// news comment
$(function(){
var scrtime;
$("#rcslider").hover(function(){
clearInterval(scrtime);
},function(){
scrtime = setInterval(function(){
var $ul = $("#rcslider");
var liHeight = $ul.find("li:last").height();
$ul.animate({marginTop : liHeight+3+"px"},1000,function(){
$ul.find("li:last").prependTo($ul);
$ul.find("li:first").hide();
$ul.css({marginTop:0});
$ul.find("li:first").fadeIn(1000);
});
},3000);
}).trigger("mouseleave");
});

//smilies toggle
$(function() {
    $("a.et_smilies").click(function() {
        $('#smilies-container').toggle(function() {
            $(document).click(function(event) {
                if (!($(event.target).is('#smilies-container') || $(event.target).parents('#smilies-container').length || $(event.target).is('a.et_smilies'))) {
                    $('#smilies-container').hide(200);
                }
            });
        });
    });
});

//comment editor
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
            addEditor(g, '***隐藏内容仅管理员可见***')
        },
        ahref: function() {
            var a = prompt('请输入链接地址', 'http://');
            var b = prompt('请输入链接描述','');
            if (a) {
                addEditor(g, '<a target="_blank" href="' + a + '" rel="external">' + b + '</a>','')
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
        }
    };
    window['SIMPALED'] = {};
    window['SIMPALED']['Editor'] = h
});

// ------------------------------------------------------end
});

// reply and references of comment
     function reply(authorId, commentId, commentBox) {
        var author = document.getElementById(authorId).innerHTML;
        var insertStr = '<a href="#' + commentId + '">@' + author.replace(/\t|\n/g, "") + '</a> \n';
        appendReply(insertStr, commentBox);
    }
    function quote(authorId, commentId, commentBodyId, commentBox) {
        var author = document.getElementById(authorId).innerHTML;
        var comment = document.getElementById(commentBodyId).innerHTML;

        var insertStr = '<blockquote cite="#' + commentBodyId + '">';
        insertStr += '\n<strong><a href="#' + commentId + '">@' + author.replace(/\t|\n|\r\n/g, "") + '</a> :</strong>';
        insertStr += comment.replace(/\t/g, "");
        insertStr += '</blockquote>\n';

        insertQuote(insertStr, commentBox);
    }
    function appendReply(insertStr, commentBox) {
        if(document.getElementById(commentBox) && document.getElementById(commentBox).type == 'textarea') {
            field = document.getElementById(commentBox);
        } else {
            alert("The comment box does not exist!");
            return false;
        }
        if (field.value.indexOf(insertStr) > -1) {
            alert("You've already appended this reply!");
            return false;
        }
        if (field.value.replace(/\s|\t|\n/g, "") == '') {
            field.value = insertStr;
        } else {
            field.value = field.value.replace(/[\n]*$/g, "") + '\n\n' + insertStr;
        }
        field.focus();
    }
    function insertQuote(insertStr, commentBox) {
        if(document.getElementById(commentBox) && document.getElementById(commentBox).type == 'textarea') {
            field = document.getElementById(commentBox);
        } else {
            alert("The comment box does not exist!");
            return false;
        }
        if(document.selection) {
            field.focus();
            sel = document.selection.createRange();
            sel.text = insertStr;
            field.focus();

        } else if (field.selectionStart || field.selectionStart == '0') {
            var startPos = field.selectionStart;
            var endPos = field.selectionEnd;
            var cursorPos = startPos;
            field.value = field.value.substring(0, startPos)
                        + insertStr
                        + field.value.substring(endPos, field.value.length);
            cursorPos += insertStr.length;
            field.focus();
            field.selectionStart = cursorPos;
            field.selectionEnd = cursorPos;

        } else {
            field.value += insertStr;
            field.focus();
        }
    };

// sidebar follow and fixed
(function(){
    var oDiv=document.getElementById("float");
    var H=0,iE6;
    var Y=oDiv;
    while(Y){H+=Y.offsetTop;Y=Y.offsetParent};
    iE6=window.ActiveXObject&&!window.XMLHttpRequest;
    if(!iE6){
        window.onscroll=function()
        {
            var s=document.body.scrollTop||document.documentElement.scrollTop;
            if(s>H){oDiv.className="box box2";if(iE6){oDiv.style.top=(s-H)+"px";}}
            else{oDiv.className="box";}
        };
    }
})();

// copy links
function copy_code(text) {
  if (window.clipboardData) {
    window.clipboardData.setData("Text", text)
	alert("已经成功将原文链接复制到剪贴板！");
  } else {
	var x=prompt('你的浏览器可能不能正常复制\n请您手动进行：',text);
  }
};

// announcement scroll
function AutoScroll(obj){ 
	$(obj).find("ul:first").animate({ 
		marginTop:"-25px" 
	},500,function(){ 
		$(this).css({marginTop:"0px"}).find("li:first").appendTo(this); 
		}); 
} 
jQuery(function($){
   $(document).ready(function(){ 
	  setInterval('AutoScroll("#announcement")',4000) 
   });
});