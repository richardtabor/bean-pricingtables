<?php

/*
Plugin Name: Bean Pricing Tables
Plugin URI: http://themebeans.com/plugin/bean-pricing-tables/?ref=plugin_bean_pricing ‎
Description: Enables shortcode pricing tables to be added to your theme.
Version: 1.0
Author: ThemeBeans
Author URI: http://themebeans.com/?ref=plugin_bean_pricing
*/


//MAKE SURE WE DO NOT EXPOSE ANY INFO IF CALLED DIRECTLY
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


if ( ! class_exists( 'Bean_BeanPricingTables' ) ) :
class Bean_BeanPricingTables {

    private $BEAN_TINYMCE_URI;
    private $BEAN_TINYMCE_DIR;

    function __construct() {
    	require_once( DIRNAME(__FILE__) . '/bean-theme-pricingtables.php' );

        $this->BEAN_TINYMCE_URI = plugin_dir_url(__FILE__) .'tinymce';
        $this->BEAN_TINYMCE_DIR = DIRNAME(__FILE__) .'/tinymce';

        add_action('init', array(&$this, 'action_admin_init'));
        add_action('admin_enqueue_scripts', array(&$this, 'action_admin_scripts_init'));
        add_action('wp_enqueue_scripts', array(&$this, 'action_frontend_scripts'));
	}

	/**
	 * ENQUEUE STYLES
	 *
	 * @return	void
	 */
	function action_frontend_scripts() {
		$default_css_url = plugin_dir_url(__FILE__) . 'css/bean-pricingtables.css';
		$default_js_url = plugin_dir_url(__FILE__) . 'js/bean-pricingtables.js';

		wp_enqueue_style( 'bean-pricingtables-style', $default_css_url, false, '1.0', 'all' );
		wp_enqueue_script('bean-pricingtables', $default_js_url, 'jquery', '1.0', true);
		wp_enqueue_script('bean-powertip', $powertip_js_url, 'jquery', '1.0', true);
	}

	/**
	 * ENQUEUE SCRIPTS
	 *
	 * @return	void
	 */
	function action_admin_scripts_init() {
		//CSS
		wp_enqueue_style( 'bean-pricingtable-popup', $this->BEAN_TINYMCE_URI . '/css/popup.css', false, '1.0', 'all' );
		//JS
		wp_localize_script( 'jquery', 'BeanPricingTables', array('plugin_folder' => plugin_dir_url(__FILE__)) );
	}

	/**
	 * REGISTERS TINYMCE RICH EDITOR BUTTONS
	 *
	 * @return void
	 */
	function action_admin_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;

		if ( get_user_option('rich_editing') == 'true' && is_admin() ) {
			add_filter( 'mce_external_plugins', array(&$this, 'add_rich_plugins') );
			add_filter( 'mce_buttons', array(&$this, 'register_rich_buttons') );
		}
	}

	/**
	 * Defines TinyMCE rich editor js plugin
	 *
	 * @return	void
	 */
	function add_rich_plugins( $plugin_array ) {
		$plugin_array['BeanPricingTables'] = $this->BEAN_TINYMCE_URI . '/plugin.js';
		return $plugin_array;
	}

	/**
	 * Adds TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function register_rich_buttons( $buttons ) {
		array_push( $buttons, "|", 'bean_pricingtable_button' );
		return $buttons;
	}
}

new Bean_BeanPricingTables;

endif;
?>