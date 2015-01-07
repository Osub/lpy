<?php

if(isset($_GET['echostr']) or isset($_GET['token'])){
	define( "TOKEN", $_GET['token'] );
	//http://www.phplog.com/wp-content/plugins/wxlog/includes/class-wxlog.php?token=wxlog
}else{
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
}

class WL {

	var $wxlog_log_id;
	
	public function __construct() {
		global $wpdb;

		if(isset($_GET['echostr']))
			$this->valid();//第一次验证

		$postStr = (isset($GLOBALS["HTTP_RAW_POST_DATA"]))?$GLOBALS["HTTP_RAW_POST_DATA"]:'';
		if($_POST["test"]){
			$postStr = @$_POST["HTTP_RAW_POST_DATA"];
		}
		//$xml = file_get_contents('php://input');
		//$postStr = unicode_encode($postStr);
		
		//只搜索标题
		
		if(get_option( 'wxlog_post_search_title_only' )==1){
			add_filter( 'posts_search', array( $this, '__search_by_title_only' ), 10, 2 );
		}
		
		if(isset($_GET['token'])){
			if($_GET['signature'] and $_GET['timestamp'] and $_GET['nonce']){
				include_once( 'wxlog-functions.php' );
				//wxlog(date("Y-m-d").'.txt','GET:'.var_export($_GET, TRUE));
				$url = 'http://'.$_SERVER['HTTP_HOST'].'/?'.TOKEN.'&signature='.$_GET['signature'].'&timestamp='.$_GET['timestamp'].'&nonce='.$_GET['nonce'];
				$content = wxlog_get_xml($url,$postStr);
				exit($content);
			}
		}
		
		if(get_option( 'wxlog_txt_log' )){
			$upload_dir = wp_upload_dir();
			$dir = $upload_dir['basedir'].'/wxlog_logs/';
			if (!is_dir($dir)) @mkdir($dir, 0777);
			//echo $dir.date("Y-m-d").'.txt';
			wxlog($dir.date("Y-m-d").'.txt','GET:'.var_export($_GET, TRUE));
			wxlog($dir.date("Y-m-d").'.txt','HTTP_RAW_POST_DATA:'.var_export(@$GLOBALS["HTTP_RAW_POST_DATA"], TRUE));		
		}
		
		if (!empty($postStr) and $this->checkSignature()){
			$wpdb->postStr = $postStr;
			$wpdb->postArray = wxlog_xml_to_array($postStr);
			$this->wxlog_log_id = $this->insert_wxlog_log($wpdb->postArray,$wpdb->postStr);
			$this->responseMsg($wpdb->postArray,$wpdb->postStr);
		}
		

	}

    public function insert_wxlog_log($data,$message=''){
		global $wpdb;
		
		$wpdb->insert(
			$wpdb->wxlog_log,
			array(
				'fromusername'      => $data['FromUserName'],
				'msgtype' 			=> $data['MsgType'],
				'content'           => $data['Content'],
				
				'signature'         => $_GET['signature'],
				'timestamp'         => current_time( 'mysql' ),

				'nonce'             => $_GET['nonce'],
				
				'user_id'           => 1,
				'user_ip'           => $this->get_user_ip(),
				'user_agent'        => $this->get_user_ua(),

				'message'           => $message,
				'reply'             => '',
				'status'            => 0,
			),
			array(
				'%s',
				'%s',
				'%s',
				
				'%s',
				'%s',
				'%s',
				
				'%d',
				'%s',
				'%s',
				
				'%s',
				'%s',
				'%d'
			)
		);
		return $wpdb->insert_id;
    } 


