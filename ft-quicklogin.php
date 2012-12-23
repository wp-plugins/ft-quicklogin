<?php
/*
 * Plugin Name: FT QuickLogin
 * Plugin URI: http://www.flarethemes.com/
 * Description: QuickLogin is a simple and versatile plugin built to enhance the whole Signup and Login process by displaying all authentification forms in a lightbox.
 * Author: FlareThemes
 * Author URI: http://www.flarethemes.com/
 * Version: 1.0.3
*/

if ( ! defined( 'WP_CONTENT_URL' ) ) {
	if ( defined( 'WP_SITEURL' ) ) define( 'WP_CONTENT_URL', WP_SITEURL . '/wp-content' );
	else define( 'WP_CONTENT_URL', get_option( 'url' ) . '/wp-content' );
}

define( 'FQL_URL', WP_CONTENT_URL . '/plugins/ft-quicklogin/' );
define( 'FQL_DIR', WP_PLUGIN_DIR . '/ft-quicklogin/' );
define( 'FQL_FILE', __FILE__ );


/*------------------------------------------------
*  QuickLogin Default Settings
*-----------------------------------------------*/
$fql_default_settings = array(
	"color_scheme" => "light",
	"box_logo" => FQL_URL. 'images/logo.png',
	"bar_status" => 1,
	"bar_width_type" => "fixed",
	"bar_width_size" => 990,
	"bar_news" => "latest",
	"bar_news_count" => 5,
	"bar_color_primary" => "#333333",
	"bar_color_secondary" => "#139b78");


/*------------------------------------------------
*  Function to get current url
*-----------------------------------------------*/
function fql_get_current_url() {
	$url = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	return $url;
}

/*------------------------------------------------
*  QuickLogin Activation
*-----------------------------------------------*/
function fql_activation() {
	$fql_default_settings = array(
		"color_scheme" => "light",
		"box_logo" => FQL_URL. 'images/logo.png',
		"bar_status" => 1,
		"bar_width_type" => "fixed",
		"bar_width_size" => 990,
		"bar_news" => "latest",
		"bar_news_count" => 5,
		"bar_color_primary" => "#333333",
		"bar_color_secondary" => "#139b78");

	if( !get_option('fquicklogin') )
		update_option('fquicklogin', $fql_default_settings);
}
register_activation_hook( FQL_FILE, 'fql_activation' );


/*------------------------------------------------
*  QuickLogin Uninstall
*-----------------------------------------------*/
function fql_uninstall() {
	if( get_option('fquicklogin') )
		delete_option('fquicklogin');
}
register_uninstall_hook( FQL_FILE, 'fql_uninstall' );


/*------------------------------------------------
*  QuickLogin Restore Settings
*-----------------------------------------------*/
function fql_restore_settings() {
	global $fql_default_settings;

	update_option('fquicklogin', $fql_default_settings);

	wp_redirect( admin_url().'options-general.php?page=' . plugin_basename(FQL_FILE) );
}
if( is_admin() && isset($_GET['fql-restore-settings']) && ($_GET['fql-restore-settings'] == "true") )
	add_action( 'admin_init', 'fql_restore_settings' );


/*------------------------------------------------
*  QuickLogin Settings Link
*-----------------------------------------------*/
function fql_add_settings_link($links, $file) {
	static $this_plugin;

	if (!$this_plugin) $this_plugin = plugin_basename(FQL_FILE);
 
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=' . plugin_basename(FQL_FILE) . '">Settings</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}
add_filter('plugin_action_links', 'fql_add_settings_link', 10, 2 );


/*------------------------------------------------
*  QuickLogin Load
*-----------------------------------------------*/
function fql_init() {
	$options = get_option('fquicklogin');

	//Load the StyleSheet
	wp_enqueue_style( 'fql_css_style', plugins_url('css/stylesheet.css', FQL_FILE) );

	//Load the Dark Style
	if($options[color_scheme] == "dark")
		wp_enqueue_style( 'fql_css_style_dark', plugins_url('css/dark.css', FQL_FILE) );

	//Load the JavaScript
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'fql_javascript', plugins_url('js/javascript.min.js', FQL_FILE), false, null );

	//Add the custom color for Top Bar
	add_action('wp_head', 'fql_bar_custom_colors');

	//Add the Box
	add_action('wp_footer', 'fql_add_box');

	//Add the Top Bar
	if( $options[bar_status] )
		if ( !$options[bar_hide] )
			add_action('wp_footer', 'fql_add_bar');
		else
			if( !is_user_logged_in() )
				add_action('wp_footer', 'fql_add_bar');
}
add_action('get_header', 'fql_init', 1);


/*------------------------------------------------
*  Change the register/loginout/lostpassword links
*-----------------------------------------------*/
function fql_change_register_url($link) {
	if(!is_user_logged_in()) {
		$link = str_replace('<a', '<a rel="fql-register"', $link);
	}

	return $link;
}
add_filter('register', 'fql_change_register_url');


function fql_change_loginout_url($link) {
	if(!is_user_logged_in()) {
		$link = str_replace('<a', '<a rel="fql-login"', $link);
	} else {
		$link = '<a href="' . esc_url( wp_logout_url(fql_get_current_url() . '#fql-box-message-logout') ) . '">' . __('Log out') . '</a>';
	}

	return $link;
}
add_filter('loginout', 'fql_change_loginout_url');


function fql_change_lostpassword_url($link) {
	if(!is_user_logged_in()) {
		$link = str_replace('<a', '<a rel="fql-lost-password"', $link);
	}

	return $link;
}
add_filter('lostpassword_url', 'fql_change_lostpassword_url');


/*-------------------------------------------------------------
*   Setup languages
*------------------------------------------------------------*/
load_plugin_textdomain('ft-quicklogin', false , dirname( plugin_basename( __FILE__ ) ) . '/languages');


/*------------------------------------------------
*  QuickLogin Load Admin Panel
*-----------------------------------------------*/
if (is_admin()) include( FQL_DIR . 'ft-quicklogin-admin.php' );


/*------------------------------------------------
*  QuickLogin Load Box Section
*-----------------------------------------------*/
if (!is_admin()) include( FQL_DIR . 'ft-quicklogin-box.php' );


/*------------------------------------------------
*  QuickLogin Load Top Bar
*-----------------------------------------------*/
if (!is_admin()) include( FQL_DIR . 'ft-quicklogin-bar.php' );
?>