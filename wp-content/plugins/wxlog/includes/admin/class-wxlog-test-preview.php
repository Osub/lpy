<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WXLOG_Test_preview {

	public function __construct() {
	}

	//关于页面内容
	public function preview_page() {
		global $wpdb;
		$id = intval($_GET['preview']);
		if($id>0 and empty($_GET['signature'])){
			$wxlog_log = $wpdb->get_row("SELECT * FROM {$wpdb->wxlog_log} WHERE ID={$id}");
		}
		if($wxlog_log){
			$_GET['HTTP_RAW_POST_DATA'] = $wxlog_log->message;
			$_GET['timestamp'] = strtotime($wxlog_log->timestamp);
		}
		if($_GET['HTTP_RAW_POST_DATA']){
			$_GET['HTTP_RAW_POST_DATA'] = wxlog_emoji(wxlog_qqface($_GET['HTTP_RAW_POST_DATA']));
			$message = wxlog_xml_to_array($_GET['HTTP_RAW_POST_DATA']);//print_r($message);
		}else{exit('null');}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP日志-微信日志-预览</title>
<meta name="title" content="PHP日志-微信日志-预览">
<meta name="keywords" content="PHP日志-微信日志-预览">
<meta name="description" content="PHP日志-微信日志-预览" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/wxlog/includes/admin/css/preview.css">
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/wxlog/includes/admin/css/emoji.css">
<?php wp_enqueue_script('jquery');wp_head(); ?>
<?php if($zhangji){?>
<link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/zh_CN/htmledition/style/comm18f87a.css">
<?php }?>
<div id="container" style="rgba(0,0,0,0.5);" >
<div id="chat" class="chatPanel normalPanel" ctrl="1">
<div class="content" >
<div class="chat lightBorder" style="visibility: visible; ">
<div class="chatContainer" style="height: 480px; " >
<div class="rightOpBtn groupChat" id="rightOpBtn" click="toggleChatMgr" style="display: none; "><a href="javascript:;"></a></div>
<div class="backToChat" id="leftOpBtn" click="toggleChatMgr" style="display: none; "><a href="javascript:;"></a></div>
<div class="chatMainPanel" id="chatMainPanel" >
<div class="chatTitle">
  <div class="chatNameWrap">
    <p class="chatName" id="messagePanelTitle">PHP日志</p>
  </div>
</div>
<div class="chatScorll" style="position: relative; ">
  <div id="chat_chatmsglist" class="chatContent" ctrl="1" style="position: absolute; ">
    <div class="chatItem me" un="item_1390543792332">
      <div class="time"> <span class="timeBg left"></span>
        <?=date('Y-m-d H:i:s',$_GET['timestamp'])?>
        <span class="timeBg right"></span> </div>
      <div class="chatItemContent"> <img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">
        <div class="cloud cloudText" un="cloud_1020740958" msgid="1020740958">
          <div class="cloudPannel" style="">
            <div class="sendStatus"> </div>
            <div class="cloudBody">
              <div class="cloudContent">
                <pre style="white-space:pre-wrap"><?php if($message['MsgType']=='image'){echo '<img src="'.$message['PicUrl'].'" style="max-width:260px;">';}else{echo wxlog_emoji(wxlog_qqface($message['Content'],'img'),'html');}?>
</pre>
              </div>
            </div>
            <div class="cloudArrow "></div>
          </div>
        </div>
      </div>
    </div>
    <?php if($wxlog_log->reply){
				$content = $wxlog_log->reply;
			}else{
				$url = site_url().'/?'.TOKEN.'&signature='.$_GET['signature'].'&timestamp='.$_GET['timestamp'].'&nonce='.$_GET['nonce'];
				$_GET['HTTP_RAW_POST_DATA'] = wxlog_emoji(wxlog_qqface($_GET['HTTP_RAW_POST_DATA']));
				$content = wxlog_get_xml($url,$_GET['HTTP_RAW_POST_DATA']);
			}
			$reply = wxlog_xml_to_array($content);
			//echo '<pre>';
			//echo $content;
			//print_r($reply);
			if($reply['MsgType']=='text'){ 
				echo $this->reply_text($reply);
			}
			if($reply['MsgType']=='news'){
                $reply = WXLOG_XML_unserialize($content);
                if($reply['xml']['ArticleCount']==1){//$reply['xml']['Articles']['item'][0]=$reply['xml']['Articles']['item'];
                    $this->reply_news1($reply);
        		}else{
                    $this->reply_news2($reply);
        		}
        	}
        	if($reply['MsgType']=='image'){
                  $this->reply_image($reply);
        	}
        	if($reply['MsgType']=='music'){
			  $this->reply_music($reply);
    		}?>
  </div>
  <div class="scrollbarBox" style="position: absolute; right: 0px; top: 0px; height: 100%; ">
    <div class="scrollbar" style="position: absolute; right: 0px; top: 89px; opacity: 0; height: 42px; z-index: 0; display: block; "></div>
  </div>
</div>
<div id="chat_editor" class="chatOperator lightBorder" ctrl="1" >
<div class="inputArea">
<form>
<div class="attach"> <a href="javascript:;" id="sendEmojiIcon" class="func expression" onclick="showEmojiPanel();" title="选择表情" style=""></a> </div>
<textarea type="text" id="textInput" class="chatInput lightBorder"></textarea>
<a href="javascript:;" class="chatSend" click="sendMsg@.inputArea"><b>发送</b></a>
</div>
<div class="emojiPanel" style="display: none; " id="emojiPanel">
  <div style="position:absolute;"></div>
  <ul class="faceTab">
    <li> <a id="qqa" class="chooseFaceTab" href="javascript:;" click="qqface()" un="faceBox">QQ表情</a></li>
    <li><a id="emoa" href="javascript:;" click="emoface()" un="emojiBox">符号表情</a></li>
  </ul>
  <div class="faceWrap" style="zoom:1;outline:noneg;" tabindex="0" hidefocus="true"> 
    <script type="text/javascript">
jQuery(document).ready(function($) {

	jQuery('.inputArea .chatSend').click(function() {

var mydata = '<xml><ToUserName><![CDATA[<?=$message['ToUserName']?>]]></ToUserName>'+"\n"+
'<FromUserName><![CDATA[<?=$message['FromUserName']?>]]></FromUserName>'+"\n"+
'<CreateTime><?=$_GET['timestamp']?></CreateTime>'+"\n"+
'<MsgType><![CDATA[text]]></MsgType>'+"\n"+
'<Content><![CDATA['+document.getElementById("textInput").value+']]></Content>'+"\n"+
'<MsgId>1000000000000000000</MsgId>'+"\n"+
'</xml>';

	<?php $signature_timestamp_nonce = wxlog_make_signature(TOKEN);?>
	
		window.location='admin.php?page=wxlog_test&preview=1&signature=<?=$signature_timestamp_nonce[0]?>&timestamp=<?=$signature_timestamp_nonce[1]?>&nonce=<?=$signature_timestamp_nonce[2]?>&HTTP_RAW_POST_DATA='+mydata;
		
		/*var mydata = '<div class="chatItem me" un="item_1390543792332">'+
			  '<div class="time"> <span class="timeBg left"></span>'+
				'<?=$signature_timestamp_nonce[0]?>'+
				'<span class="timeBg right"></span> </div>'+
			  '<div class="chatItemContent"> <img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">'+
				'<div class="cloud cloudText" un="cloud_1020740958" msgid="1020740958">'+
				  '<div class="cloudPannel" style="">'+
					'<div class="sendStatus"> </div>'+
					'<div class="cloudBody">'+
					  '<div class="cloudContent">'+
						'<pre style="white-space:pre-wrap">'+document.getElementById("textInput").value+'</pre>'+
					  '</div>'+
					'</div>'+
					'<div class="cloudArrow "></div>'+
				  '</div>'+
				'</div>'+
			  '</div>'+
			'</div>';
	jQuery('#chat_chatmsglist').append(mydata);	*/	

	});	

	jQuery('#emojiPanel #qqa').click(function() {
		document.getElementById('faceBox').style.display='';
		document.getElementById('emojiBox').style.display='none';
		
		document.getElementById('qqa').className='chooseFaceTab';
		document.getElementById('emoa').className='';
	});				

	jQuery('#emojiPanel #emoa').click(function() {
		document.getElementById('faceBox').style.display='none';
		document.getElementById('emojiBox').style.display='';
		
		document.getElementById('qqa').className='';
		document.getElementById('emoa').className='chooseFaceTab';
	});				

	jQuery('#faceBox a').click(function() {
		document.getElementById('emojiPanel').style.display='none';
		document.getElementById('textInput').value=document.getElementById('textInput').value+'['+jQuery(this).attr("title")+']';
	});				

	jQuery('#emojiBox a').click(function() {
		document.getElementById('emojiPanel').style.display='none';
		document.getElementById('textInput').value=document.getElementById('textInput').value+'<'+jQuery(this).attr("title")+'>';
	});				

});

function showEmojiPanel(){
	document.getElementById('emojiPanel').style.display='';
}
</script>
    <div id="faceBox" class="faceBox emojiArea" style="display:;" click="chooseEmoji"> <a title="微笑" class="f14" href="javascript:;"></a><a title="撇嘴" class="f1" href="javascript:;"></a><a title="色" class="f2" href="javascript:;"></a><a title="发呆" class="f3" href="javascript:;"></a><a title="得意" class="f4" href="javascript:;"></a><a title="流泪" class="f5" href="javascript:;"></a><a title="害羞" class="f6" href="javascript:;"></a><a title="闭嘴" class="f7" href="javascript:;"></a><a title="睡" class="f8" href="javascript:;"></a><a title="大哭" class="f9" href="javascript:;"></a><a title="尴尬" class="f10" href="javascript:;"></a><a title="发怒" class="f11" href="javascript:;"></a><a title="调皮" class="f12" href="javascript:;"></a><a title="呲牙" class="f13" href="javascript:;"></a><a title="惊讶" class="f0 borderRightNone" href="javascript:;"></a><a title="难过" class="f15" href="javascript:;"></a><a title="酷" class="f16" href="javascript:;"></a><a title="冷汗" class="f96" href="javascript:;"></a><a title="抓狂" class="f18" href="javascript:;"></a><a title="吐" class="f19" href="javascript:;"></a><a title="偷笑" class="f20" href="javascript:;"></a><a title="愉快" class="f21" href="javascript:;"></a><a title="白眼" class="f22" href="javascript:;"></a><a title="傲慢" class="f23" href="javascript:;"></a><a title="饥饿" class="f24" href="javascript:;"></a><a title="困" class="f25" href="javascript:;"></a><a title="惊恐" class="f26" href="javascript:;"></a><a title="流汗" class="f27" href="javascript:;"></a><a title="憨笑" class="f28" href="javascript:;"></a><a title="悠闲" class="f29 borderRightNone" href="javascript:;"></a><a title="奋斗" class="f30" href="javascript:;"></a><a title="咒骂" class="f31" href="javascript:;"></a><a title="疑问" class="f32" href="javascript:;"></a><a title="嘘" class="f33" href="javascript:;"></a><a title="晕" class="f34" href="javascript:;"></a><a title="疯了" class="f35" href="javascript:;"></a><a title="衰" class="f36" href="javascript:;"></a><a title="骷髅" class="f37" href="javascript:;"></a><a title="敲打" class="f38" href="javascript:;"></a><a title="再见" class="f39" href="javascript:;"></a><a title="擦汗" class="f97" href="javascript:;"></a><a title="抠鼻" class="f98" href="javascript:;"></a><a title="鼓掌" class="f99" href="javascript:;"></a><a title="糗大了" class="f100" href="javascript:;"></a><a title="坏笑" class="f101 borderRightNone" href="javascript:;"></a><a title="左哼哼" class="f102" href="javascript:;"></a><a title="右哼哼" class="f103" href="javascript:;"></a><a title="哈欠" class="f104" href="javascript:;"></a><a title="鄙视" class="f105" href="javascript:;"></a><a title="委屈" class="f106" href="javascript:;"></a><a title="快哭了" class="f107" href="javascript:;"></a><a title="阴险" class="f108" href="javascript:;"></a><a title="亲亲" class="f109" href="javascript:;"></a><a title="吓" class="f110" href="javascript:;"></a><a title="可怜" class="f111" href="javascript:;"></a><a title="菜刀" class="f112" href="javascript:;"></a><a title="西瓜" class="f89" href="javascript:;"></a><a title="啤酒" class="f113" href="javascript:;"></a><a title="篮球" class="f114" href="javascript:;"></a><a title="乒乓" class="f115 borderRightNone" href="javascript:;"></a><a title="咖啡" class="f60" href="javascript:;"></a><a title="饭" class="f61" href="javascript:;"></a><a title="猪头" class="f46" href="javascript:;"></a><a title="玫瑰" class="f63" href="javascript:;"></a><a title="凋谢" class="f64" href="javascript:;"></a><a title="嘴唇" class="f116" href="javascript:;"></a><a title="爱心" class="f66" href="javascript:;"></a><a title="心碎" class="f67" href="javascript:;"></a><a title="蛋糕" class="f53" href="javascript:;"></a><a title="闪电" class="f54" href="javascript:;"></a><a title="炸弹" class="f55" href="javascript:;"></a><a title="刀" class="f56" href="javascript:;"></a><a title="足球" class="f57" href="javascript:;"></a><a title="瓢虫" class="f117" href="javascript:;"></a><a title="便便" class="f59 borderRightNone" href="javascript:;"></a><a title="月亮" class="f75" href="javascript:;"></a><a title="太阳" class="f74" href="javascript:;"></a><a title="礼物" class="f69" href="javascript:;"></a><a title="拥抱" class="f49" href="javascript:;"></a><a title="强" class="f76" href="javascript:;"></a><a title="弱" class="f77" href="javascript:;"></a><a title="握手" class="f78" href="javascript:;"></a><a title="胜利" class="f79" href="javascript:;"></a><a title="抱拳" class="f118" href="javascript:;"></a><a title="勾引" class="f119" href="javascript:;"></a><a title="拳头" class="f120" href="javascript:;"></a><a title="差劲" class="f121" href="javascript:;"></a><a title="爱你" class="f122" href="javascript:;"></a><a title="NO" class="f123" href="javascript:;"></a><a title="OK" class="f124 borderRightNone" href="javascript:;"></a><a title="爱情" class="f42 borderBottomNone" href="javascript:;"></a><a title="飞吻" class="f85 borderBottomNone" href="javascript:;"></a><a title="跳跳" class="f43 borderBottomNone" href="javascript:;"></a><a title="发抖" class="f41 borderBottomNone" href="javascript:;"></a><a title="怄火" class="f86 borderBottomNone" href="javascript:;"></a><a title="转圈" class="f125 borderBottomNone" href="javascript:;"></a><a title="磕头" class="f126 borderBottomNone" href="javascript:;"></a><a title="回头" class="f127 borderBottomNone" href="javascript:;"></a><a title="跳绳" class="f128 borderBottomNone" href="javascript:;"></a><a title="挥手" class="f129 borderBottomNone" href="javascript:;"></a><a title="激动" class="f130 borderBottomNone" href="javascript:;"></a><a title="街舞" class="f131 borderBottomNone" href="javascript:;"></a><a title="献吻" class="f132 borderBottomNone" href="javascript:;"></a><a title="左太极" class="f133 borderBottomNone" href="javascript:;"></a><a title="右太极" class="f134 borderBottomNone borderRightNone" href="javascript:;"></a>
      <div style="display: none;" class="facePreview">
        <div>
          <p class="faceImg"></p>
          <p class="faceName"></p>
        </div>
      </div>
    </div>
    <div style="display:none;" class="emojiBox" id="emojiBox">
      <div class="emojiContent">
        <div class="emojiFacePanel" click="chooseSysEmoji"> <a title="笑脸" class="" href="javascript:;"></a><a title="开心" class="" href="javascript:;"></a><a title="大笑" class="" href="javascript:;"></a><a title="热情" class="" href="javascript:;"></a><a title="眨眼" class="" href="javascript:;"></a><a title="色" class="" href="javascript:;"></a><a title="接吻" class="" href="javascript:;"></a><a title="亲吻" class="" href="javascript:;"></a><a title="脸红" class="" href="javascript:;"></a><a title="露齿笑" class="" href="javascript:;"></a><a title="满意" class="" href="javascript:;"></a><a title="戏弄" class="" href="javascript:;"></a><a title="吐舌" class="" href="javascript:;"></a><a title="无语" class="" href="javascript:;"></a><a title="得意" class="borderRightNone" href="javascript:;"></a><a title="汗" class="" href="javascript:;"></a><a title="失望" class="" href="javascript:;"></a><a title="低落" class="" href="javascript:;"></a><a title="呸" class="" href="javascript:;"></a><a title="焦虑" class="" href="javascript:;"></a><a title="担心" class="" href="javascript:;"></a><a title="震惊" class="" href="javascript:;"></a><a title="悔恨" class="" href="javascript:;"></a><a title="眼泪" class="" href="javascript:;"></a><a title="哭" class="" href="javascript:;"></a><a title="破涕为笑" class="" href="javascript:;"></a><a title="晕" class="" href="javascript:;"></a><a title="恐惧" class="" href="javascript:;"></a><a title="心烦" class="" href="javascript:;"></a><a title="生气" class="borderRightNone" href="javascript:;"></a><a title="睡觉" class="" href="javascript:;"></a><a title="生病" class="" href="javascript:;"></a><a title="恶魔" class="" href="javascript:;"></a><a title="外星人" class="" href="javascript:;"></a><a title="心" class="" href="javascript:;"></a><a title="心碎" class="" href="javascript:;"></a><a title="丘比特" class="" href="javascript:;"></a><a title="闪烁" class="" href="javascript:;"></a><a title="星星" class="" href="javascript:;"></a><a title="叹号" class="" href="javascript:;"></a><a title="问号" class="" href="javascript:;"></a><a title="睡着" class="" href="javascript:;"></a><a title="水滴" class="" href="javascript:;"></a><a title="音乐" class="" href="javascript:;"></a><a title="火" class="borderRightNone" href="javascript:;"></a><a title="便便" class="" href="javascript:;"></a><a title="强" class="" href="javascript:;"></a><a title="弱" class="" href="javascript:;"></a><a title="拳头" class="" href="javascript:;"></a><a title="胜利" class="" href="javascript:;"></a><a title="上" class="" href="javascript:;"></a><a title="下" class="" href="javascript:;"></a><a title="右" class="" href="javascript:;"></a><a title="左" class="" href="javascript:;"></a><a title="第一" class="" href="javascript:;"></a><a title="强壮" class="" href="javascript:;"></a><a title="吻" class="" href="javascript:;"></a><a title="热恋" class="" href="javascript:;"></a><a title="男孩" class="" href="javascript:;"></a><a title="女孩" class="borderRightNone" href="javascript:;"></a><a title="女士" class="" href="javascript:;"></a><a title="男士" class="" href="javascript:;"></a><a title="天使" class="" href="javascript:;"></a><a title="骷髅" class="" href="javascript:;"></a><a title="红唇" class="" href="javascript:;"></a><a title="太阳" class="" href="javascript:;"></a><a title="下雨" class="" href="javascript:;"></a><a title="多云" class="" href="javascript:;"></a><a title="雪人" class="" href="javascript:;"></a><a title="月亮" class="" href="javascript:;"></a><a title="闪电" class="" href="javascript:;"></a><a title="海浪" class="" href="javascript:;"></a><a title="猫" class="" href="javascript:;"></a><a title="小狗" class="" href="javascript:;"></a><a title="老鼠" class="borderRightNone" href="javascript:;"></a><a title="仓鼠" class="" href="javascript:;"></a><a title="兔子" class="" href="javascript:;"></a><a title="狗" class="" href="javascript:;"></a><a title="青蛙" class="" href="javascript:;"></a><a title="老虎" class="" href="javascript:;"></a><a title="考拉" class="" href="javascript:;"></a><a title="熊" class="" href="javascript:;"></a><a title="猪" class="" href="javascript:;"></a><a title="牛" class="" href="javascript:;"></a><a title="野猪" class="" href="javascript:;"></a><a title="猴子" class="" href="javascript:;"></a><a title="马" class="" href="javascript:;"></a><a title="蛇" class="" href="javascript:;"></a><a title="鸽子" class="" href="javascript:;"></a><a title="鸡" class="borderRightNone" href="javascript:;"></a><a title="企鹅" class="" href="javascript:;"></a><a title="毛虫" class="" href="javascript:;"></a><a title="章鱼" class="" href="javascript:;"></a><a title="鱼" class="" href="javascript:;"></a><a title="鲸鱼" class="" href="javascript:;"></a><a title="海豚" class="" href="javascript:;"></a><a title="玫瑰" class="" href="javascript:;"></a><a title="花" class="" href="javascript:;"></a><a title="棕榈树" class="" href="javascript:;"></a><a title="仙人掌" class="" href="javascript:;"></a><a title="礼盒" class="" href="javascript:;"></a><a title="南瓜灯" class="" href="javascript:;"></a><a title="鬼魂" class="" href="javascript:;"></a><a title="圣诞老人" class="" href="javascript:;"></a><a title="圣诞树" class="borderRightNone" href="javascript:;"></a><a title="礼物" class="" href="javascript:;"></a><a title="铃" class="" href="javascript:;"></a><a title="庆祝" class="" href="javascript:;"></a><a title="气球" class="" href="javascript:;"></a><a title="CD" class="" href="javascript:;"></a><a title="相机" class="" href="javascript:;"></a><a title="录像机" class="" href="javascript:;"></a><a title="电脑" class="" href="javascript:;"></a><a title="电视" class="" href="javascript:;"></a><a title="电话" class="" href="javascript:;"></a><a title="解锁" class="" href="javascript:;"></a><a title="锁" class="" href="javascript:;"></a><a title="钥匙" class="" href="javascript:;"></a><a title="成交" class="" href="javascript:;"></a><a title="灯泡" class="borderRightNone" href="javascript:;"></a><a title="邮箱" class="" href="javascript:;"></a><a title="浴缸" class="" href="javascript:;"></a><a title="钱" class="" href="javascript:;"></a><a title="炸弹" class="" href="javascript:;"></a><a title="手枪" class="" href="javascript:;"></a><a title="药丸" class="" href="javascript:;"></a><a title="橄榄球" class="" href="javascript:;"></a><a title="篮球" class="" href="javascript:;"></a><a title="足球" class="" href="javascript:;"></a><a title="棒球" class="" href="javascript:;"></a><a title="高尔夫" class="" href="javascript:;"></a><a title="奖杯" class="" href="javascript:;"></a><a title="入侵者" class="" href="javascript:;"></a><a title="唱歌" class="" href="javascript:;"></a><a title="吉他" class="borderRightNone" href="javascript:;"></a><a title="比基尼" class="" href="javascript:;"></a><a title="皇冠" class="" href="javascript:;"></a><a title="雨伞" class="" href="javascript:;"></a><a title="手提包" class="" href="javascript:;"></a><a title="口红" class="" href="javascript:;"></a><a title="戒指" class="" href="javascript:;"></a><a title="钻石" class="" href="javascript:;"></a><a title="咖啡" class="" href="javascript:;"></a><a title="啤酒" class="" href="javascript:;"></a><a title="干杯" class="" href="javascript:;"></a><a title="鸡尾酒" class="" href="javascript:;"></a><a title="汉堡" class="" href="javascript:;"></a><a title="薯条" class="" href="javascript:;"></a><a title="意面" class="" href="javascript:;"></a><a title="寿司" class="borderRightNone" href="javascript:;"></a><a title="面条" class="" href="javascript:;"></a><a title="煎蛋" class="" href="javascript:;"></a><a title="冰激凌" class="" href="javascript:;"></a><a title="蛋糕" class="" href="javascript:;"></a><a title="苹果" class="" href="javascript:;"></a><a title="飞机" class="" href="javascript:;"></a><a title="火箭" class="" href="javascript:;"></a><a title="自行车" class="" href="javascript:;"></a><a title="高铁" class="" href="javascript:;"></a><a title="警告" class="" href="javascript:;"></a><a title="旗" class="" href="javascript:;"></a><a title="男人" class="" href="javascript:;"></a><a title="女人" class="" href="javascript:;"></a><a title="O" class="" href="javascript:;"></a><a title="X" class="borderRightNone" href="javascript:;"></a><a title="版权" class=" borderBottomNone" href="javascript:;"></a><a title="注册商标" class=" borderBottomNone" href="javascript:;"></a><a title="商标" class=" borderBottomNone" href="javascript:;"></a></div>
      </div>
    </div>
    <div style="display:none;" class="rabbitBox" click="chooseCustomEmoji">
      <div class="rabbitContent">
        <div class="rabbitPanel"><a class="r11" href="javascript:;" un="icon_002.gif"></a><a class="r12" href="javascript:;" un="icon_007.gif"></a> <a class="r13" href="javascript:;" un="icon_010.gif"></a> <a class="r14" href="javascript:;" un="icon_012.gif"></a> <a class="r15 borderRightNone" href="javascript:;" un="icon_013.gif"></a><a class="r21" href="javascript:;" un="icon_018.gif"></a><a class="r22" href="javascript:;" un="icon_019.gif"></a> <a class="r23" href="javascript:;" un="icon_021.gif"></a> <a class="r24" href="javascript:;" un="icon_022.gif"></a> <a class="r25 borderRightNone" href="javascript:;" un="icon_024.gif"></a> <a class="r31" href="javascript:;" un="icon_027.gif"></a><a class="r32" href="javascript:;" un="icon_029.gif"></a> <a class="r33" href="javascript:;" un="icon_030.gif"></a> <a class="r34" href="javascript:;" un="icon_035.gif"></a> <a class="r35 borderRightNone" href="javascript:;" un="icon_040.gif"></a> <a class="r41" href="javascript:;" un="icon_020.gif"></a></div>
      </div>
    </div>
  </div>
  <!--<a title="关闭" class="faceCloseIcon" href="javascript:;" click="closeEmojiPanel"><img src="https://res.wx.qq.com/zh_CN/htmledition/images/spacer17ced3.gif" /></a>-->
  <div class="faceTriangle">
    <div class="faceTrianglePanel">
      <div class="faceTriangle1"></div>
      <div class="faceTriangle2"></div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
<div style="clear: both; visibility: visible; "></div>
</div>
</div>
</div>
</body>
</html>
<?php		
	}
	
	public function reply_text($reply) {
		return '<div class="chatItem you" un="item_1020740959">
		  <div class="chatItemContent"> <img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">
			<div class="cloud cloudText" un="cloud_1020740959" msgid="1020740959">
			  <div class="cloudPannel" style="">
				<div class="sendStatus"> </div>
				<div class="cloudBody">
				  <div class="cloudContent">
					<pre style="white-space:pre-wrap">'.wxlog_emoji(wxlog_qqface($reply['Content'],'img'),'html').'</pre>
				  </div>
				</div>
				<div class="cloudArrow "></div>
			  </div>
			</div>
		  </div>
		</div>';		
	}
		
	public function reply_image($reply) {?>
        <div class="chatItem you" un="item_1020742835">
          <div class="time"><span class="timeBg left"></span>
            <?=date('Y-m-d H:i:s',$reply['CreateTime'])?>
            <span class="timeBg right"></span> </div>
            <div class="chatItemContent"> <img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">
            <div class="cloud cloudImg" un="cloud_1020742835" msgid="1020742835">
              <div class="cloudPannel" style="">
                <div class="sendStatus"> </div>
                <div class="cloudBody">
                  <div class="cloudContent"> <!--img msg--> <img class="zoomIn imageBorder" src="<?=$reply['PicUrl']?>"> </div>
                </div>
                <div class="cloudArrow "></div>
              </div>
            </div>
          </div>
        </div>
<?php	}	
	
	
	public function reply_news1($reply) {?>
<div class="chatItem you" un="item_1020742752"> <!--media mesg content-->
  <div class="time"><span class="timeBg left"></span>
    <?=date('Y-m-d H:i:s',$reply['xml']['CreateTime'])?>
    <span class="timeBg right"></span> </div>
  <img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">
  <div class="media mediaFullText"><a style="text-decoration: none;" href="<?=$reply['xml']['Articles']['item']['Url']?>" target="_blank"></a>
    <div class="chatItemContent_news1">
      <div class="mediaPanel"><a style="text-decoration: none;" href="<?=$reply['xml']['Articles']['item']['Url']?>" target="_blank">
        <div class="mediaHead"><span class="title left">
          <?=$reply['xml']['Articles']['item']['Title']?>
          </span> <span class="time right">
          <?=date('Y-m-d',$reply['xml']['CreateTime'])?>
          </span>
          <div class="clr"></div>
        </div>
        <?php if ($reply['xml']['Articles']['item']['PicUrl']){?>
        <div class="mediaImg"><img onload="jQuery.genImgCentralStyle(this);" src="<?=$reply['xml']['Articles']['item']['PicUrl']?>"></div>
        <?php }?>
        <div class="mediaContent mediaContentP">
          <p>
            <?=$reply['xml']['Articles']['item']['Description']?>
          </p>
        </div>
        </a>
        <div class="mediaFooter"><a style="text-decoration: none;" href="<?=$reply['xml']['Articles']['item']['Url']?>" target="_blank"><a style="text-decoration: none;" href="<?=$reply['xml']['Articles']['item']['Url']?>" target="_blank"><span class="mesgIcon left"></span><span class="left" style="line-height: 39px;">查看全文</span>
          <div class="clr"></div>
          </a></div>
      </div>
      <div class="cloudArrow"></div>
    </div>
  </div>
</div>
<?php }	
	
	
	public function reply_news2($reply) {?>
<div class="chatItem you" un="item_1020740964"> <!--media mesg-->
  <div class="time"><span class="timeBg left"></span>
    <?=date('Y-m-d H:i:s',$reply['xml']['CreateTime'])?>
    <span class="timeBg right"></span> </div>
  <img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">
  <div class="media">
    <div class="chatItemContent_news2">
      <div class="mediaPanel"> <a href="<?=$reply['xml']['Articles']['item'][0]['Url']?>" target="_blank">
        <div class="mediaImg">
          <div class="mediaImgPanel"><img src="<?=$reply['xml']['Articles']['item'][0]['PicUrl']?>" style="visibility: inherit; height: 273.75px; width: 365px; top: -54.875px; left: 0px; " onload="jQuery.genImgCentralStyle(this);"></div>
          <div class="mediaImgFooter">
            <p class="mesgTitleTitle left">
              <?=$reply['xml']['Articles']['item'][0]['Title']?>
            </p>
            <div class="clr"></div>
          </div>
        </div>
        </a>
        <div class="mediaContent">
          <?php if(isset($reply['xml']['Articles']['item'])){foreach(@$reply['xml']['Articles']['item'] as $key=>$value){if($key>0){?>
          <a href="<?=$value['Url']?>" target="_blank">
          <div class="mediaMesg"><span class="mediaMesgDot"></span>
            <div class="mediaMesgTitle left">
              <p class="left">
                <?=$value['Title']?>
              </p>
              <div class="clr"></div>
            </div>
            <div class="mediaMesgIcon right"><img src="<?=$value['PicUrl']?>"></div>
            <div class="clr"></div>
          </div>
          </a>
          <?php }}}?>
        </div>
      </div>
      <div class="cloudArrow"></div>
    </div>
  </div>
</div>
<?php }	
	
	
	public function reply_music($reply) {?>
<div class="chatItem you" un="item_1020742745">
  <div class="time"><span class="timeBg left"></span>
    <?=date('Y-m-d H:i:s',$reply['CreateTime'])?>
    <span class="timeBg right"></span> </div>
  <div class="chatItemContent"><img class="avatar" src="/wp-content/plugins/wxlog/includes/admin/images/man.jpg" onerror="reLoadImg(this)" un="avatar_phplog" title="PHP日志" click="showProfile" username="phplog.com">
    <div class="cloud cloudMesg cloudMesgLink" un="cloud_1020742745" msgid="1020742745">
      <div class="cloudPannel">
        <div class="cloudBody">
          <div class="cloudContent left"> 
            <!-- app audio -->
            <div class="cloudMesgPanel"> <a href="<?=$reply['HQMusicUrl']?>" target="_blank">
              <div class="cloudMesgLinkFilePanel">
                <div class="cloudMesgIcon left">
                  <div  style="width:43px;height:41px; border:#999 solid 1px;padding-left:17px;padding-top:19px;">
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="25" height="25">
                      <param name="movie" value="/wp-content/plugins/wxlog/includes/admin/images/preview/mp3_player.swf?file=<?=$reply['HQMusicUrl']?>&amp;width=25&amp;songVolume=100&amp;backColor=E8E8E8&amp;frontColor=000000&amp;autoStart=false&amp;repeatPlay=false&amp;showDownload=false">
                      <param name="quality" value="High">
                      <param value="transparent" name="wmode">
                      <embed src="/wp-content/plugins/wxlog/includes/admin/images/preview/mp3_player.swf?file=<?=$reply['HQMusicUrl']?>&amp;width=25&amp;songVolume=100&amp;backColor=E8E8E8&amp;frontColor=000000&amp;autoStart=false&amp;repeatPlay=false&amp;showDownload=false" width="25" height="25" quality="High" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent">
                    </object>
                  </div>
                </div>
                <!--<img class="cloudMesgIcon left" onerror="reLoadImg(this)" src="/cgi-bin/mmwebwx-bin/webwxgetmsgimg?type=slave&amp;MsgID=1020742745">-->
                <div class="cloudMesgContent left">
                  <p>
                    <?=$reply['Title']?>
                  </p>
                  <span>
                  <?=$reply['Description']?>
                  </span> </div>
                <div class="clr"></div>
              </div>
              </a></div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="cloudArrow"></div>
      </div>
    </div>
  </div>
</div>
<?php }	
	
}
