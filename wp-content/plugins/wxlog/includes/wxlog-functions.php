<?php

if(!function_exists('wxlog_emoji')){
	function wxlog_emoji($content,$html='') {
		/*表情替换*/
		$f = ',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,';
		$r = "笑脸 开心 大笑 热情 眨眼 色 接吻 亲吻 脸红 露齿笑 满意 戏弄 吐舌 无语 得意 汗 失望 低落 呸 焦虑 担心 震惊 悔恨 眼泪 哭 破涕为笑 晕 恐惧 心烦 生气 睡觉 生病 恶魔 外星人 心 心碎 丘比特 闪烁 星星 叹号 问号 睡着 水滴 音乐 火 便便 强 弱 拳头 胜利 上 下 右 左 第一 强壮 吻 热恋 男孩 女孩 女士 男士 天使 骷髅 红唇 太阳 下雨 多云 雪人 月亮 闪电 海浪 猫 小狗 老鼠 仓鼠 兔子 狗 青蛙 老虎 考拉 熊 猪 牛 野猪 猴子 马 蛇 鸽子 鸡 企鹅 毛虫 章鱼 鱼 鲸鱼 海豚 玫瑰 花 棕榈树 仙人掌 礼盒 南瓜灯 鬼魂 圣诞老人 圣诞树 礼物 铃 庆祝 气球 CD 相机 录像机 电脑 电视 电话 解锁 锁 钥匙 成交 灯泡 邮箱 浴缸 钱 炸弹 手枪 药丸 橄榄球 篮球 足球 棒球 高尔夫 奖杯 入侵者 唱歌 吉他 比基尼 皇冠 雨伞 手提包 口红 戒指 钻石 咖啡 啤酒 干杯 鸡尾酒 汉堡 薯条 意面 寿司 面条 煎蛋 冰激凌 蛋糕 苹果 飞机 火箭 自行车 高铁 警告 旗 男人 女人 O X 版权 注册商标 商标 公交";
		$i =  '1f604,1f60a,1f603,263a,1f609,1f60d,1f618,1f61a,1f633,1f63c,1f60c,1f61c,1f445,1f612,1f60f,1f613,1f640,1f61e,1f616,1f625,1f630,1f628,1f62b,1f622,1f62d,1f602,1f632,1f631,1f620,1f63e,1f62a,1f637,1f47f,1f47d,2764,1f494,1f498,2728,1f31f,2755,2754,1f4a4,1f4a6,1f3b5,1f525,1f4a9,1f44d,1f44e,1f44a,270c,1f446,1f447,1f449,1f448,261d,1f4aa,1f48f,1f491,1f466,1f467,1f469,1f468,1f47c,1f480,1f48b,2600,2614,2601,26c4,1f319,26a1,1f30a,1f431,1f429,1f42d,1f439,1f430,1f43a,1f438,1f42f,1f428,1f43b,1f437,1f42e,1f417,1f435,1f434,1f40d,1f426,1f414,1f427,1f41b,1f419,1f420,1f433,1f42c,1f339,1f33a,1f334,1f335,1f49d,1f383,1f47b,1f385,1f384,1f381,1f514,1f389,1f388,1f4bf,1f4f7,1f3a5,1f4bb,1f4fa,1f4de,1f513,1f512,1f511,1f528,1f4a1,1f4eb,1f6c0,1f4b2,1f4a3,1f52b,1f48a,1f3c8,1f3c0,26bd,26be,26f3,1f3c6,1f47e,1f3a4,1f3b8,1f459,1f451,1f302,1f45c,1f484,1f48d,1f48e,2615,1f37a,1f37b,1f377,1f354,1f35f,1f35d,1f363,1f35c,1f373,1f366,1f382,1f34f,2708,1f680,1f6b2,1f684,26a0,1f3c1,1f6b9,1f6ba,2b55,274e,a9,ae,2122,1f68c';		
		$f = explode(',',$f);
		$r = explode(' ',$r);
		$i = explode(',',$i);
		foreach($r as $key=>$val){
			$rr[] = '<'.$val.'>';
			$ii[] =  '<span class="emoji emoji'.$i[$key].'"></span>';
		}
		if($html){
			return str_replace($f,$ii,$content);		
		}else{
			return str_replace($rr,$f,$content);		
		}
	}
	
}


/**
 * 判断是否是QQ表情
 * 
 * @param content
 * @return
 */