	private function get_user_ip() {
		return sanitize_text_field( ! empty( $_SERVER['HTTP_X_FORWARD_FOR'] ) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'] );
	}
	private function get_user_ua() {
		$ua = sanitize_text_field( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '' );
		if ( strlen( $ua ) > 200 )  
			$ua = substr( $ua, 0, 199 );
		return $ua;
	}

    public function responseMsg($postArray,$postStr){
		global $wpdb;
		
		if(empty($postArray['Content']) and $postArray['Content']!==0){
			//return;
		}
		$postArray['Content'] = strtolower($postArray['Content']);

		//黑名单过滤	
		$wxlog_blacklist_user = get_option( 'wxlog_blacklist_user' );
		if($wxlog_blacklist_user){
			$contentStr = get_option( 'wxlog_blacklist_message_custom' );
			if(in_array($postArray['FromUserName'],explode(',',str_replace('，',',',$wxlog_blacklist_user)))){
				if(!empty($contentStr)){
					$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], $contentStr);
					if($resultStr){
						$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
						exit($resultStr);
					}
				}else{
					return '';
				}
			}
		}
		
		//调用自定义回复数据
		$custom_reply_array = $wpdb->get_results( "SELECT ID,keyword,mode FROM {$wpdb->wxlog_custom_reply} where status = 2" );
		foreach($custom_reply_array as $key=>$value){
			$keyword_arr = explode(',',str_replace('，',',',$value->keyword));
			if($value->mode==1){
				if(preg_match_all("/(".implode('|',$keyword_arr).")/i",$postArray['Content'],$keyarr)){
					$custom_reply = $wpdb->get_row( "SELECT * FROM {$wpdb->wxlog_custom_reply} where ID = {$value->ID}" );
					break;
				}
			}else{
				if(in_array($postArray['Content'],$keyword_arr)){
					$custom_reply = $wpdb->get_row( "SELECT * FROM {$wpdb->wxlog_custom_reply} where ID = {$value->ID}" );
					break;
				}
			}
		}//d($custom_reply);
		if($custom_reply){
			$resultStr = $this->get_custom_reply($postArray,$custom_reply);
			if($resultStr){
				$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
				exit($resultStr);
			}
		}


