<?php
/*
Plugin Name: Woo CMP Flash Video Player
Plugin URI: http://www.softrial.info/woocmp
Description: Create player with multiple skins via CMP Player shortcode!
Version: 1.1
Author: Softrial
Author URI: http://www.softrial.info/
License: GPLv2
*/


/* * * ** * * * * * * * * * * * * * * * * * * * * * * */
/*                   BASE FUNCTION                    */
/* * * * * * * * * * * * * * * * * * * * * * * * * * */

function woocmp_shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
		   'preview' => 1,
		   'video' => 1,
		   'duration' => 1,
		   'width' => '600',
		   'height' => '400',
		   'fullscreen' => 'true',
		   'autostart' => 'false',
		   'skin' => '1',
		   'plugin' => '',
		   'wmode' => ''
		), $atts)); 

		$url = get_bloginfo('url').'/wp-content/plugins/'. basename(dirname(__FILE__));
		
		$content .= "
			<div id='mediaplayer'></div>
			<embed
			 flashvars='src=$video&video_image=$preview&skin_id=$skin&duration=$duration&auto_play=$autostart&plugins=$url/plugins/$plugin'
			 allowfullscreen=$fullscreen
 			 allowscriptaccess='always'
 			 wmode='$wmode'
 			 id='mediaplayer'
 			 name='mediaplayer'
 			 src='$url/player.swf'
 			 width=$width
 			 height=$height
			/>
		";
	return $content;
}
add_shortcode( 'woocmp', 'woocmp_shortcode' );

?>