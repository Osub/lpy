<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WXLOG_Custom_reply_edit {

	public function __construct() {
	}

	//关于页面内容
	public function edit_page() {
		global $wpdb;
		$wpdb->query('ALTER TABLE  `'.$wpdb->prefix.'wxlog_custom_reply` CHANGE  `title`  `title` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');	
		$wpdb->query('ALTER TABLE  `'.$wpdb->prefix.'wxlog_custom_reply` CHANGE  `image_url`  `image_url` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');	
		$wpdb->query('ALTER TABLE  `'.$wpdb->prefix.'wxlog_custom_reply` CHANGE  `url`  `url` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');	
		$wpdb->query("ALTER TABLE  `".$wpdb->prefix."wxlog_custom_reply` ADD  `mode` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `msgtype`");	

		$id = intval($_GET['edit']);
		if($_POST){
			$data = array( 
				'keyword' => $_POST['keyword'], 
				'msgtype' => $_POST['msgtype'], 
				'mode' => $_POST['mode'], 				
				'title' => implode('|phplogcom|',$_POST['title']), 
				'url' => implode('|phplogcom|',$_POST['url']), 
				'image_url' => implode('|phplogcom|',$_POST['image_url']), 
				'content' => $_POST['content'], 
				'status' => $_POST['status'] 
			);
			if($_POST['msgtype']=='music'){
				$data['title'] = $_POST['music_title'];
				$data['url'] = $_POST['music_url'];
				$data['image_url'] = $_POST['music_image_url'];
			}
			if($_POST['msgtype']=='api'){
				$data['title'] = $_POST['api_token'];
				$data['url'] = $_POST['api_url'];
				$data['image_url'] = $_POST['api_mode'];
			}
			$data_format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s','%d' );
			if($id>0){
				$wpdb->update( $wpdb->wxlog_custom_reply, $data, array( 'ID' => $id ), $data_format, array( '%d' ) );
			}else{
				$wpdb->insert( $wpdb->wxlog_custom_reply, $data, $data_format );
				$id = $wpdb->insert_id;
			}
			$updated = 1;
		}
		if($id>0){
			$custom_reply = $wpdb->get_row("SELECT * FROM {$wpdb->wxlog_custom_reply} WHERE ID={$id}");
		}
		?>
		<div class="wrap">
            <h2><?php if($id>0){echo '编辑';}else{echo '添加';}?>自定义回复 <a href="javascript:history.go(-1);" class="add-new-h2">返回</a></h2>
			<?php if($updated){?>
			<div class="updated fade below-h2"><p>保存成功。</p></div>
			<?php if($phplog){?>
			<script language="javascript">
            jQuery(document).ready(function(){
                window.location.href='<?=admin_url( 'admin.php?page=wxlog_custom_reply' )?>';
            });
            </script>
			<?php }?>
			<?php }?>
            <form method="post" action="?page=wxlog_custom_reply&edit=<?=$id?>">
            <table class="form-table">
            <tbody><tr valign="top">
            <th scope="row"><label for="blogname">关键字</label></th>
            <td><input name="keyword" type="text" id="keyword" value="<?=$custom_reply->keyword?>" class="regular-text">
            <p class="description">多个关键词请用英文,格开，<code>例如: h,help,帮助</code></p></td>
            </tr>
            <tr valign="top">
            <th scope="row"><label for="default_role">回复类型</label></th>
            <td>
            <select name="msgtype" id="msgtype">
                <option<?php if($custom_reply->msgtype=='text') echo ' selected="selected"';?> value="text">文本消息</option>
                <option<?php if($custom_reply->msgtype=='news') echo ' selected="selected"';?> value="news">图文消息</option>
                <option<?php if($custom_reply->msgtype=='music') echo ' selected="selected"';?> value="music">音乐消息</option>
                <option<?php if($custom_reply->msgtype=='post') echo ' selected="selected"';?> value="post">文章消息</option>
                <option<?php if($custom_reply->msgtype=='api') echo ' selected="selected"';?> value="api">第三方接口</option> 
            </select>
            </td>
            </tr>
            
            <tr valign="top">
            <th scope="row"><label for="default_role">区配方式</label></th>
            <td>
            <select name="mode" id="mode">
                <option<?php if($custom_reply->mode=='0') echo ' selected="selected"';?> value="0">精准区配</option>
                <option<?php if($custom_reply->mode=='1') echo ' selected="selected"';?> value="1">模糊区配</option>
            </select>
            </td>
            </tr>            
            
            <tr valign="top" class="set-news"<?php if($custom_reply->msgtype!='news') echo ' style="display:none;"';?>><th scope="row"><span class="set-news">图文内容</span></th>
            <td id="wxlog_news_list"><input type="button" value=" 增加图文 " onclick="add_wxlog_news();">

			<?php 
			$wxlog_news_list_title = explode('|phplogcom|',stripslashes($custom_reply->title));
			$wxlog_news_list_url = explode('|phplogcom|',stripslashes($custom_reply->url));
			$wxlog_news_list_image_url = explode('|phplogcom|',stripslashes($custom_reply->image_url));
			foreach($wxlog_news_list_title as $key=>$value){?>
                <table class="form-table wxlog_news_list" id="wxlog_news_<?=($key+1)?>" style="padding-top:15px;">
                    <tr valign="top" class="set-news">
                    <th style="width:50px; padding:2px;" scope="row">标题</th>
                    <td style="padding:2px;"><input name="title[]" type="text" id="title" value="<?=$wxlog_news_list_title[$key]?>" class="regular-text"></td>
                    </tr>
                    <tr valign="top" class="set-news">
                    <th style="width:50px; padding:2px;" scope="row">URL</th>
                    <td style="padding:2px;"><input name="url[]" type="text" id="url" value="<?=$wxlog_news_list_url[$key]?>" class="regular-text"></td>
                    </tr>
                    <tr valign="top" class="set-news">
                    <th style="width:50px; padding:2px;" scope="row">图片</th>
                    <td style="padding:2px;"><input name="image_url[]" type="text" id="image_url<?=($key+1)?>" value="<?=$wxlog_news_list_image_url[$key]?>" class="regular-text"><input onclick="wxlog_upload('<?=($key+1)?>');" type="button" value=" 上传 " class="button dp-upload-button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:del_wxlog_news('wxlog_news_<?=($key+1)?>');">删除图文</a>
                    </td>
                    </tr>
                </table>
            <?php }?>
            </td>
            </tr> 

            <tr valign="top" class="set-music"<?php if($custom_reply->msgtype!='music') echo ' style="display:none;"';?>><th scope="row"><span class="set-music">音乐内容</span></th>
            <td id="wxlog_music_list">
                <table class="form-table wxlog_music_list" style="padding-top:15px;">
                    <tr valign="top">
                    <th style="width:110px; padding:2px;" scope="row">音乐标题</th>
                    <td style="padding:2px;"><input name="music_title" type="text" id="music_title" value="<?=$custom_reply->title?>" class="regular-text"></td>
                    </tr>
                    <tr valign="top">
                    <th style="width:110px; padding:2px;" scope="row">URL</th>
                    <td style="padding:2px;"><input name="music_url" type="text" id="music_url" value="<?=$custom_reply->url?>" class="regular-text"></td>
                    </tr>
                    <tr valign="top">
                    <th style="width:110px; padding:2px;" scope="row">高质量音乐链接</th>
                    <td style="padding:2px;"><input name="music_image_url" type="text" id="image_url11" value="<?=$custom_reply->image_url?>" class="regular-text"><input onclick="wxlog_upload('11');" type="button" value=" 上传 " class="button dp-upload-button">
                    </td>
                    </tr>
                </table>
            </td>
            </tr> 

            
            <tr valign="top" class="set-api"<?php if($custom_reply->msgtype!='api') echo ' style="display:none;"';?>><th scope="row"><span class="set-api">第三方接口参数</span></th>
            <td id="wxlog_music_list">
                <table class="form-table wxlog_music_list" style="padding-top:15px;">
                    <tr id="api_token_tr" valign="top"<?php if($custom_reply->image_url=='1') echo ' style="display:none;"';?>>
                    <th style="width:180px; padding:2px;" scope="row" id='api_token_desc'><?php if($custom_reply->image_url=='0'){echo '第三方接口的微信TOKEN';}else{echo 'POST参数';}?></th>
                    <td style="padding:2px;"><input name="api_token" type="text" id="api_token" value="<?=$custom_reply->title?>" class="regular-text"></td>
                    </tr>
                    <tr valign="top">
                    <th style="width:110px; padding:2px;" scope="row">URL</th>
                    <td style="padding:2px;"><input name="api_url" type="text" id="api_url" value="<?=$custom_reply->url?>" class="regular-text"></td>
                    </tr>
                    <tr valign="top">
                    <th style="width:110px; padding:2px;" scope="row">接口类型</th>
                    <td style="padding:2px;">
                        <select name="api_mode" id="api_mode" onchange="if(this.value==1){jQuery('#api_token_tr').hide();}else{jQuery('#api_token_tr').show();if(this.value==0){jQuery('#api_token_desc').html('第三方接口的微信TOKEN');}else{jQuery('#api_token_desc').html('POST参数');}}">
                            <option<?php if($custom_reply->image_url=='0') echo ' selected="selected"';?> value="0">微信标准接口（标准xml）</option>
                            <option<?php if($custom_reply->image_url=='1') echo ' selected="selected"';?> value="1">文本接口（GET）</option>
                            <option<?php if($custom_reply->image_url=='2') echo ' selected="selected"';?> value="2">文本接口（POST）</option>
                        </select>
                        <p class="description">参数填写帮助 <code><a target="_blank" href="http://www.phplog.com/detail/567.html">点我</a></code></p>                    
                    </td>
                    </tr>
                </table>
            </td>
            </tr>  
 
            <tr valign="top" id="content_tr"><th scope="row"><span class="set-post">检索条件</span><span class="set-text">回复内容</span><span class="set-news">第一条描述内容</span><span class="set-music">音乐描述内容</span></th><td>
            <textarea name="content" id="content" cols="50" rows="3" style="height:280px;" class="regluar-text ltr"><?=stripslashes($custom_reply->content)?></textarea><p class="set-news description">多图文回复的第一条描述内容</p><p class="set-post description"><code>例：cat=3&year=2014&tag=php+mysql&orderby=date&order=ASC</code> <br><code>参考：<a href="http://www.phplog.com/detail/365.html">http://www.phplog.com/detail/365.html</a></code></p>
            </td></tr>

            <tr valign="top"><th scope="row">是否有效</th><td>
            <label title="ag:i"><input type="radio" name="status" value="2" <?php if($custom_reply->status==2) echo 'checked="checked"';?>> <span>有效</span></label>
                <label title="g:i A"><input type="radio" name="status" value="0" <?php if($custom_reply->status!=2) echo 'checked="checked"';?>> <span>无效</span></label>
            </td></tr></tbody></table>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="保存更改"></p>
            </form>

			<script>
            function add_wxlog_news(){
                var wxlog_news=jQuery(".wxlog_news_list");
                var wxlog_news_len=wxlog_news.length;
                if(wxlog_news_len>=10){
                    alert('最多只能增加10个图文！');return false;
                }
				if(wxlog_news_len==0){
					document.getElementById('content_tr').style.display='';
                }else{
					document.getElementById('content_tr').style.display='none';
				}		
                jQuery("#wxlog_news_list").append('<table class="wxlog_news_list" id="wxlog_news_'+(wxlog_news_len+1)+'" style="padding-top:15px;">'+
                    '<tr valign="top" class="set-news">'+
                    '<th style="width:50px; padding:2px;" scope="row">标题</th>'+
                    '<td style="padding:2px;"><input name="title[]" type="text" id="title" value="" class="regular-text"></td>'+
                    '</tr>'+
                    '<tr valign="top" class="set-news">'+
                    '<th style="width:50px; padding:2px;" scope="row">URL</th>'+
                    '<td style="padding:2px;"><input name="url[]" type="text" id="url" value="" class="regular-text"></td>'+
                    '</tr>'+
                    '<tr valign="top" class="set-news">'+
                    '<th style="width:50px; padding:2px;" scope="row">图片</th>'+
                    '<td style="padding:2px;">'+
                    '<input name="image_url[]" type="text" id="image_url'+(wxlog_news_len+1)+'" value="" class="regular-text"><input onclick="wxlog_upload(\''+(wxlog_news_len+1)+'\');" type="button" value=" 上传 " class="button dp-upload-button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                    '<a href="javascript:del_wxlog_news(\'wxlog_news_'+(wxlog_news_len+1)+'\');">删除图文</a>'+
                    '</td>'+
                    '</tr>'+
                '</table>');
            }
            function del_wxlog_news(id){
                var wxlog_news=jQuery(".wxlog_news_list");
                var wxlog_news_len=wxlog_news.length;	

                if(wxlog_news_len<2){
					alert('必须要一个图文');return false;
                }
                if(wxlog_news_len>2){
					document.getElementById('content_tr').style.display='none';
                }else{
					document.getElementById('content_tr').style.display='';
				}	
                jQuery("#"+id).remove();
            }
			var targetfieldl;
            function wxlog_upload(id){
				//获取它前面的一个兄弟元素   
				 targetfield = "#image_url"+id;   
				 tb_show('', 'media-upload.php?type=image&amp;from=wxlog_custom_reply&amp;TB_iframe=true');   
				 return false;
            }			
            </script>

		</div>

		<?php
		
		  wp_enqueue_script('my-upload', get_bloginfo( 'stylesheet_directory' ) . '/js/upload.js'); 
		  add_thickbox();
		  wp_enqueue_script('media-upload');
		
		 global $WXLOG; $WXLOG->add_inline_js("jQuery('#msgtype').change(function(){
			 	document.getElementById('content_tr').style.display='';
				if ( jQuery(this).val() == 'news' ) {
					var wxlog_news=jQuery('.wxlog_news_list');
					var wxlog_news_len=wxlog_news.length;
					if(wxlog_news_len>1){
						document.getElementById('content_tr').style.display='none';
					}
					jQuery('.set-news').show();
					jQuery('.set-music').hide();
					jQuery('.set-text').hide();
					jQuery('.set-post').hide();
					jQuery('.set-api').hide();					
				} else if ( jQuery(this).val() == 'music' ) {
					jQuery('.set-music').show();
					jQuery('.set-news').hide();
					jQuery('.set-text').hide();
					jQuery('.set-post').hide();
					jQuery('.set-api').hide();					
				} else if ( jQuery(this).val() == 'post' ) {
					jQuery('.set-music').hide();
					jQuery('.set-news').hide();
					jQuery('.set-text').hide();
					jQuery('.set-post').show();
					jQuery('.set-api').hide();					
					jQuery('.set-api').hide();					
				} else if ( jQuery(this).val() == 'api' ) {
					document.getElementById('content_tr').style.display='none';
					jQuery('.set-music').hide();
					jQuery('.set-news').hide();
					jQuery('.set-text').hide();
					jQuery('.set-post').hide();
					jQuery('.set-api').show();					
				} else {
					jQuery('.set-news').hide();
					jQuery('.set-music').hide();
					jQuery('.set-post').hide();
					jQuery('.set-text').show();
					jQuery('.set-api').hide();					
				}
			}).change();
			window.send_to_editor = function(html) {
				 //musicurl = jQuery('a',html).attr('href');   
				 //jQuery('#content').val(html);  
				 imgurl = jQuery('img',html).attr('src');   
				 jQuery(targetfield).val(imgurl);
				 tb_remove();    
			}//更新上传输入框的文件内容  
			");
	}

}