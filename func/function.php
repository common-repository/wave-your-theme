<?php
/*author: QiQiBoY
 *date: 2010/09/22
 *contact: imqiqiboy#gmail.com(#->@)
 *blog: http://www.qiqiboy.com

 ################################################
 contact me: 1. imqiqiboy#gmail.com
			2. http://www.qiqiboy.com/contact
			3. http://www.qiqiboy.com/guestbook
 ################################################

*/

function WYT_showErr($ErrMsg,$httperr='HTTP/1.0 500 Internal Server Error') {
	header($httperr);
	header('Content-Type: text/plain;charset=UTF-8');
	echo $ErrMsg;
	exit;
}

$WYT_cookie_time=get_option('WYT_Cookie-Time')!=''?(int)get_option('WYT_Cookie-Time'):3;

$WYT_preview_theme=$WYT_preview_css='';

if(isset($_COOKIE['WYT_preview_theme']))$WYT_preview_theme=$_COOKIE['WYT_preview_theme'];

if(get_option('WYT_need_key')&&get_option('WYT_key_value')!=''&&(isset($_GET['preview_theme'])||isset($_GET['wptheme']))){
	if(!isset($_GET['key']))WYT_showErr(__("Need a key!","Wave-Your-Theme"),'HTTP/1.0 403 Not Allowed');
	elseif(trim($_GET['key'])!=get_option('WYT_key_value')) WYT_showErr(__("Error key!","Wave-Your-Theme"),'HTTP/1.0 403 Not Allowed');
	else setcookie('WYT_key_value_'.get_option('WYT_key_value'), get_option('WYT_key_value'), time()+60*60*24*$WYT_cookie_time, COOKIEPATH, COOKIE_DOMAIN);
}

if(isset($_GET['preview_theme'])){
	$WYT_preview_theme = $_GET['preview_theme'];
	if(get_option('WYT_all_cookie'))setcookie('WYT_preview_theme', $WYT_theme, time()+60*60*24*$WYT_cookie_time, COOKIEPATH, COOKIE_DOMAIN);
}
if(get_option('WYT_Compatible')){
	if(isset($_GET['wptheme'])){
		$WYT_preview_theme = $_GET['wptheme'];
		if(get_option('WYT_all_cookie'))setcookie('WYT_preview_theme', $WYT_theme, time()+60*60*24*$WYT_cookie_time, COOKIEPATH, COOKIE_DOMAIN);
	}
}
if (! $WYT_preview_css )
	$WYT_preview_css = $WYT_preview_theme;

if($WYT_preview_theme && file_exists(get_theme_root() . "/$WYT_preview_theme")) {
	add_filter('template','WYT_set_theme');
}

if($WYT_preview_css && file_exists(get_theme_root() . "/$WYT_preview_css")) {
	add_filter('stylesheet','WYT_set_css');
}

function WYT_set_theme($themename) {
	global $WYT_preview_theme;

	return $WYT_preview_theme;
}

function WYT_set_css($cssname) {
	global $WYT_preview_css;

	return $WYT_preview_css;
}

if($_GET['action'] == 'WYT_getAllThemes'){
	$themesdir = get_theme_root();
	$TrackDir=opendir($themesdir);
	while ($file = readdir($TrackDir)) {
		if ($file == "." || $file == ".." || is_file($file)) { }
		else {
			$themeurl = "?preview_theme=".$file;
			$jsonArr[]=array("href"=>$themeurl,"name"=>$file);
		}
	}
	echo json_encode($jsonArr);
	die();
}else if($_GET['action'] == 'WYT_set_theme'){
	if(get_option('WYT_need_key')&&get_option('WYT_key_value')!=''&&!$_COOKIE['WYT_key_value_'.get_option('WYT_key_value')]&&!isset($_GET['key']))
		WYT_showErr(__('Error key!','Wave-Your-Theme'),'HTTP/1.0 403 Not Allowed');
	$WYT_key=isset($_COOKIE['WYT_key_value_'.get_option('WYT_key_value')])?$_COOKIE['WYT_key_value_'.get_option('WYT_key_value')]:$_GET['key'];
	if(get_option('WYT_need_key')&&get_option('WYT_key_value')!=''){
		if(trim($WYT_key)!=trim(get_option('WYT_key_value')))WYT_showErr(__('Error key!.','Wave-Your-Theme'),'HTTP/1.0 403 Not Allowed');
		else setcookie('WYT_key_value_'.get_option('WYT_key_value'), get_option('WYT_key_value'), time()+60*60*24*$WYT_cookie_time, COOKIEPATH, COOKIE_DOMAIN);
	}
	$WYT_theme=$_GET['WYT_theme'];if(!isset($WYT_theme))WYT_showErr('something error.');
	setcookie('WYT_preview_theme', $WYT_theme, time()+60*60*24*$WYT_cookie_time, COOKIEPATH, COOKIE_DOMAIN);
	echo 'yes';
	die();
}

add_action('admin_menu', 'WYT_add_options');

function WYT_add_options() {
	add_options_page('Wave Your Theme options', __("Wave Your Theme","Wave-Your-Theme"), 8, __FILE__, 'WYT_the_options');
}
function WYT_addScript(){
	$css = '<link rel="stylesheet" href="' .get_bloginfo("wpurl") . '/wp-content/plugins/wave-your-theme/css/wave-your-theme.css" type="text/css" media="screen" />';
	$script = '<script type="text/javascript">WYT_options={image:"'.(get_option("WYT_button-choose")!='auto'&&get_option("WYT_button-choose")!=''?get_option("WYT_button-choose"):'icons.png').'",title:"'.(get_option("WYT_button_title")!=''?get_option("WYT_button_title"):__("preview theme from here","Wave-Your-Theme")).'",width:'.(get_option("WYT_img_width")!=''?(int)get_option("WYT_img_width"):'22').',height:'.(get_option("WYT_img_height")!=''?(int)get_option("WYT_img_height"):'22').',custom:'.(get_option("WYT_Custom-Location")?1:0).',location:'.(get_option("WYT_click-Location")!=''?get_option("WYT_click-Location"):1).',x:'.(get_option("WYT_xoffset")!=''?(int)get_option("WYT_xoffset"):20).',y:'.(get_option("WYT_yoffset")!=''?(int)get_option("WYT_yoffset"):0).',id:"'.(get_option("WYT_start_id")!=''?get_option("WYT_start_id"):"WYT-theme").'",tips:"'.(get_option("WYT_succss_tips")!=''?get_option("WYT_succss_tips"):__("Theme switch was successful, the page will automatically refresh, please wait!","Wave-Your-Theme")).'",tips2:"'.(get_option("WYT_faild_tips")!=''?get_option("WYT_faild_tips"):__("Oops, failed to change theme.","Wave-Your-Theme")).'",tips3:"'.__('Enter the key','Wave-Your-Theme').'",tips4:"'.__('You can\'t preview the theme!','Wave-Your-Theme').'",zIndex:'.(get_option("WYT_zindex")!=''?(int)get_option("WYT_zindex"):0).'};</script><script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/wave-your-theme/js/wave-your-theme.min.js"></script>';
	if(!get_option("WYT_Just-Admin")||is_user_logged_in()) echo $css . $script;
	else return;
}

if(!get_option("WYT_JS-Location")||get_option("WYT_JS-Location")=='0')add_action ('wp_head', 'WYT_addScript');
if(get_option("WYT_JS-Location")&&get_option("WYT_JS-Location")=='1')add_action ('wp_footer', 'WYT_addScript');

function WYT_add(){
	echo '<a id="WYT-theme" href="javascript:;"></a>';
}

?>