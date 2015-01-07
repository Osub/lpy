jQuery(document).ready(function($){
	   
    // tag提醒信息
	$("#contribute_tags").focus(function(){
		$("#tagsinfo").text("(多个用英文半角逗号隔开)");
	});
	$("#contribute_tags").blur(function(){
		$("#tagsinfo").text("");
	});

	 
    // 定义函数   
    var form = $("#article_contribute_from");   
    var name = $("#contribute_authorname");   
    var nameInfo = $("#nameinfo"); 
	var email = $("#contribute_authoremail");   
    var emailInfo = $("#emailinfo");   
    var title = $('#contribute_title');   
    var titleInfo = $("#titleinfo");   
    var message = $("#contribute_content"); 
	var messageInfo = $("#messageinfo");   
  
    name.focus(validateName).blur(validateName);
	email.focus(validateEmail).blur(validateEmail);   
    title.focus(validateTitle).blur(validateTitle);
	message.focus(validateMessage).blur(validateMessage);   
       
    name.keyup(validateName);
	email.keyup(validateEmail);   
    title.keyup(validateTitle);   
    message.keyup(validateMessage);   
    //提交按钮激活   
    form.submit(function(){   
        if(validateName() & validateEmail() & validateTitle() & validateMessage())   
            return true  
        else  
            return false;   
    });   
       
    // 昵称函数判断   
    function validateName(){      
        if(name.val().length < 2){   
            name.css("background-color","#ffeaea");   
            nameInfo.text("（你的昵称也太短了吧？至少两个字哦！）");   
            nameInfo.removeClass("right_info").addClass("error_info");   
            return false;   
        }else if(name.val().length > 20){
			name.css("background-color","#ffeaea");   
            nameInfo.text("（你的昵称也太长了吧？最多20个字哦！）");   
            nameInfo.removeClass("right_info").addClass("error_info");   
            return false;   
		}     
        else{   
            name.css("background-color","#F5F5EB");   
            nameInfo.addClass("right_info").text("");   
            nameInfo.removeClass("error_info");   
            return true;   
        }   
    }  
	
	// 邮箱函数判断   
    function validateEmail(){      
        if(email.val() == '' || email.val().length > 60){   
            email.css("background-color","#ffeaea");   
            emailInfo.text("（邮件不能为空，且长度不得超过60字！）");   
            emailInfo.removeClass("right_info").addClass("error_info");   
            return false;
        }else if(!email.val().match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){
			email.css("background-color","#ffeaea");   
            emailInfo.text("（格式错误！格式：xxxx@xx.xxx）");   
            emailInfo.removeClass("right_info").addClass("error_info");   
            return false;   
		}     
        else{   
            email.css("background-color","#F5F5EB");   
            emailInfo.addClass("right_info").text("");   
            emailInfo.removeClass("error_info");   
            return true;   
        }   
    }  
	
	// 标题函数判断
    function validateTitle(){      
        if(title.val().length < 5 || title.val().length > 100){   
            title.css("background-color","#ffeaea");   
            titleInfo.text("（标题最少5字，且不得超过100字！）");   
            titleInfo.removeClass("right_info").addClass("error_info");   
            return false;   
        }     
        else{   
            title.css("background-color","#F5F5EB");   
            titleInfo.addClass("right_info").text("");   
            titleInfo.removeClass("error_info");   
            return true;   
        }   
    }   
	
	// 内容函数判断
    function validateMessage(){      
        if(message.val().length < 300){   
            message.css("background-color","#ffeaea"); 
			messageInfo.text("（文章内容最少300字！）");   
            messageInfo.removeClass("right_info").addClass("error_info");     
            return false;   
        }   
        else{              
            message.css("background-color","#F5F5EB");   
			messageInfo.addClass("right_info").css("margin-top","1px").text("");   
            messageInfo.removeClass("error_info");  
            return true;   
        }   
    }   
});  