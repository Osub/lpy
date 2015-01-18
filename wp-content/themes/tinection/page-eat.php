<?php
 /**
  * Template Name:摇一摇今天要吃啥
  * author : 吴俊圆
  *
  */


?>
<!DOCTYPE html>
<!-- saved from url=(0028)http://wx.120ah.com/2015/#rd -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>今天要吃啥？</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<style type="text/css">
    @keyframes play {
		100%{
			background-position: -737px -2px;
		}
	}
	@-webkit-keyframes play {
		100%{
			background-position: -737px -2px;
		}
	}
	.sprite {
		width:145px;page-
		height:300px;
		display:inline-block;
		overflow:hidden;
		background-repeat: no-repeat;
		background-image:url(234.png);
		background-position: -2px -2px;
		animation: play 0.8s steps(5) infinite;
		-webkit-animation: play 0.8s steps(5) infinite;
	}
	.preload {
		background-position: 9999px 9999px;
		width: 1px;
		height: 1px;
		display: none;
	}

	body {
		
		background: url(./wp-content/themes/tinection/includes/images/bg.png) no-repeat #E8DFD0;
	}
	html{
		margin:auto;
		max-width: 320px;
	}
	.do {
		background: url(button.png) no-repeat center;
		background-size: cover;
		width: 100%;
		height: 15%;
		margin: 0 auto;
		position: fixed;
		bottom: 20%;
		text-align: center;
		color: #666;
		line-height: 180px;
		cursor: pointer;
	}

	.cover {
		background: url(share.png) top right no-repeat;
		background-size: 50%;
		background-color: rgba(0, 0, 0, 0.7);
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		display: none;
	}

	.decode .inner {
		width: 100%;
		height: 100%;
		background: url(decode.png) center no-repeat;
		background-size: 80%;
	}

	.item {
		width: 100%;
		text-align: center;
		padding-top: 20%;
	}
	#yao img{
		margin: 20% auto;
		border-radius: 160px;
	}
	#eat img{
		width: 100%;
		border-radius: 200px;
	}
</style>
</head>
<body ontouchstart="music()">
	<div id="audiocontainer" style="display:none">
    <audio id="bgsound"  autoplay="autoplay"><source src="http://182.92.78.110/webstar/wp-content/themes/tinection/includes/images/5018.mp3"/></audio>
</div>

    <div id="yao" >
    	<img src="<?php bloginfo('template_url' ); ?>/includes/images/677-1306141I635114.gif">
    </div>
    <div id="eat" >
    </div>
 <script src="<?php bloginfo('template_url') ?>/includes/js/zepto.min.js"></script>
    <script src="<?php bloginfo('template_url') ?>/includes/js/index.js"></script>
    <script type="text/javascript">


    </script>

</body></html>