if(!function_exists('wxlog_qqface')){
	//print_r(wxlog_qqface('[微笑][美女]'));
	//print_r(wxlog_qqface('/::)/::B','img'));
	function wxlog_qqface($content,$img='') {
		/*表情替换*/
		$f = array('/::)','/::~','/::B','/::|','/:8-)','/::<','/::$','/::X','/::Z','/::\'(','/::-|','/::@','/::P','/::D','/::O','/::(','/::+','/:Cb','/::Q','/::T','/:,@P','/:,@-D','/::d','/:,@o','/::g','/:|-)','/::!','/::L','/::>','/::,@','/:,@f','/::-S','/:?','/:,@x','/:,@@','/::8','/:,@!','/:!!!','/:xx','/:bye','/:wipe','/:dig','/:handclap','/:&-(','/:B-)','/:<@','/:@>','/::-O','/:>-|','/:P-(','/::\'|','/:X-)','/::*','/:@x','/:8*','/:pd','/:<W>','/:beer','/:basketb','/:oo','/:coffee','/:eat','/:pig','/:rose','/:fade','/:showlove','/:heart','/:break','/:cake','/:li','/:bome','/:kn','/:footb','/:ladybug','/:shit','/:moon','/:sun','/:gift','/:hug','/:strong','/:weak','/:share','/:v','/:@)','/:jj','/:@@','/:bad','/:lvu','/:no','/:ok','/:love','/:<L>','/:jump','/:shake','/:<O>','/:circle','/:kotow','/:turn','/:skip','/[]','/:#-0','/[]','/:kiss','/:<&','/:&>');
		$r = array('微笑','伤心','美女','发呆','墨镜','哭','羞','哑','睡','哭','囧','怒','调皮','笑','惊讶','难过','酷','汗','抓狂','吐','笑','快乐','奇','傲','饿','累','吓','汗','高兴','闲','努力','骂','疑问','秘密','乱','疯','哀','鬼','打击','bye','汗','抠','鼓掌','糟糕','恶搞','什么','什么','累','看','难过','难过','坏','亲','吓','可怜','刀','水果','酒','篮球','乒乓','咖啡','美食','动物','鲜花','枯','唇','爱','分手','生日','电','炸弹','刀','足球','虫','臭','月亮','太阳','礼物','伙伴','赞','差','握手','优','恭','勾','顶','坏','爱','不','好的','爱','吻','跳','怕','尖叫','圈','拜','回头','跳','天使','激动','舞','吻','瑜伽','太极');
		$i = array();
		//echo count($r);
		foreach($r as $key=>$val){
			$rr[] = '['.$val.']';
			//$ii[] =  '<img src="https://wx.qq.com/zh_CN/htmledition/images/qqface/'.$key.'.png" />';
			$ii[] =  '<img src="/wp-content/plugins/wxlog/includes/admin/images/qqface/'.$key.'.png" />';
		}
		if($img){
			return str_replace($f,$ii,$content);		
		}else{
			return str_replace($rr,$f,$content);		
		}
	}

}


