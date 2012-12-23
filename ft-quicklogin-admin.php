<?php
/*------------------------------------------------
*  QuickLogin Admin Panel Scripts
*-----------------------------------------------*/
function fql_admin_init() {
	//Load the Uploadify
	wp_enqueue_style('style-uploadify.css', plugins_url('css/admin.css', FQL_FILE));
	wp_enqueue_script( 'jquery-uploadify', plugins_url('js/jquery.uploadify.min.js', FQL_FILE), array('jquery', 'swfobject'), null );
}
if( is_admin() && isset($_GET['page']) && ($_GET['page'] == plugin_basename(FQL_FILE)) )
	add_action('admin_init', 'fql_admin_init', 1);


/*------------------------------------------------
*  QuickLogin Admin Panel Menus
*-----------------------------------------------*/
function fql_admin_create_menu() {
	add_options_page('Quick Login', 'Quick Login', 'administrator', FQL_FILE, 'fql_admin_settings_page');

	add_action( 'admin_init', 'fql_admin_register_settings' );
}
add_action('admin_menu', 'fql_admin_create_menu');


/*------------------------------------------------
*  QuickLogin Admin Panel Register Settings
*-----------------------------------------------*/
function fql_admin_register_settings() {
	register_setting( 'fquicklogin', 'fquicklogin' );
}


/*------------------------------------------------
*  QuickLogin Admin Page
*-----------------------------------------------*/
function fql_admin_settings_page() {
	$options = get_option('fquicklogin'); ?>

<div class="wrap">
<form method="post" action="options.php">
	<?php settings_fields( 'fquicklogin' ); ?>

	<div id="icon-options-general" class="icon32"><br /></div><h2>General Settings</h2>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Color scheme</th>
			<td>
				<select name="fquicklogin[color_scheme]">
					<option value="light"<?php if($options[color_scheme]=="light") echo ' selected="selected"';?>>Light</option>
					<option value="dark"<?php if($options[color_scheme]=="dark") echo ' selected="selected"';?>>Dark</option>
				</select>
			</td>
		</tr>
	</table>

	<div id="icon-options-general" class="icon32"><br /></div><h2>Login Lightbox Settings</h2>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Logo</th>
			<td>
				<label for="fql_upload_image">
					<input id="fql_upload_image" type="text" size="36" name="fquicklogin[box_logo]" value="<?php echo $options[box_logo]; ?>" style="display: inline-block; vertical-align: top;" />
					<div style="display: inline-block; margin: 1px 0 0 5px;"><input id="fql_upload_image_file" type="file" name="fql_upload_image_file" style="display:none;" /></div>
					<br />Enter an URL or upload the logo.

					<script type="text/javascript">// <![CDATA[
					jQuery(document).ready(function(){
						jQuery('#fql_upload_image_file').uploadify({
							'swf'		: '<?php echo plugins_url('images/uploadify.swf', FQL_FILE); ?>',
							'uploader'	: '<?php echo plugins_url('ft-upload.php', FQL_FILE); ?>',
							'buttonImage'	: '<?php echo plugins_url('images/upload-button.jpg', FQL_FILE); ?>',

							'auto'      : true,
							'height'    : 22,
							'width'     : 82,

							'formData' : {
								'timestamp' : userSettings.time,
								'token'     : '<?php echo md5( 'unique_salt' . time() ); ?>',
								'folder'    : userSettings.url
							},
							'onUploadError' : function(file, errorCode, errorMsg, errorString) {
								alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
							},
							'onUploadSuccess' : function(file, data, response) {
								if( data != 0 ) {
									var fileName= userSettings.url + 'wp-content/uploads/' + userSettings.time + '-' + file.name;
									jQuery('#fql_upload_image').val(fileName);
								} else {
									alert('The file ' + file.name + ' could not be uploaded.');
								}
							}
						});
					});
					// ]]></script>
				</label>
			</td>
		</tr>
	</table>

	<div id="icon-options-general" class="icon32"><br /></div><h2>Top Bar Settings</h2>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Status</th>
			<td><label for="fql_bar_status"><input name="fquicklogin[bar_status]" type="checkbox" id="fql_bar_status" value="1"<?php if($options[bar_status]) echo ' checked="checked"';?> /> Enable Top Bar</label></td>
		</tr>
		<tr valign="top">
			<th scope="row">Hide when logged in</th>
			<td><label for="fql_bar_hide"><input name="fquicklogin[bar_hide]" type="checkbox" id="fql_bar_hide" value="1"<?php if($options[bar_hide]) echo ' checked="checked"';?> /> Hide</label></td>
		</tr>
		<tr valign="top">
			<th scope="row">Content width</th>
			<td id="fql_bar_width">
				<script type="text/javascript">
					function fql_bar_width_change() {
						if( jQuery("#fql_bar_width_type").val() == "fixed" )
							jQuery("#fql_bar_width span").show();
						else
							jQuery("#fql_bar_width span").hide();
					}
				</script>
				<select name="fquicklogin[bar_width_type]" id="fql_bar_width_type" onchange="javascript:fql_bar_width_change();">
					<option value="fixed"<?php if($options[bar_width_type]=="fixed") echo ' selected="selected"';?>>Fixed width</option>
					<option value="fluid"<?php if($options[bar_width_type]=="fluid") echo ' selected="selected"';?>>Full width</option>
				</select>
				
				<span<?php if($options[bar_width_type]=="fluid") echo ' style="display: none;"'; ?>><input type="text" name="fquicklogin[bar_width_size]" id="fql_bar_width_size" value="<?php echo $options[bar_width_size]; ?>" class="small-text" /> px</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">News Section</th>
			<td>
				<select name="fquicklogin[bar_news]">
					<option value="latest"<?php if($options[bar_news]=="latest") echo ' selected="selected"';?>>Latest Posts</option>
					<option value="popular"<?php if($options[bar_news]=="popular") echo ' selected="selected"';?>>Popular Posts</option>
					<option value="random"<?php if($options[bar_news]=="random") echo ' selected="selected"';?>>Random Posts</option>
					<option value="sticky"<?php if($options[bar_news]=="sticky") echo ' selected="selected"';?>>Sticky Posts</option>
				</select>
				
				<span><input type="text" name="fquicklogin[bar_news_count]" id="fql_bar_news_count" value="<?php echo $options[bar_news_count]; ?>" class="small-text" /> posts</span>
				
				<br />Select the type of Posts to display on the top bar.
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Colors</th>
			<td>
				<span><input type="text" name="fquicklogin[bar_color_primary]" id="fql_bar_color_primary" value="<?php echo $options[bar_color_primary]; ?>" style="width:65px" /> &nbsp;Primary color</span><br />
				<span><input type="text" name="fquicklogin[bar_color_secondary]" id="fql_bar_color_secondary" value="<?php echo $options[bar_color_secondary]; ?>" style="width:65px" /> &nbsp;Secondary color</span>
			</td>
		</tr>
	</table>
    
	<p class="submit">
		<input type="submit" class="button-primary" value="Save Changes" />
		<a href="<?php echo add_query_arg( 'fql-restore-settings', 'true', fql_get_current_url() ); ?>" class="button-secondary reset-options" onclick="return confirm('Are you sure you want to restore settings to default?')" />Restore Settings</a>
	</p>
</form>
</div>
<?php } ?>