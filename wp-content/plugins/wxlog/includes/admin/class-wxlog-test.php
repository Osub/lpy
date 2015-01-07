<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WXLOG_Test {

	public function __construct() {
		parse_str($_SERVER['QUERY_STRING'], $queryVars);
		if(isset($queryVars['preview']) && intval($queryVars['preview']) >0){
			include_once( 'class-wxlog-test-preview.php' );
			$WXLOG_Test_preview = new WXLOG_Test_preview();
			$WXLOG_Test_preview->preview_page();
			die;
		}
		
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
	}

	//管理菜单
	public function admin_menu() {
		add_submenu_page( 'wxlog_log', '调试', '调试', 'administrator', 'wxlog_test', array( $this, 'wxlog_test_page' ) );
	}

	//调试页面内容
	public function wxlog_test_page() {
		global $wpdb;
		$signature_timestamp_nonce = wxlog_make_signature(TOKEN);
		$wxlog_log = $wpdb->get_row("SELECT * FROM {$wpdb->wxlog_log} WHERE 1=1 order by ID asc");
		$message = wxlog_xml_to_array($wxlog_log->message);//print_r($message); 
		?>

<div class="wrap">
    <form name="form1" target="_blank" action='' method="POST">
      <input type="hidden" name="test" value="test" >
    <!-- Screen icons are no longer used as of WordPress 3.8. -->
    <h2 class="nav-tab-wrapper"> <a class="nav-tab nav-tab-active" href="#settings-general">调试</a><a class="nav-tab" href="?page=wxlog_setting">基本设置</a> </h2>
    <br>
    <div class="settings_panel" id="settings-general" style="display: block;">
      <table class="form-table">
      
        <tbody>
          <tr valign="top">
            <th scope="row"><label>消息类型:</label></th>
            <td><select class="regular-text" onchange="get_msgtype(this.value);" id="MsgType">
              <option value="text">文本类型</option>
              <option value="image">图片类型</option>
              <option value="location">位置类型</option>
              <option value="voice">语音类型</option>
              <option value="event">事件类型</option>
            </select></td>
          </tr>
          <tr valign="top">
            <th scope="row"><label>ToUserName:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="ToUserName" type="text" value="<?=$message['ToUserName']?>"></td>
          </tr>
          <tr valign="top">
            <th scope="row"><label>FromUserName:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="FromUserName" type="text" value="<?=$message['FromUserName']?>"></td>
          </tr>
          <tr valign="top" id="div_Content">
            <th scope="row"><label>Content:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="Content" type="text" value="TEST"></td>
          </tr>
          <tr valign="top" id="div_PicUrl">
            <th scope="row"><label>PicUrl:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="PicUrl" type="text"></td>
          </tr>
          
          <tr valign="top" id="div_Location_X">
            <th scope="row"><label>Location_X:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="Location_X" type="text" value="31.230416"></td>
          </tr>
          
          <tr valign="top" id="div_Location_Y">
            <th scope="row"><label>Location_Y:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="Location_Y" type="text" value="121.473701"></td>
          </tr>
          
          <tr valign="top" id="div_Label">
            <th scope="row"><label>Label:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="Label" type="text" value="上海"></td>
          </tr>
          
          <tr valign="top" id="div_MediaId">
            <th scope="row"><label>MediaId:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="MediaId" type="text" value=""></td>
          </tr>
          
          <tr valign="top" id="div_Recognition">
            <th scope="row"><label>Recognition:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="Recognition" type="text" value=""></td>
          </tr>
          
          <tr valign="top" id="div_Event">
            <th scope="row"><label>Event:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="Event" type="text" value="subscribe">
            <p class="description">可以填 <code>subscribe</code> 或 <code>unsubscribe</code> 或 <code>click</code> 或 <code>view</code></p>
            </td>
          </tr>         
          
          <tr valign="top" id="div_EventKey">
            <th scope="row"><label>EventKey:</label></th>
            <td><input onkeyup="get_msgtype(document.getElementById('MsgType').value);" class="regular-text" id="EventKey" type="text" value=""></td>
          </tr> 
                  
          <tr valign="top" style="display:none;">
            <th scope="row"><label>HTTP_RAW_POST_DATA:</label></th>
            <td><textarea name="HTTP_RAW_POST_DATA" class="large-text" id="HTTP_RAW_POST_DATA" rows="5" cols="50"></textarea></td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="submit">
		<input class="button-primary" id="submit" type="submit" value="预览">      
		<input style="display:noness;" class="button" type="submit" value="调用" onclick="document.form1.action = '<?=site_url()?>/?<?=TOKEN?>&signature=<?=$signature_timestamp_nonce[0]?>&timestamp=<?=$signature_timestamp_nonce[1]?>&nonce=<?=$signature_timestamp_nonce[2]?>';">
    </p>
  </form>

<script>
function get_msgtype(msgtype){
	
	if(msgtype=='text'){
		document.getElementById('HTTP_RAW_POST_DATA').value = '<xml><ToUserName><![CDATA['+document.getElementById('ToUserName').value+']]></ToUserName>'+"\n"+
'<FromUserName><![CDATA['+document.getElementById('FromUserName').value+']]></FromUserName>'+"\n"+
'<CreateTime><?=$signature_timestamp_nonce[1]?></CreateTime>'+"\n"+
'<MsgType><![CDATA[text]]></MsgType>'+"\n"+
'<Content><![CDATA['+document.getElementById('Content').value+']]></Content>'+"\n"+
'<MsgId>1000000000000000000</MsgId>'+"\n"+
'</xml>';
		document.getElementById('div_Content').style.display="";
		document.getElementById('div_PicUrl').style.display="none";
		document.getElementById('div_Location_X').style.display="none";
		document.getElementById('div_Location_Y').style.display="none";
		document.getElementById('div_Label').style.display="none";
		document.getElementById('div_MediaId').style.display="none";
		document.getElementById('div_Recognition').style.display="none";
		document.getElementById('div_Event').style.display="none";
		document.getElementById('div_EventKey').style.display="none";
	}
	
	if(msgtype=='image'){
		document.getElementById('HTTP_RAW_POST_DATA').value = '<xml><ToUserName><![CDATA['+document.getElementById('ToUserName').value+']]></ToUserName>'+"\n"+
'<FromUserName><![CDATA['+document.getElementById('FromUserName').value+']]></FromUserName>'+"\n"+
'<CreateTime><?=$signature_timestamp_nonce[1]?></CreateTime>'+"\n"+
'<MsgType><![CDATA[image]]></MsgType>'+"\n"+
'<PicUrl><![CDATA['+document.getElementById('PicUrl').value+']]></PicUrl>'+"\n"+
'<MediaId><![CDATA['+document.getElementById('MediaId').value+']]></MediaId>'+"\n"+
'<MsgId>2000000000000000000</MsgId>'+"\n"+
'</xml>';
		document.getElementById('div_Content').style.display="none";
		document.getElementById('div_PicUrl').style.display="";
		document.getElementById('div_Location_X').style.display="none";
		document.getElementById('div_Location_Y').style.display="none";
		document.getElementById('div_Label').style.display="none";
		document.getElementById('div_MediaId').style.display="";
		document.getElementById('div_Recognition').style.display="none";
		document.getElementById('div_Event').style.display="none";
		document.getElementById('div_EventKey').style.display="none";
	}	
		
	if(msgtype=='location'){
		document.getElementById('HTTP_RAW_POST_DATA').value = '<xml><ToUserName><![CDATA[<?=get_option( 'wxlog_weixin_id' )?>]]></ToUserName>'+"\n"+
'<FromUserName><![CDATA[oqyDfjhCqyQFzzskAeu8pA5NLYmo]]></FromUserName>'+"\n"+
'<CreateTime><?=$signature_timestamp_nonce[1]?></CreateTime>'+"\n"+
'<MsgType><![CDATA[location]]></MsgType>'+"\n"+
'<Location_X>'+document.getElementById('Location_X').value+'</Location_X>'+"\n"+
'<Location_Y>'+document.getElementById('Location_Y').value+'</Location_Y>'+"\n"+
'<Scale>20</Scale>'+"\n"+
'<Label><![CDATA['+document.getElementById('Label').value+']]></Label>'+"\n"+
'<MsgId>3000000000000000000</MsgId>'+"\n"+
'</xml>';
		document.getElementById('div_Content').style.display="none";
		document.getElementById('div_PicUrl').style.display="none";
		document.getElementById('div_Location_X').style.display="";
		document.getElementById('div_Location_Y').style.display="";
		document.getElementById('div_Label').style.display="";
		document.getElementById('div_MediaId').style.display="none";
		document.getElementById('div_Recognition').style.display="none";
		document.getElementById('div_Event').style.display="none";
		document.getElementById('div_EventKey').style.display="none";
	}
		
	if(msgtype=='voice'){
		document.getElementById('HTTP_RAW_POST_DATA').value = '<xml><ToUserName><![CDATA[<?=get_option( 'wxlog_weixin_id' )?>]]></ToUserName>'+"\n"+
'<FromUserName><![CDATA[oqyDfjhCqyQFzzskAeu8pA5NLYmo]]></FromUserName>'+"\n"+
'<CreateTime><?=$signature_timestamp_nonce[1]?></CreateTime>'+"\n"+
'<MsgType><![CDATA[voice]]></MsgType>'+"\n"+
'<MediaId><![CDATA['+document.getElementById('MediaId').value+']]></MediaId>'+"\n"+
'<Format><![CDATA[amr]]></Format>'+"\n"+
'<Recognition><![CDATA['+document.getElementById('Recognition').value+']]></Recognition>'+"\n"+
'<MsgId>4000000000000000000</MsgId>'+"\n"+
'</xml>';
		document.getElementById('div_Content').style.display="none";
		document.getElementById('div_PicUrl').style.display="none";
		document.getElementById('div_Location_X').style.display="none";
		document.getElementById('div_Location_Y').style.display="none";
		document.getElementById('div_Label').style.display="none";
		document.getElementById('div_MediaId').style.display="";
		document.getElementById('div_Recognition').style.display="";
		document.getElementById('div_Event').style.display="none";
		document.getElementById('div_EventKey').style.display="none";
	}
	
	if(msgtype=='event'){
		document.getElementById('HTTP_RAW_POST_DATA').value = '<xml><ToUserName><![CDATA[<?=get_option( 'wxlog_weixin_id' )?>]]></ToUserName>'+"\n"+
'<FromUserName><![CDATA[oqyDfjhCqyQFzzskAeu8pA5NLYmo]]></FromUserName>'+"\n"+
'<CreateTime><?=$signature_timestamp_nonce[1]?></CreateTime>'+"\n"+
'<MsgType><![CDATA[event]]></MsgType>'+"\n"+
'<Event><![CDATA['+document.getElementById('Event').value+']]></Event>'+"\n"+
'<EventKey><![CDATA['+document.getElementById('EventKey').value+']]></EventKey>'+"\n"+
'</xml>';
		document.getElementById('div_Content').style.display="none";
		document.getElementById('div_PicUrl').style.display="none";
		document.getElementById('div_Location_X').style.display="none";
		document.getElementById('div_Location_Y').style.display="none";
		document.getElementById('div_Label').style.display="none";
		document.getElementById('div_MediaId').style.display="none";
		document.getElementById('div_Recognition').style.display="none";
		document.getElementById('div_Event').style.display="";
		document.getElementById('div_EventKey').style.display="";
	}

}

</script>
<script>get_msgtype('text');</script> 
</div>
<?php add_thickbox();
	global $WXLOG;
	$WXLOG->add_inline_js("
		jQuery('#submit').click(function() {
			tb_show('预览', '".admin_url( "admin.php?page=wxlog_test&preview=1&signature=".$signature_timestamp_nonce[0]."&timestamp=".$signature_timestamp_nonce[1]."&nonce=".$signature_timestamp_nonce[2]."&HTTP_RAW_POST_DATA='+jQuery(\"#HTTP_RAW_POST_DATA\").val()+'&amp;TB_iframe=true&amp;height=480&amp;width=520" )."');
			return false;	
		});				
	");}
}

new WXLOG_Test();