if(!function_exists('wxlog_make_signature')){
	function wxlog_make_signature($TOKEN) {
        $timestamp = current_time( 'timestamp' ); 
        $nonce = intval($timestamp+3600*24*7); 
        $token = $TOKEN; 
        $tmpArr = array($token, $timestamp, $nonce);//print_r($tmpArr);
        sort($tmpArr,SORT_STRING);//print_r($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
  		return array($tmpStr,$timestamp,$nonce);
	}
}
if(!function_exists('wxlog_xml_to_array')){
	function wxlog_xml_to_array($xml){
		$postObj = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		//echo $xml;
		//var_dump($postObj);
		$arr['ToUserName'] = trim(@$postObj->ToUserName);
		$arr['FromUserName'] = trim(@$postObj->FromUserName);
		$arr['CreateTime'] = trim(@$postObj->CreateTime);
		$arr['MsgType'] = trim(@$postObj->MsgType);
		if($arr['MsgType']=='text'){
			$arr['Content'] = trim(@$postObj->Content);
		}
		if($arr['MsgType']=='image'){
			if(@$postObj->Image->PicUrl){
				$arr['PicUrl'] = trim(@$postObj->Image->PicUrl);
				$arr['MediaId'] = trim(@$postObj->Image->MediaId);
			}else{
				$arr['PicUrl'] = trim(@$postObj->PicUrl);
			}
			$arr['Content'] = $arr['PicUrl'];
		}
		if($arr['MsgType']=='voice'){
			$arr['MediaId'] = trim(@$postObj->MediaId);
			$arr['Format'] = trim(@$postObj->Format);
			//服务号必须开通语音识别功能
			$arr['Recognition'] = trim(@$postObj->Recognition);
			$arr['Content'] = $arr['Recognition'];
		}
		if($arr['MsgType']=='video'){
			$arr['MediaId'] = trim(@$postObj->MediaId);
			$arr['ThumbMediaId'] = trim(@$postObj->ThumbMediaId);
		}
		if($arr['MsgType']=='music'){
			$arr['HQMusicUrl'] = trim(@$postObj->Music->HQMusicUrl);
			$arr['MusicUrl'] = trim(@$postObj->Music->MusicUrl);
			$arr['Description'] = trim(@$postObj->Music->Description);
			$arr['Title'] = trim(@$postObj->Music->Title);
		}
		if($arr['MsgType']=='location'){
			$arr['Location_X'] = trim(@$postObj->Location_X);
			$arr['Location_Y'] = trim(@$postObj->Location_Y);
			$arr['Scale'] = trim(@$postObj->Scale);
			$arr['Label'] = trim(@$postObj->Label);
			$arr['Content'] = 'location_'.$arr['Location_X'].'_'.$arr['Location_Y'].'_'.$arr['Scale'].'_'.$arr['Label'];
		}
		if($arr['MsgType']=='link'){
			$arr['Title'] = trim(@$postObj->Title);
			$arr['Description'] = trim(@$postObj->Description);
			$arr['Url'] = trim(@$postObj->Url);
		}
		if($arr['MsgType']=='event'){
			$arr['Event'] = trim(@$postObj->Event);
			$arr['EventKey'] = trim(@$postObj->EventKey);
			$arr['Ticket'] = trim(@$postObj->Ticket);
			if($arr['event']!='LOCATION'){
				$arr['Latitude'] = trim(@$postObj->Latitude);
				$arr['Longitude'] = trim(@$postObj->Longitude);
				$arr['Precision'] = trim(@$postObj->Precision);
			}
			$event = strtolower($arr['Event']);
			if($event == 'subscribe' || $event == 'unsubscribe'){ //订阅和取消订阅
				$arr['Content'] = $event;
			}elseif($event == 'click'){	//点击事件
				$arr['Content'] = strtolower($arr['EventKey']);
			}elseif($event == 'view'){	//查看网页事件

			}
		}
		if($arr['MsgType']!='event'){
			$arr['MsgId'] = trim(@$postObj->MsgId);
		}
		//print_r($arr);
		return $arr;
	}
}

if(!function_exists('wxlog')){
	function wxlog($toppath,$logs){
	  $Ts=fopen($toppath,"a+");
	  fputs($Ts,$logs."\r\n");
	  fclose($Ts);
	}
}


//判断当前用户是否为微信用户
if(!function_exists('is_wxlog_weixin')){
	function is_wxlog_weixin(){
		if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
			if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
				return true;
			}
		}
		return false;
	}
}

if(!function_exists('get_post_wxlog_excerpts')){
    //获取日志摘要
    function get_post_wxlog_excerpts($post, $excerpt_length=240){
        if(!$post) $post = get_post();
        $post_excerpt = $post->post_excerpt;
        if($post_excerpt == ''){
            $post_content = $post->post_content;
            $post_content = do_shortcode($post_content);
            $post_content = wp_strip_all_tags( $post_content );
            $excerpt_length = apply_filters('excerpt_length', $excerpt_length);
            $excerpt_more   = apply_filters('excerpt_more', ' ' . '&hellip;');
            $post_excerpt = mb_strimwidth($post_content,0,$excerpt_length,$excerpt_more,'utf-8');
        }
        $post_excerpt = wp_strip_all_tags( $post_excerpt );
        $post_excerpt = trim( preg_replace( "/[\n\r\t ]+/", ' ', $post_excerpt ), ' ' );
        return $post_excerpt;
    }
}


if(!function_exists('get_post_wxlog_first_image')){
	function get_post_wxlog_first_image($post_content){
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches);
		if($matches){
			return $matches[1][0];
		}else{
			return false;
		}
	}
}

