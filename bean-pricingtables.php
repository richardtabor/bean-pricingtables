<?php
/**
 * Plugin Name: Bean Pricing Tables
 * Plugin URI: http://themebeans.com/plugin/bean-pricing-tables/?ref=plugin_bean_pricing
 * Description: Enables shortcode pricing tables to be added to your theme.
 * Version: 1.1
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
/* PLUGIN UPDATER
/*===================================================================*/
//CONSTANTS
define( 'BEANPRICINGTABLES_EDD_TB_URL', 'http://themebeans.com' );
define( 'BEANPRICINGTABLES_EDD_TB_NAME', 'Bean Pricing Tables' );

//INCLUDE UPDATER
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/updates/EDD_SL_Plugin_Updater.php' );
}

include( dirname( __FILE__ ) . '/updates/EDD_SL_Setup.php' );

//LICENSE KEY
$license_key = trim( get_option( 'edd_beanpricingtables_license_key' ) );

//CURRENT BUILD
$edd_updater = new EDD_SL_Plugin_Updater( BEANPRICINGTABLES_EDD_TB_URL, __FILE__, array(
		'version' 	=> '1.3',
		'license' 	=> $license_key,
		'item_name' => BEANPRICINGTABLES_EDD_TB_NAME,
		'author' 	=> 'ThemeBeans'
	)
);





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




/*===================================================================*/
/* ADMIN PAGE FOR LICENSE ENTRY
/*===================================================================*/
//MENU LINK
function bean_pricingtables_admin_menu() {
	add_options_page(
		__('Bean Pricing Tables', 'bean'), __('Bean Pricing Tables', 'bean'), 'manage_options', 'bean_pricingtables', 'bean_pricingtables_admin_page');
}
add_action('admin_menu', 'bean_pricingtables_admin_menu');

//PRINT PAGE
function bean_pricingtables_admin_page()
{
	$license = get_option( 'edd_beanpricingtables_license_key' );
	$status = get_option( 'edd_beanpricingtables_license_status' );
	?>
		<div class="wrap">
		<h2><?php echo esc_html__('Bean Pricing Tables Plugin', 'bean'); ?></h2>
		<p>This plugin allows you to display one, two, and three column responsive pricing tables via a shortcode generator on the WordPress Visual Editor. If you like this plugin, consider checking out our other <a href="http://themebeans.com/plugins/?ref=bean_pricingtables" target="blank">Free Plugins</a> and our <a href="http://themebeans.com/themes/?ref=bean_pricingtables" target="blank">Premium WordPress Themes</a>. Cheers!</p><br />

		<h4 style="font-size: 15px; font-weight: 600; color: #222; margin-bottom: 10px;"><?php _e('Activate License'); ?></h4>
		<p>Enter the license key <code style="padding: 1px 5px 2px; background-color: #FFF; border-radius: 2px; font-weight: bold; font-family: 'Open Sans',sans-serif;">BEANPRICINGTABLES</code>, hit Save, then Activate, to turn on the plugin updater. You'll then be able to update this plugin from your Plugins Dashboard when future updates are available.</p>

		<form method="post" action="options.php">
			<?php settings_fields('edd_beanpricingtables_license'); ?>
			<input id="edd_beanpricingtables_license_key" name="edd_beanpricingtables_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
				<?php if( $status !== false && $status == 'valid' ) { ?>
					<?php wp_nonce_field( 'edd_beanpricingtables_nonce', 'edd_beanpricingtables_nonce' ); ?>
					<input type="submit" class="button-secondary" name="edd_beanpricingtables_license_deactivate" style="outline: none!important;" value="<?php _e('Deactivate License'); ?>"/>
					<span style="color: #7AD03A;"><?php _e('&nbsp;&nbsp;Good to go!'); ?></span>
				<?php } else {
					wp_nonce_field( 'edd_beanpricingtables_nonce', 'edd_beanpricingtables_nonce' ); ?>
					<input type="submit" name="submit" id="submit" class="button button-secondary" value="Save License Key">
					<input type="submit" class="button-secondary" name="edd_beanpricingtables_license_activate" style="outline: none!important;" value="<?php _e('Activate License'); ?>"/>
					<span style="color: #DD3D36;"><?php _e('&nbsp;&nbsp;Inactive'); ?></span>
				<?php } ?>
		</form>
    </div>
    <?php
} //END function bean_pricingtables_admin_page()
?>