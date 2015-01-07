<?php 
/**
 * The template for displaying footer.
 * @package WordPress
 * @subpackage Javin
 */
?>
<div id="backtotop"><a href="javascript:void(0);"><span>&uarr;</span>返回顶部</a></div>
<script src="<?php bloginfo( 'template_url' ); ?>/js/base.js" type="text/javascript"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/load.js" type="text/javascript"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/jquery.nivo.slider.js" type="text/javascript"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/jquery.infinitescroll.js"></script>
<script type="text/javascript">
	$(window).load(function() {
		$('#slider').nivoSlider({
			controlNav:false,
			effect:'<?php if( javin_opt('javin_slider_effect')!='' ) echo javin_opt('javin_slider_effect'); else echo ('random'); ?>',   
			animSpeed:500,
			captionOpacity:0.9,
			directionNav:true,
			controlNav:true,
			pauseTime:<?php if( javin_opt('javin_slider_pausetime')!='' ) echo javin_opt('javin_slider_pausetime'); else echo ('3000'); ?>,
			directionNavHide: true,
			pauseOnHover:true
		});
	});
</script>
<script type="text/javascript" >
(window.INFSCR_jQ ? jQuery.noConflict() : jQuery)(function($){
  
  $('#content').infinitescroll({
    debug           : false,
    nextSelector    : "#page_nav a",
    loadingImg      : "<?php bloginfo( 'template_url' ); ?>/images/ajax-loader.gif",
    loadingText     : "正在努力为你加载下一页..",
    donetext        : "很好! 全部的内容加载完了.",
    navSelector     : "#page_nav",
    contentSelector : "#content",
    itemSelector    : "#content > div.post"
    },function(){ 
 
    });
});		
</script> 
<!-- 网站统计 -->
<?php if( javin_opt('javin_track_button')!='' ) echo javin_opt('javin_track'); ?>
<!-- baidu begin -->
<?php if ( is_single() || is_page() ) { ?>
<!-- baidu share -->
<script>
window._bd_share_config={"common":{"bdSnsKey":{"tsina":"1865653506"},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script>
<!-- baidu like -->
<script id="bdlike_shell"></script>
<script>
var bdShare_config = {
	"type":"large",
	"color":"red",
	"uid":"6443910"
};
document.getElementById("bdlike_shell").src="http://bdimg.share.baidu.com/static/js/like_shell.js?t=" + Math.ceil(new Date()/3600000);
</script>
<?php } else { ?>
<?php } ?>
<!-- baidu end -->
<?php wp_footer(); ?>