if(!function_exists('get_post_wxlog_thumb')){
	function get_post_wxlog_thumb($post,$size){
		$thumbnail_id = get_post_thumbnail_id($post->ID);
		if($thumbnail_id){
			$thumb = wp_get_attachment_image_src($thumbnail_id, $size);
			$thumb = $thumb[0];
		}else{
			$thumb = get_post_wxlog_first_image($post->post_content);
		}
		if(empty($thumb)){
			$thumb = get_option('wxlog_default_image');
		}
		$thumb = apply_filters('weixin_thumb',$thumb,$size,$post);
		return $thumb;
	}
}


if(!function_exists('get_wxlog_plugin_data')){

	//获取插件数据
	function get_wxlog_plugin_data( $plugin_file, $markup = true, $translate = true ) {

		$default_headers = array(
			'Name' => 'Plugin Name',
			'PluginURI' => 'Plugin URI',
			'Version' => 'Version',
			'Description' => 'Description',
			'Author' => 'Author',
			'AuthorURI' => 'Author URI',
			'TextDomain' => 'Text Domain',
			'DomainPath' => 'Domain Path',
			'Network' => 'Network',
			'ClassName' => 'ClassName',
			// Site Wide Only is deprecated in favor of Network.
			'_sitewide' => 'Site Wide Only',
		);

		$plugin_data = get_file_data( $plugin_file, $default_headers, 'plugin' );

		// Site Wide Only is the old header for Network
		if ( ! $plugin_data['Network'] && $plugin_data['_sitewide'] ) {
			_deprecated_argument( __FUNCTION__, '3.0', sprintf( __( 'The <code>%1$s</code> plugin header is deprecated. Use <code>%2$s</code> instead.' ), 'Site Wide Only: true', 'Network: true' ) );
			$plugin_data['Network'] = $plugin_data['_sitewide'];
		}
		$plugin_data['Network'] = ( 'true' == strtolower( $plugin_data['Network'] ) );
		unset( $plugin_data['_sitewide'] );

		if ( $markup || $translate ) {
			$plugin_data = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, $markup, $translate );
		} else {
			$plugin_data['Title']      = $plugin_data['Name'];
			$plugin_data['AuthorName'] = $plugin_data['Author'];
		}

		return $plugin_data;
	}
}

if(!function_exists('get_wxlog_plugins')){
	//获取插件列表
	function get_wxlog_plugins($plugin_folder = '') {

		if ( ! $cache_plugins = wp_cache_get('plugins', 'plugins') )
			$cache_plugins = array();

		if ( isset($cache_plugins[ $plugin_folder ]) )
			return $cache_plugins[ $plugin_folder ];

		$wp_plugins = array ();
		$plugin_root = WP_PLUGIN_DIR;
		if ( !empty($plugin_folder) )
			$plugin_root .= $plugin_folder;

		// Files in wp-content/plugins directory
		$plugins_dir = @ opendir( $plugin_root);
		$plugin_files = array();
		if ( $plugins_dir ) {
			while (($file = readdir( $plugins_dir ) ) !== false ) {
				if ( substr($file, 0, 1) == '.' )
					continue;
				if ( is_dir( $plugin_root.'/'.$file ) ) {
					$plugins_subdir = @ opendir( $plugin_root.'/'.$file );
					if ( $plugins_subdir ) {
						while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
							if ( substr($subfile, 0, 1) == '.' )
								continue;
							if ( substr($subfile, -4) == '.php' )
								$plugin_files[] = "$file/$subfile";
						}
						closedir( $plugins_subdir );
					}
				} else {
					if ( substr($file, -4) == '.php' )
						$plugin_files[] = $file;
				}
			}
			closedir( $plugins_dir );
		}

		if ( empty($plugin_files) )
			return $wp_plugins;

		foreach ( $plugin_files as $plugin_file ) {
			if ( !is_readable( "$plugin_root/$plugin_file" ) )
				continue;

			$plugin_data = get_wxlog_plugin_data( "$plugin_root/$plugin_file", false, false ); //Do not apply markup/translate as it'll be cached.
			if ( empty ( $plugin_data['Name'] ) )
				continue;

			$wp_plugins[plugin_basename( $plugin_file )] = $plugin_data;
		}

		@uasort( $wp_plugins, '_sort_uname_callback' );//插件排序

		$cache_plugins[ $plugin_folder ] = $wp_plugins;
		wp_cache_set('plugins', $cache_plugins, 'plugins');

		return $wp_plugins;
	}
}

