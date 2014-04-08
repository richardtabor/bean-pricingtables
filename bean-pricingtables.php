<?php
/**
 * Plugin Name: Bean Pricing Tables
 * Plugin URI: http://themebeans.com/plugin/bean-pricing-tables/?ref=plugin_bean_pricing
 * Description: Enables shortcode pricing tables to be added to your theme.
 * Version: 1.2.1
 * Author: ThemeBeans
 * Author URI: http://themebeans.com/?ref=plugin_bean_pricing
 *
 *
 * @package Bean Plugins
 * @subpackage PricingTables
 * @author ThemeBeans
 * @since PricingTables 1.0
 */




/*===================================================================*/
/*	MAKE SURE WE DO NOT EXPOSE ANY INFO IF CALLED DIRECTLY
/*===================================================================*/
if ( !function_exists( 'add_action' ) )
{
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


/*===================================================================*/
/*
/* PLUGIN FEATURES SETUP
/*
/*===================================================================*/

$bean_plugin_features[ plugin_basename( __FILE__ ) ] = array(
        "updates"       => true    // Whether to utilize plugin updates feature or not
    );


if ( ! function_exists( 'bean_plugin_supports' ) ) {
    function bean_plugin_supports( $plugin_basename, $feature ) {
        global $bean_plugin_features;

        $setup = $bean_plugin_features;

        if( isset( $setup[$plugin_basename][$feature] ) && $setup[$plugin_basename][$feature] )
            return true;
        else
            return false;
    }
}


/*===================================================================*/
/*
/* PLUGIN UPDATER FUNCTIONALITY
/*
/*===================================================================*/
define( 'EDD_BEANPRICINGTABLES_TB_URL', 'http://themebeans.com' );
define( 'EDD_BEANPRICINGTABLES_NAME', 'Bean Pricing Tables' );

if ( bean_plugin_supports ( plugin_basename( __FILE__ ), 'updates' ) ) : // check to see if updates are allowed; only import if so

//LOAD UPDATER CLASS
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) 
{
	include( dirname( __FILE__ ) . '/updates/EDD_SL_Plugin_Updater.php' );
}
//INCLUDE UPDATER SETUP
include( dirname( __FILE__ ) . '/updates/EDD_SL_Activation.php' );


endif; // END if ( bean_plugin_supports ( plugin_basename( __FILE__ ), 'updates' ) )


/*===================================================================*/
/* UPDATER SETUP
/*===================================================================*/
function beanpricingtables_license_setup() 
{
	add_option( 'edd_beanpricingtables_activate_license', 'BEANPRICINGTABLES' );
	add_option( 'edd_beanpricingtables_license_status' );
}
add_action( 'init', 'beanpricingtables_license_setup' );

function edd_beanpricingtables_plugin_updater() 
{
    // check to see if updates are allowed; don't do anything if not
    if ( ! bean_plugin_supports ( plugin_basename( __FILE__ ), 'updates' ) ) return;

	//RETRIEVE LICENSE KEY
	$license_key = trim( get_option( 'edd_beanpricingtables_activate_license' ) );

	$edd_updater = new EDD_SL_Plugin_Updater( EDD_BEANPRICINGTABLES_TB_URL, __FILE__, array( 
			'version' 	=> '1.2.1',
			'license' 	=> $license_key,
			'item_name' => EDD_BEANPRICINGTABLES_NAME,
			'author' 	=> 'Rich Tabor / ThemeBeans'
		)
	);
}
add_action( 'admin_init', 'edd_beanpricingtables_plugin_updater' );


/*===================================================================*/
/* DEACTIVATION HOOK - REMOVE OPTION
/*===================================================================*/
function beanpricingtables_deactivate() 
{
	delete_option( 'edd_beanpricingtables_activate_license' );
	delete_option( 'edd_beanpricingtables_license_status' );
}
register_deactivation_hook( __FILE__, 'beanpricingtables_deactivate' );








/*===================================================================*/
/*
/* BEGIN BEAN PRICING TABLES PLUGIN
/*
/*===================================================================*/
/*===================================================================*/
/*	PLUGIN CLASS
/*===================================================================*/
if ( ! class_exists( 'Bean_BeanPricingTables' ) ) :
	class Bean_BeanPricingTables {

	    private $BEAN_TINYMCE_URI;
	    private $BEAN_TINYMCE_DIR;




		/*===================================================================*/
		/*	CONSTRUCT
		/*===================================================================*/
	    function __construct()
	    {
	    	require_once( DIRNAME(__FILE__) . '/bean-theme-pricingtables.php' );

	        $this->BEAN_TINYMCE_URI = plugin_dir_url(__FILE__) .'tinymce';
	        $this->BEAN_TINYMCE_DIR = DIRNAME(__FILE__) .'/tinymce';

	        add_action('init', array(&$this, 'action_admin_init'));
	        add_action('admin_enqueue_scripts', array(&$this, 'action_admin_scripts_init'));
	        add_filter('the_posts', array(&$this, 'add_frontend_scripts_if_shortcode_being_used'));
		}




		/*===================================================================*/
		/*	ENQUEUE FRONTEND
		/*===================================================================*/
		function add_frontend_scripts_if_shortcode_being_used($posts)
		{
            if (empty($posts)) return $posts;

            $shortcode_found = false;
            foreach ($posts as $post) {
                if (stripos($post->post_content, '[pricing_table') !== false) {
                    $shortcode_found = true;
                    break;
                }
            }

            if ($shortcode_found) {
                $default_css_url = plugin_dir_url(__FILE__) . 'css/bean-pricingtables.css';
                $default_js_url = plugin_dir_url(__FILE__) . 'js/bean-pricingtables.js';

                wp_enqueue_style( 'bean-pricingtables-style', $default_css_url, false, '1.0', 'all' );
                wp_enqueue_script('bean-pricingtables', $default_js_url, 'jquery', '1.0', true);
            }

            return $posts;
		}




		/*===================================================================*/
		/*	ENQUEUE ADMIN
		/*===================================================================*/
		function action_admin_scripts_init()
		{
			wp_enqueue_style( 'bean-pricingtable-popup', $this->BEAN_TINYMCE_URI . '/css/popup.css', false, '1.0', 'all' );
			wp_localize_script( 'jquery', 'BeanPricingTables', array('plugin_folder' => plugin_dir_url(__FILE__)) );
		}




		/*===================================================================*/
		/*	REGISTERS TINYMCE RICH EDITOR BUTTONS
		/*===================================================================*/
		function action_admin_init()
		{
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
				return;

			if ( get_user_option('rich_editing') == 'true' && is_admin() )
			{
				add_filter( 'mce_external_plugins', array(&$this, 'add_rich_plugins') );
				add_filter( 'mce_buttons', array(&$this, 'register_rich_buttons') );
			}
		}




		/*===================================================================*/
		/*	DEFINE TINYMCE RICH EDITOR JS PLUGIN
		/*===================================================================*/
		function add_rich_plugins( $plugin_array )
		{
			$plugin_array['BeanPricingTables'] = $this->BEAN_TINYMCE_URI . '/plugin.js';
			return $plugin_array;
		}




		/*===================================================================*/
		/*	ADDS TINYMCE BUTTON
		/*===================================================================*/
		function register_rich_buttons( $buttons )
		{
			array_push( $buttons, "|", 'bean_pricingtable_button' );
			return $buttons;
		}
	}

	new Bean_BeanPricingTables;

endif; //END class Bean_BeanPricingTables