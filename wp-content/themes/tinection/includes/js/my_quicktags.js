/**
 * Main Javascript of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.1
 * @date      2014.12.19
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

QTags.addButton( 'hr', '水平线', "\n<hr />\n", '' );//添加横线
QTags.addButton( 'h2', '标题2', "\n<h2>", "</h2>\n" ); //添加标题2
QTags.addButton( 'h3', '标题3', "\n<h3>", "</h3>\n" ); //添加标题3
QTags.addButton( 'p', '段落', '\n<p>\n\n</p>', "" );//添加段落
QTags.addButton( 'php', 'PHP', '\n<pre class="lang:php decode:true ">\n\n</pre>', "" );//添加php代码
QTags.addButton( 'js', 'JS', '\n<pre class="lang:js decode:true ">\n\n</pre>', "" );//添加js代码
QTags.addButton( 'css', 'CSS', '\n<pre class="lang:css decode:true ">\n\n</pre>', "" );//添加css代码
QTags.addButton( 'toggle', '折叠板', '\n[toggle hide="no",title="",color=""]\n\n[/toggle]', "" );//添加Toggle内容块
QTags.addButton( 'button', '按钮', '\n[button class="default或primary或success或info或warning或danger" size="lg或sm或xs" href="" title=""]\n\n[/button]', "" );//添加按钮短代码
QTags.addButton( 'callout', '信息条', '\n[callout class="info或warning或danger" title=""]\n\n[/callout]', "" );//添加提示信息短代码
QTags.addButton( 'infobg', '背景块', '\n[infobg class="primary或notice或success或info或waring或danger" closebtn="" color="" bgcolor=""]\n\n[/infobg]', "" );//添加可关闭背景块短代码
QTags.addButton( 'l2v', '登录可见', '\n[tinl2v]\n\n[/tinl2v]', "" );//添加登录可见短代码
QTags.addButton( 'r2v', '回复可见', '\n[tinr2v]\n\n[/tinr2v]', "" );//添加回复可见短代码
QTags.addButton( 'download', '下载', '\n[button class="download" size="lg或sm或xs" href="" title=""]此下载为直接跳转下载地址页，若要跳转站内专用下载页，请使用编辑器下方下载资源meta-box\n[/button]', "" );//添加下载按钮短代码
QTags.addButton( 'demo', '演示', '\n[button class="demo" size="lg或sm或xs" href="" title=""]此演示为直接跳转演示网站页，若要站内嵌入演示，请使用编辑器下方演示资源meta-box\n[/button]', "" );//添加演示按钮短代码
//这儿共有四对引号，分别是按钮的ID、显示名、点一下输入内容、再点一下关闭内容（此为空则一次输入全部内容），\n表示换行。