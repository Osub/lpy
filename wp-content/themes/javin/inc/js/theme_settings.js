$(function(){
	//导航切换
	$('.javin_box:eq(0)').show();
	$('.nav-tab-wrapper a').each(function(i) {
		$(this).click(function(){
			$(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
			$($('.javin_box')[i]).show().siblings('.javin_box').hide();
		})
	});
	
	//广告系统实时预览
	$('.javin_box:last .javin_textarea').each(function(i) {
		$(this).bind('keyup',function(){
			$(this).next().html( $(this).val() );
		}).bind('change',function(){
			$(this).next().html( $(this).val() );
		}).bind('click',function(){
			$(this).next().html( $(this).val() );
			if( $(this).next().attr('class') != 'javin_ads_view' ){
				$(this).after('<div class="javin_ads_view">' + $(this).val() + '</div>');
			}else{
				$(this).next().slideDown();
			}
		})
		
	});	
})