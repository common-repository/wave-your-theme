<?php
/*
 * Plugin Name: Wave Your Theme
 * Plugin URI: http://www.qiqiboy.com
 * Description: A cool, beautiful method that allows themes to be previewed without activation
 * Author: QiQiBoY
 * Author URI: http://www.qiqiboy.com
 * Version: 1.2.1
 */
load_plugin_textdomain('Wave-Your-Theme', false, basename(dirname(__FILE__)) . '/lang');
require_once(dirname(__FILE__).'/func/function.php');
function WYT_the_options() {
?>
<div class="wrap">

	<h2><?php _e('Wave Your Theme Options','Wave-Your-Theme');?></h2>
	
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		
		<h3><?php _e('Some configuration:','Wave-Your-Theme');?></h3>
		<label>
			<input name="WYT_Custom-Location" type="checkbox" value="checkbox" <?php if(get_option("WYT_Custom-Location")) echo "checked='checked'"; ?> />
			<?php _e('Custom Location to show? (Strongly not recommended)', 'Wave-Your-Theme'); ?>
		</label>
		
		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('the id of the node that clicked to show theme-show-label(Strongly not recommended)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_start_id" value="<?php echo get_option("WYT_start_id"); ?>" />
		<?php _e('if you have options "Custom Location to show", please input the id of your custom link. if you not options "Custom Location to show", you can input a custom id to here. You can leave it blank.', 'Wave-Your-Theme'); ?>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('button title(when mouse over button to show)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_button_title" value="<?php echo get_option('WYT_button_title'); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('cookie record time(day)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_Cookie-Time" value="<?php echo get_option('WYT_Cookie-Time'); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('Theme change succussed to show(day)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_succss_tips" value="<?php echo get_option("WYT_succss_tips"); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('Theme change faild to show(day)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_faild_tips" value="<?php echo get_option("WYT_faild_tips"); ?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><?php _e('javascript, css files location:', 'Wave-Your-Theme'); ?></th>
			<td><?php _e('Add to ', 'Wave-Your-Theme'); ?><select style="width:90px;text-align:center" name="WYT_JS-Location">
				<option value="0" <?php if(get_option("WYT_JS-Location")=="0") echo "selected='selected'"; ?>><?php _e('head', 'Wave-Your-Theme'); ?></option>
				<option value="1" <?php if(get_option("WYT_JS-Location")=="1") echo "selected='selected'"; ?>><?php _e('foot', 'Wave-Your-Theme'); ?></option>
				<option value="2" <?php if(get_option("WYT_JS-Location")=="2") echo "selected='selected'"; ?>><?php _e('custom', 'Wave-Your-Theme'); ?></option>
			</select><label><?php _e('("custom" means will not add JS, css files.Need your theme have <b>wp_head()</b> and <b>wp_foot()</b> function.)', 'Wave-Your-Theme'); ?></label></td>
		</tr>
		
		<tr valign="top">
			<th scope="row">
			<?php _e('if you didn\'t options "custom location to show", please choose auto show location:"', 'Wave-Your-Theme'); ?>
			</th>
			<td><?php _e('THE LOACATION:', 'Wave-Your-Theme'); ?><select style="width:120px;text-align:center" name="WYT_click-Location">
				<option value="0" <?php if(get_option("WYT_click-Location")=="0") echo "selected='selected'"; ?>><?php _e('Left-Top', 'Wave-Your-Theme'); ?></option>
				<option value="1" <?php if(get_option("WYT_click-Location")=="1") echo "selected='selected'"; ?>><?php _e('Right-Top', 'Wave-Your-Theme'); ?></option>
				<option value="2" <?php if(get_option("WYT_click-Location")=="2") echo "selected='selected'"; ?>><?php _e('Left-Bottom', 'Wave-Your-Theme'); ?></option>
				<option value="3" <?php if(get_option("WYT_click-Location")=="3") echo "selected='selected'"; ?>><?php _e('Right-Bottom', 'Wave-Your-Theme'); ?></option>
			</select><?php _e('if you have options "custom location to show", it will not works.', 'Wave-Your-Theme'); ?></td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php _e('the x offset','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_xoffset" value="<?php echo get_option("WYT_xoffset"); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('the y offset','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_yoffset" value="<?php echo get_option("WYT_yoffset"); ?>" /></td>
		</tr>
		<tr valign="top">
		<th scope="row"><b><?php _e('You can also custom the button image.','Wave-Your-Theme');?></b></th>
		</tr>
		<tr valign="top">
			<th scope="row">
			<?php _e('choose these image(default:auto):', 'Wave-Your-Theme'); ?>
			</th>
			<td><?php _e('THE LOACATION:', 'Wave-Your-Theme'); ?><select style="width:120px;text-align:center" name="WYT_button-choose">
				<option value="auto" <?php if(get_option("WYT_button-choose")=='auto') echo " selected='selected'" ?>><?php _e('auto','Wave-Your-Theme');?></option>
				<?php
					$TrackDir=opendir(dirname(__FILE__).'/img');
					while ($file = readdir($TrackDir)) {
						if (ereg("loading|icons",$file)||$file == "." || $file == ".." || is_file($file)) {}else{
							echo '<option value="'.$file.'"';
							if(get_option("WYT_button-choose")==$file) echo " selected='selected'";
							echo '>'.$file.'</option>';
							$WYT_count++;
						}
					}	
			?>
			</select><span style="color:#ff0000"><?php _e('You simply plug your own images into the img folder, and then come back here to choose your pictures to add. If the image width and height is not 22, then you also need to fill in the following picture width and height, otherwise the image will not be fully displayed. If you choose a plug-in comes with four pictures, then please fill in the following picture width and height were 50', 'Wave-Your-Theme'); ?></span></td>
		</tr>

		<tr valign="top">
		<th scope="row"><?php _e('the image width(default:22)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_img_width" value="<?php echo get_option("WYT_img_width"); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('the image height(default:22)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_img_height" value="<?php echo get_option("WYT_img_height"); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('you can also custom the button zIndex(default:auto)','Wave-Your-Theme');?></th>
		<td><input type="text" name="WYT_zindex" value="<?php echo get_option("WYT_zindex"); ?>" /></td>
		</tr>
		</table><br>
		<label>
			<input name="WYT_Just-Admin" type="checkbox" value="checkbox" <?php if(get_option("WYT_Just-Admin")) echo "checked='checked'"; ?> />
			<?php _e('Just Allow admin to switch theme?', 'Wave-Your-Theme'); ?>
		</label>
		<label>
			<input name="WYT_all_cookie" type="checkbox" value="checkbox" <?php if(get_option("WYT_all_cookie")) echo "checked='checked'"; ?> />
			<?php _e('If is statically linked still set a cookie?', 'Wave-Your-Theme'); ?>
		</label><br>
		<label>
			<input name="WYT_Compatible" type="checkbox" value="checkbox" <?php if(get_option("WYT_Compatible")) echo "checked='checked'"; ?> />
			<?php _e('Compatible with other plug-ins request(If you use other plug-ins before,please select this, you will not need to modify anyone links of your earlier articles)?(eg: wptheme, theme-preview)', 'Wave-Your-Theme'); ?>
		</label><br>
		<label>
			<input name="WYT_need_key" type="checkbox" value="checkbox" <?php if(get_option("WYT_need_key")) echo "checked='checked'"; ?> />
			<?php _e('must use key?','Wave-Your-Theme');?>
		</label>
		<label>
			<?php _e('please input the custom key', 'Wave-Your-Theme'); ?>
			<input type="text" name="WYT_key_value" value="<?php echo get_option("WYT_key_value"); ?>" />
		</label>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="WYT_button_title,WYT_key_value,WYT_need_key,WYT_Compatible,WYT_zindex,WYT_all_cookie,WYT_button-choose,WYT_img_width,WYT_img_height,WYT_Just-Admin,WYT_xoffset,WYT_yoffset,WYT_click-Location,WYT_succss_tips,WYT_faild_tips,WYT_start_id,WYT_JS-Location,WYT_Custom-Location,WYT_Cookie-Time" />

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Changes','Wave-Your-Theme') ?>" />
		</p>
	</form>
	<p>======================================<span style="color:#ff0000;font-weight:bold;"><?php _e('Tips:', 'Wave-Your-Theme'); ?> </span>==============================</p>
	<ol>
		<li><?php _e('Plug-in settings you better leave it alone the first two', 'Wave-Your-Theme'); ?></li>
		<li><span style="color:#ff0000;"><?php _e('If you use IE browser, when you need to be asked to enter key, the browser may block pop-up input window, you\'d better inform visitors to this, please allow pop-up window.', 'Wave-Your-Theme'); ?></span></li>
		<li><?php _e('If you enable the plug-in button on the page but not out, then please check the topic z-index setting. If necessary, the background for the button in the plug-in to set up a sufficiently large z-index', 'Wave-Your-Theme'); ?></li>
		<li><?php _e('If you want to customize the button icon, then you only need to prepare good picture into the plugins directory under the img folder, and then in the background you can choose this picture. Needs to be done is where the width and height of the icon fill in your width and height of this icon can be.', 'Wave-Your-Theme'); ?></li>
		<li><?php _e('If you use the plug-in comes with four picture as a button, then set the image height and width, respectively, 48 are filled', 'Wave-Your-Theme'); ?></li>
		<li><?php _e('If you must use a key set, then any topic at the first preview will be asked to enter key', 'Wave-Your-Theme'); ?></li>
		<li><?php _e('After the first key is entered correctly, then set the cookie in the life of all no need to re enter the key', 'Wave-Your-Theme'); ?></li>
		<li><?php _e('Compatible with other plug-in option is currently only supports plug-compatible wptheme request, if you need compatibility with other plug-in, please contact me.', 'Wave-Your-Theme'); ?></li>
	</ol>
</div>
<?php
}
?>