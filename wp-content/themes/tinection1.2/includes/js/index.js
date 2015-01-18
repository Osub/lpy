function music(){
	var music = document.getElementById("bgsound");//获取ID
        		if (music.paused) { //判读是否播放
               		 music.play(); //没有就播放
        		} 
}


// 首先，定义一个摇动的阀值
var SHAKE_THRESHOLD = 2000;
// 定义一个变量保存上次更新的时间
var last_update = 0;
// 紧接着定义x、y、z记录三个轴的数据以及上一次出发的时间
var x;
var y;
var z;
var last_x;
var last_y;
var last_z;

  
$('#yao').hide();
//摇一摇

var urls = [
					"<img class='eatimg' src='http://182.92.78.110/webstar/wp-content/themes/tinection/includes/images/baozi.jpg' />",
					"<img class='eatimg' src='http://182.92.78.110/webstar/wp-content/themes/tinection/includes/images/daoxiaomian.jpeg' />",
					"<img class='eatimg' src='http://182.92.78.110/webstar/wp-content/themes/tinection/includes/images/lamian.jpg' />"
			    ];
	$(window).on('devicemotion', function(e) {
		
		// 获取含重力的加速度
	　　var acceleration = e.accelerationIncludingGravity; 

	　　// 获取当前时间
	　　var curTime = new Date().getTime(); 
	　　var diffTime = curTime -last_update;
	　　// 固定时间段
	　　if (diffTime > 100) {
	　　　　last_update = curTime; 
	　　　　x = acceleration.x; 
	　　　　y = acceleration.y; 
	　　　　z = acceleration.z; 
	　　　　var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000; 
			
	　　　　if (speed > SHAKE_THRESHOLD) { 
				//var event = document.createEvent('TouchEvent'); 
				//event.initUIEvent('touchstart', true, true); 
				////body.dispatchEvent(event);
				//alert(56)
	　　　　　　// TODO:在此处可以实现摇一摇之后所要进行的数据逻辑操作
				$('#yao').show();
				$('#eat').hide();
				

				setTimeout(function() {
					$('#yao').hide();
					var jumpTo = urls[parseInt(Math.random() * urls.length)];
					$('#eat').html(jumpTo);
					$('#eat').show();

				}, 3000);

	　　　　}

	　　　　last_x = x; 
	　　　　last_y = y; 
	　　　　last_z = z; 
	　　} 
	});
//微信分享

var shareMeta = {
	img_url: "http://yu.weiju100.com/2015newyeardraw/thumbnail.gif",
	image_width: 100,
	image_height: 100,
	link: 'http://yu.weiju100.com/2015newyeardraw/',
	title: "2015乙未羊，为自己摇枚新年签！",
	desc: "这是对过去的感悟和对新年的祈望，希望它能为你带来好运...",
	appid: ''
};
document.addEventListener('WeixinJSBridgeReady', function () {
	WeixinJSBridge.on('menu:share:appmessage', function(){
		WeixinJSBridge.invoke('sendAppMessage', shareMeta);
	});
	WeixinJSBridge.on('menu:share:timeline', function(){
		WeixinJSBridge.invoke('shareTimeline', shareMeta);
	});
	WeixinJSBridge.on('menu:share:weibo', function(){
		WeixinJSBridge.invoke('shareWeibo', {
			content: shareMeta.title + shareMeta.desc,
			url: shareMeta.link
		});
	});
});