if(!function_exists('is_wxlog_plugin')){
	//判断是否为WWY插件
	function is_wxlog_plugin( $plugin ) {
		if(in_array($plugin['ClassName'],array('wxlog_baiyin','wxlog_weather','wxlog_location','wxlog_mobile','wxlog_stock','wxlog_baike','wxlog_domain','wxlog_caipiao','wxlog_shenfenzheng','wxlog_bus','wxlog_train','wxlog_chouqian','wxlog_ip','wxlog_anquanqi','wxlog_ip','wxlog_fanyi'))){
			//return false;
		}
		if($plugin['ClassName']){
			return true;
		}else{
			return false;
		}
		static $term;
		if ( is_null( $term ) )
			$term = wp_unslash( 'wxlog_' );

		foreach ( $plugin as $value )
			if ( stripos( $value, $term ) !== false )
				return true;

		return false;
	}
}

if(!function_exists('is_active_wxlog_plugin')){
	//判断插件是否激活
	function is_active_wxlog_plugin( $file ) {
		if ( in_array( $file, (array) get_option( 'active_plugins', array() ) ) )
			return true;

		return false;
	}
}

if(!function_exists('wxlog_get_xml')){
	function wxlog_get_xml($url,$xmlData){
		//第一种发送方式，也是推荐的方式：
		$header[] = "Content-type: text/xml";        //定义content-type为xml,注意是数组
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3.6; zh-cn; GT-S5660 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1 MicroMessenger/4.5.255');
		$response = curl_exec($ch);
		if(curl_errno($ch)){
			print curl_error($ch);
		}
		curl_close($ch);
		return $response;
	}
}

if(!function_exists('WXLOG_XML_unserialize')){
	function WXLOG_XML_unserialize($xml){
		$xml_parser = new WXLOG_XML();
		$data = $xml_parser->parse($xml);
		$xml_parser->destruct();
		return $data;
	}
}
class WXLOG_XML{
    var $parser;   #a reference to the XML parser
    var $document; #the entire XML structure built up so far
    var $parent;   #a pointer to the current parent - the parent will be an array
    var $stack;    #a stack of the most recent parent at each nesting level
    var $last_opened_tag; #keeps track of the last tag opened.

    function WXLOG_XML(){
        $this->parser = xml_parser_create();
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'open','close');
        xml_set_character_data_handler($this->parser, 'data');
    }
    function destruct(){ xml_parser_free($this->parser); }
    function parse($data){
        $this->document = array();
        $this->stack    = array();
        $this->parent   = &$this->document;
        return xml_parse($this->parser, $data, true) ? $this->document : NULL;
    }
    function open($parser, $tag, $attributes){
        $this->data = ''; #stores temporary cdata
        $this->last_opened_tag = $tag;
        if(is_array($this->parent) and array_key_exists($tag,$this->parent)){ #if you've seen this tag before
            if(is_array($this->parent[$tag]) and array_key_exists(0,$this->parent[$tag])){ #if the keys are numeric
                #this is the third or later instance of $tag we've come across
                $key = $this->count_numeric_items($this->parent[$tag]);
            }else{
                #this is the second instance of $tag that we've seen. shift around
                if(array_key_exists("$tag attr",$this->parent)){
                    $arr = array('0 attr'=>&$this->parent["$tag attr"], &$this->parent[$tag]);
                    unset($this->parent["$tag attr"]);
                }else{
                    $arr = array($this->parent[$tag]);
                }
                $this->parent[$tag] = &$arr;
                $key = 1;
            }
            $this->parent = &$this->parent[$tag];
        }else{
            $key = $tag;
        }
        if($attributes) $this->parent["$key attr"] = $attributes;
        $this->parent  = &$this->parent[$key];
        $this->stack[] = &$this->parent;
    }
    function data($parser, $data){
        if($this->last_opened_tag != NULL) #you don't need to store whitespace in between tags
            $this->data .= $data;
    }
    function close($parser, $tag){
        if($this->last_opened_tag == $tag){
            $this->parent = $this->data;
            $this->last_opened_tag = NULL;
        }
        array_pop($this->stack);
        if($this->stack) $this->parent = &$this->stack[count($this->stack)-1];
    }
	function count_numeric_items(&$array){
		return is_array($array) ? count(array_filter(array_keys($array), 'is_numeric')) : 0;
	}
}