<?
/*
Plugin Name: LivDis
Plugin URI: http://livdis.com
Description: LivDis plugin
Version: 0.3
Author: P.Nixx
Author URI: http://pnixx.ru
*/

define('LD_CONTENT_URL', get_option('siteurl') . '/wp-content');
define('LD_PLUGIN_URL', LD_CONTENT_URL . '/plugins/livediscourse');
define('LD_HOST', 'livdis.com');
define('LD_URL', 'http://' . LD_HOST);
define('LD_ID', 'livediscourse');

register_deactivation_hook(__FILE__,'livdis_delete');
add_filter('the_content', 'append_livdis_script', 50);
add_action('admin_head', 'livdis_admin_head');

add_action('admin_menu', 'livids_add_pages');
add_action('admin_notices', 'livdis_messages');

function append_livdis_script($s) {

	if( get_option('livdis_uid') ) {
		$uid = get_option('livdis_uid');
		$host = LD_URL;
		$script = <<<HTML
<div id="ld_comments_box"></div>
<script src="{$host}/js/api/comments.js"></script>
<script>LDApi({app_id: {$uid}});</script>
HTML;

		if( is_singular() ) {
			return "<div class=\"livedisable\">{$s}</div>" . $script;
		} else {
			return $s;
		}
	} else {
		return $s;
	}
}

/**
 * Include styles and files in the admin
 */
function livdis_admin_head() {

	$page = (isset($_GET['page']) ? $_GET['page'] : null);
	if( $page == LD_ID ) {
		?>
	<link rel='stylesheet' href='<?= LD_PLUGIN_URL ?>/css/livdis.css' type='text/css'/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>jQueryLD = jQuery.noConflict(true);LD_HOST = "<?= LD_HOST ?>";</script>
	<script src="<?= LD_PLUGIN_URL ?>/livdis.js"></script>
	<?php
	}
}

/**
 * Notice of setting the widget
 */
function livdis_messages() {

	$page = (isset($_GET['page']) ? $_GET['page'] : null);
	if( !get_option('livdis_uid') && $page != LD_ID ) {
		echo '<div class="updated"><p><b>' . __('You must <a href="edit-comments.php?page=livediscourse">configure the plugin</a> to enable LivDis.', LD_ID) . '</b></p></div>';
	}
}

/**
 * Action when uninstall plugin
 */
function livdis_delete(){
	delete_option('livdis_id');
	delete_option('livdis_uid');
}

/**
 * Include manage file
 */
function livdis_options_page() {
	if( $_POST['livdis_form_counter_sub'] == 'Y' ) {
		if(isset($_POST['livdis_uid'])){
			update_option( 'livdis_uid',  $_POST['livdis_uid'] );
		}else{
			delete_option('livdis_uid');
		}
		echo '<div class="updated"><p><strong>'.__('Options saved', LD_ID).'</strong></p></div>';
	}
	include_once(dirname(__FILE__) . '/manage.php');
}



/**
 * Insert menu in the Comments section
 */
function livids_add_pages() {
	add_comments_page('Livdis Plugin Comments', 'LiveDiscourse', 'read', LD_ID, 'livdis_options_page');
}