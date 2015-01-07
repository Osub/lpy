=== Woo CMP Flash Video Player ===
Contributors: softrial
Donate link: http://www.zhiyanblog.com/woocmp
Tags: player, flash player, simple, video, audio, skins, mp3, woocmp
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2

Create player with multiple skins via shortcode succinctly! 短代码方式创建多皮肤风格flash播放器

== Description ==

This plugin provide a shortcode to create player. You can change skins through shortcode and players with different appearance can be created anywhere of your site.这个插件让你可以以短代码方式调用并创建一个flash播放器，并内置多种自定义皮肤外观。

== Installation ==

1. Upload plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use shortcode in your posts/pages

* Base structure of shortcode (these parameters must be set with value):
`[woocmp preview="" video="" duration=""]`

* Full option shortcode:
`[woocmp preview="" video="" duration="" width="" height="" skin="" fullscreen="" autostart="" plugin="" wmode=""]`

Example:
`[woocmp preview="http://www.softrial.info/attachments/cover.jpg" video="http://www.softrial.info/attachments/video.mp4" duration="30" width="600" height="400" fullscreen="true" autostart="false" skin="10" plugin="sharing.swf"]`


== Frequently Asked Questions ==
= What's this player? 关于播放器 =

this plugin helps you create player via shortcode and the player is developed from CMP which is an open flash player.这个插件帮助你以短代码方式创建一个播放器，播放器是以开源cmp播放器为基础的。

= What's the skin? 播放器皮肤 =
the skins offer different apperance of players for viewers,the value of skin parameter is from 1-20, and 1-13 are video player skins while 14-20 are audio player skins.插件提供了20种皮肤，皮肤ID 1-13是为视频设计，14-20是为音频设计，当然你也可以用视频皮肤作为音频界面。

= what,s the wmode? wmode参数介绍 =
wmode is window mode ,there are three kinds of wmode value:window(default) opaque transparent. To create video player, i recommend to use window; to create audio player or black background goes against appearance, transparent is recommended.wmode就是窗口模式，有三种参数，window(默认) opaque transparent，如果创建视频播放器，推荐默认值，如果音频播放器，特别是发现背景有黑边，推荐transparent。

= plugin module =
now only one module is offered ，you can use 'plugin='sharing.swf'' to enable the sharing module. 插件提供了一个分享组件，在短代码中加入plugin='sharing.swf来调用，适合中国用户。



== Screenshots ==

1. Player in action

== Changelog ==
= 1.1 =
* Repair one install error; Add social sharing module
= 1.0 =
* Start the work...