		//调用官方插件
		$wxlog_my_plugins = get_option( 'wxlog_my_plugins' );
		if($wxlog_my_plugins){
			//print_r($wxlog_my_plugins);
			$content = file_get_contents('http://www.phplog.com/?wxlog_plugins&format=json&host='.$_SERVER['HTTP_HOST'].'&id='.$wxlog_my_plugins.'&key='.$postArray['Content']);
			//print_r($content);
			$content = json_decode($content);
			//print_r($content);
			if($content->content){
				if(is_array($content->content)){
					$resultStr = $this->reply_news($postArray['FromUserName'], $postArray['ToUserName'], $content->content);
				}else{
					$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], $content->content);
				}
				if($resultStr){
					$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
					exit($resultStr);
				}
			}
		}
		
		//调用插件
		$plugins = get_wxlog_plugins();
		//print_r($plugins);
		foreach($plugins as $key=>$value){
			if(is_wxlog_plugin($value) and is_active_wxlog_plugin($key) and $value['key']==''){
				if(file_exists(WP_PLUGIN_DIR .'/'. $key)){
					include_once( WP_PLUGIN_DIR .'/'. $key );
				}
				$classname = $value['ClassName'];
				if(class_exists($classname)){
					//echo $classname;
					$pluginObj = new $classname;
					$content = $pluginObj->get_content( $postArray['Content'] );
					if($content){
						if(is_array($content)){
							$resultStr = $this->reply_news($postArray['FromUserName'], $postArray['ToUserName'], $content);
						}else{
							$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], $content);
						}
						if($resultStr){
							$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
							exit($resultStr);
						}
					}
				}
			}
		}//d($plugins);


		//调用Wordpress数据
		if(get_option( 'wxlog_post_max' )){
			$contentArr = $this->get_posts($postArray['Content']);
			if($contentArr){
				if(is_array($contentArr)){
					$resultStr = $this->reply_news($postArray['FromUserName'], $postArray['ToUserName'], $contentArr);
					if($resultStr){
						$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
						exit($resultStr);
					}
				}
			}
		}
		
		$wxlog_api_url = get_option( 'wxlog_api_url' );
		$wxlog_api_token = get_option( 'wxlog_api_token' );
		$wxlog_api_default = get_option( 'wxlog_api_default' );
		if($wxlog_api_url and $wxlog_api_token and $wxlog_api_default){
			exit(wxlog_get_xml($wxlog_api_url,$postStr));
		}
		
		//是否开启小黄鸡，可以到高级里设置。
		if(get_option( 'wxlog_simsimi' )){
			$simsimi_keyword = get_option( 'wxlog_simsimi_keyword' );
			if(!in_array($postArray['Content'],explode(',',$simsimi_keyword))){
				$contentStr = $this->simsimi( $postArray['Content'] );
				if($contentStr){
					$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], $contentStr);
					if($resultStr){
						$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
						exit($resultStr);
					}
				}
			}
		}
		
		//默认的回复内容		
		$contentStr = get_option( 'wxlog_default_content' );
		if($contentStr){
			if($contentStr=='default'){
				$custom_reply = $wpdb->get_row( "SELECT * FROM {$wpdb->wxlog_custom_reply} where keyword = 'default'" );
				if($custom_reply){
					$resultStr = $this->get_custom_reply($postArray,$custom_reply);
				}				
			}else{
				$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], $contentStr);
			}
		}
		if($resultStr){
			$wpdb->update( $wpdb->wxlog_log, array( 'reply' => $resultStr,'status' => 2 ), array( 'ID' => $this->wxlog_log_id ), array( '%s','%d' ), array( '%d' ) );
			exit($resultStr);
		}
    }




	function wxlog_post($url,$curlPost){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($curl);

		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($data === FALSE) {
			return curl_error($ch); 
			return false;//连接失败
		}

		if ($http_code !== 200) {
			return $http_code;//连接失败
			return false;//连接失败
		}

		curl_close($curl);
		return $data;
	}



	//自定义回复
	public function get_custom_reply($postArray,$custom_reply){
		global $wpdb;
		$resultStr = '';
		if($custom_reply->msgtype=='text'){
			$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], stripslashes($custom_reply->content));
		}elseif($custom_reply->msgtype=='news'){
			$contentArr = array();
			$wxlog_news_list_title = explode('|phplogcom|',stripslashes($custom_reply->title));
			$wxlog_news_list_url = explode('|phplogcom|',stripslashes($custom_reply->url));
			$wxlog_news_list_image_url = explode('|phplogcom|',stripslashes($custom_reply->image_url));
			$wxlog_news_list_description = explode('|phplogcom|',stripslashes($custom_reply->description));
			foreach($wxlog_news_list_title as $key=>$value){
				$contentArr[$key]['title'] =  $wxlog_news_list_title[$key];
				if($key==0){
					$contentArr[$key]['description'] =  $custom_reply->content;
				}
				$contentArr[$key]['image_url'] =  $wxlog_news_list_image_url[$key];
				$contentArr[$key]['url'] =  $wxlog_news_list_url[$key];
			}
			$resultStr = $this->reply_news($postArray['FromUserName'], $postArray['ToUserName'], $contentArr);
		}elseif($custom_reply->msgtype=='music'){
			$contentArr = array();
			$contentArr['title'] =  $custom_reply->title;
			$contentArr['description'] =  $custom_reply->content;
			$contentArr['image_url'] =  $custom_reply->image_url;
			$contentArr['url'] =  $custom_reply->url;
			$resultStr = $this->reply_music($postArray['FromUserName'], $postArray['ToUserName'], $contentArr);
		}elseif($custom_reply->msgtype=='api'){
			if($custom_reply->image_url==1){
				$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], file_get_contents($custom_reply->url));
			}elseif($custom_reply->image_url==2){
				$resultStr = $this->reply_text($postArray['FromUserName'], $postArray['ToUserName'], $this->wxlog_post($custom_reply->url,$custom_reply->title));
			}else{
				$TOKEN = $custom_reply->title;
				$signature_timestamp_nonce = wxlog_make_signature($TOKEN);
				$custom_reply->url .= ((strpos($custom_reply->url, '?') !== false) ? '&' : '?');
				$xml = wxlog_get_xml($custom_reply->url.'signature='.$signature_timestamp_nonce[0].'&timestamp='.$signature_timestamp_nonce[1].'&nonce='.$signature_timestamp_nonce[2].'',$wpdb->postStr);
				exit($xml);
			}
		}elseif($custom_reply->msgtype=='post'){
			$contentArr = $this->get_posts($custom_reply->keyword,$custom_reply->content);
			if($contentArr){
				if(is_array($contentArr)){
					$resultStr = $this->reply_news($postArray['FromUserName'], $postArray['ToUserName'], $contentArr);
				}
			}
		}
		return 	$resultStr;	
	}

	function __search_by_title_only( $search, &$wp_query ) {
		global $wpdb;
		if ( empty($search) ) 
			return $search;
		$q =& $wp_query->query_vars; 
		$n = !empty($q['exact']) ? '' : '%';
		$term = esc_sql( like_escape( $q['s'] ) );
		$search .= " AND ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
		if ( empty($q['sentence']) && count($q['search_terms']) > 1 && $q['search_terms'][0] != $q['s'] ) 
			$search .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')"; 
		//echo $search;		
		return $search; 
	} 


	public function get_posts($keyword,$query_array=''){
		if(empty($keyword)){
			return;
		}
		global $wpdb;
		if(empty($keyword)){
			return;
		}
		$post_max = ( $wxlog_post_max = get_option( 'wxlog_post_max' ) ) ? $wxlog_post_max : '5';
		$where = " 1=1 AND (((post_title LIKE '%".$keyword."%') OR (post_content LIKE '%".$keyword."%'))) AND post_type IN ('post', 'page', 'attachment') AND (post_status = 'publish' OR post_author = 1 AND post_status = 'private') ";
		$order = "post_title LIKE '%".$keyword."%' DESC, post_date DESC";
		
		if($keyword==get_option( 'wxlog_post_new' )){
		 	$where = " 1=1 AND post_type IN ('post', 'page', 'attachment') AND (post_status = 'publish' OR post_author = 1 AND post_status = 'private') ";
		 	$order = "post_date DESC";
		}		
		
		if($keyword==get_option( 'wxlog_post_hot' ) or $keyword==get_option( 'wxlog_post_hot_comment' ) or in_array($keyword,explode(',',str_replace('，',',',get_option( 'wxlog_post_category' ))))){
		 	return $this->get_posts_old($keyword);
		}
				
		$post_array = $wpdb->get_results( "SELECT * FROM wp_posts  WHERE ".$where." ORDER BY ".$order." Limit ".$post_max );
		//echo '<pre>';print_r($post_array);
		$post_total = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE ".$where.";" );
		//echo '<pre>';print_r($post_total);
		foreach($post_array as $key=>$post){
			if($contentArr){
				$contentArr[$key]['image_url'] = get_post_wxlog_thumb($post, array(80,80));
			}else{
				$contentArr[$key]['image_url'] = get_post_wxlog_thumb($post, array(640,320));
			}
			$contentArr[$key]['title'] = $post->post_title;
			$contentArr[$key]['description'] = get_post_wxlog_excerpts($post,150);
			$contentArr[$key]['url'] = $post->guid;			
		}
		if($post_total>$post_max){
			$contentArr[$post_max-1]['image_url'] = $contentArr[$post_max-2]['image_url'];
			$contentArr[$post_max-1]['title'] = '查看更多...';
			$contentArr[$post_max-1]['description'] = 'more...';
			$contentArr[$post_max-1]['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/?s='.$keyword;	
		}
		//print_r($_SERVER);
		return $contentArr;
	}


	//查询数据库
	public function get_posts_old($keyword,$query_array=''){
		if(empty($keyword)){
			return;
		}
		$post_max = ( $wxlog_post_max = get_option( 'wxlog_post_max' ) ) ? $wxlog_post_max : '5';
		if($query_array){
			
		}else{
			switch ($keyword) {
				case get_option( 'wxlog_post_new' ) :
					break;
				case get_option( 'wxlog_post_hot' ) :
					if(get_option( 'wxlog_post_hot_field_name' )){
						$query_array['meta_key'] = get_option( 'wxlog_post_hot_field_name' );//访问统计的字段名
						$query_array['orderby'] = 'meta_value_num';
						$query_array['order'] = 'DESC';
						break;
					}
				case get_option( 'wxlog_post_hot_comment' ) :
					$query_array['orderby'] = 'comment_count';
					$query_array['order'] = 'DESC';
					break;
				case in_array($keyword,explode(',',str_replace('，',',',get_option( 'wxlog_post_category' )))) :
					$query_array['cat'] = $keyword;
					break;
				default :
					$query_array['s'] = $keyword;
					break;
			}//print_r($query_array);
			
			$query_array['showposts'] = $post_max;
			$query_array['posts_per_page'] = $post_max;
			$query_array['post_status'] = 'publish';
			$query_array['ignore_sticky_posts'] = 1;
			
			$post_type = get_option( 'wxlog_post_type' );
			if($post_type){
				$query_array['post_type'] = explode(',',$post_type);
			}
			
			if(!$query_array['order']){
				$query_array['orderby'] = 'modified';
				$query_array['order'] = 'DESC';
			}
			
			$tags = explode('@',$keyword);					
			if($tags[1]){
				$query_array['tag'] = $tags[1];
				$query_array['s'] = $tags[0];
			}
		}
		
		/*$query = new WP_Query($query_array);
		
		$contentArr = '';
		$i = 0;
		if($query->have_posts()){
			while ($query->have_posts()) {
				$query->the_post();
				global $post;
				if($contentArr){
					$contentArr[$i]['image_url'] = get_post_wxlog_thumb($post, array(80,80));
				}else{
					$contentArr[$i]['image_url'] = get_post_wxlog_thumb($post, array(640,320));
				}
				$contentArr[$i]['title'] = get_the_title();
				$contentArr[$i]['description'] = get_post_wxlog_excerpts($post,150);
				$contentArr[$i]['url'] = get_permalink();
				$i++;
			}
		}*/

		
		$query = query_posts($query_array);
		if ( have_posts() ) : while ( have_posts() ) : the_post();

				global $post;
				if($contentArr){
					$contentArr[$i]['image_url'] = get_post_wxlog_thumb($post, array(80,80));
				}else{
					$contentArr[$i]['image_url'] = get_post_wxlog_thumb($post, array(640,320));
				}
				$contentArr[$i]['title'] = get_the_title();
				$contentArr[$i]['description'] = get_post_wxlog_excerpts($post,150);
				$contentArr[$i]['url'] = get_permalink();
				$i++;

		endwhile; else:

		endif;
		
		wp_reset_query();
		
		//$contentArr[1]['title'] .= $contentArr[1]['title'].$query_array['order'].'test';
		return $contentArr;
	}


	//小黄鸡智能机器人接口
	public function simsimi( $keyword ){
		if ( $keyword<>'' ){
			$curlPost = 'para='.$keyword;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://www.xiaohuangji.com/ajax.php');
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
			$return_str = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($return_str === false) {
				return false;//连接失败
			}
			if ($http_code !== 200) {
				return false;//连接失败
			}		
			curl_close($curl);
			if(md5($return_str)=='3f46a0d792a6a4104c11283d4768192d'){
				//抱歉，小黄鸡还不能理解，求您教我,使用"问...答....."句式(不准带标点~)
				return false;
			}
			return $return_str;
		}return false;
	}

	//公众平台绑定验证
    public function valid(){ 
        $echoStr = $_GET["echostr"];  
        if($this->checkSignature()){ 
            echo $echoStr; 
            exit; 
        } 
    } 

    public function checkSignature() { 
        $signature = $_GET["signature"]; 
        $timestamp = $_GET["timestamp"]; 
        $nonce = $_GET["nonce"]; 
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);//print_r($tmpArr);
        sort($tmpArr,SORT_STRING);//print_r($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
  
        if( $tmpStr == $signature ){ 
            return true; 
        }else{ 
            return false; 
        } 
    } 


	//你可以设FuncFlag字段为1来对消息进行星标，你可以在实时消息的星标消息分类中找到该消息
	//回复文本消息
    public function reply_text($toUsername, $fromUsername, $contentStr){
		$textTpl = "<xml>
<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
<CreateTime>".current_time( 'timestamp' )."</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";
		return sprintf($textTpl, str_replace('\r\n',"\r\n",wxlog_emoji(wxlog_qqface($contentStr))));
	}

	//回复图片消息
    public function reply_image($toUsername, $fromUsername, $contentArr){
		$imageTpl = "<xml>
<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
<CreateTime>".current_time( 'timestamp' )."</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<PicUrl><![CDATA[".$contentArr['PicUrl']."]]></PicUrl>
<MediaId><![CDATA[".$contentArr['MediaId']."]]></MediaId>
</Image>
</xml>";
		return $imageTpl;
	}

	//回复语音消息
    public function reply_voice($toUsername, $fromUsername, $contentStr){
		$voiceTpl = "官方未开放";
		return sprintf($voiceTpl, $contentStr);
	}
	
	
	//回复视频消息
    public function reply_video($toUsername, $fromUsername, $contentStr){
		$videoTpl = "官方未开放";
		return sprintf($videoTpl, $contentStr);
	}	


	//回复音乐消息
    public function reply_music($toUsername, $fromUsername, $contentArr){
		$musicTpl = "<xml>
<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
<CreateTime>".current_time( 'timestamp' )."</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<HQMusicUrl><![CDATA[".$contentArr['image_url']."]]></HQMusicUrl>
<MusicUrl><![CDATA[".$contentArr['url']."]]></MusicUrl>
<Description><![CDATA[".$contentArr['description']."]]></Description>
<Title><![CDATA[".$contentArr['title']."]]></Title>
</Music>
<FuncFlag>0</FuncFlag>
</xml>";
		return $musicTpl;
	}	

	//回复图文消息
    public function reply_news($toUsername, $fromUsername, $contentArr){
		$contentStr = '';
		foreach($contentArr as $value){
			if(!$value['description']) $value['description'] = $value['title'];
			if($value['image_url']){
			$contentStr .= '<item>
<Url><![CDATA['.$value['url'].']]></Url>
<PicUrl><![CDATA['.$value['image_url'].']]></PicUrl>
<Description><![CDATA['.html_entity_decode($value['description'], ENT_QUOTES, "utf-8" ).']]></Description>
<Title><![CDATA['.html_entity_decode($value['title'], ENT_QUOTES, "utf-8" ).']]></Title>
</item>';
			}else{
			$contentStr .= '<item>
<Url><![CDATA['.$value['url'].']]></Url>
<Description><![CDATA['.html_entity_decode($value['description'], ENT_QUOTES, "utf-8" ).']]></Description>
<Title><![CDATA['.html_entity_decode($value['title'], ENT_QUOTES, "utf-8" ).']]></Title>
</item>';
			}
		}
		$newsTpl = "<xml>
<ToUserName><![CDATA[".$toUsername."]]></ToUserName>
<FromUserName><![CDATA[".$fromUsername."]]></FromUserName>
<CreateTime>".current_time( 'timestamp' )."</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%d</ArticleCount>
<Articles>
%s
</Articles>
<FuncFlag>1</FuncFlag>
</xml>";
		return sprintf($newsTpl, count($contentArr), $contentStr);
	}

}


$WL = new WL();