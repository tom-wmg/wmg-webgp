<?php
/*

Plugin Name: Web GP Wordpress plugin
Plugin URI: https://cloud.webmadegood.com/web-gp-wordpress-plugin
Description: Plugin which configures WebGP banner to appear on your GP practice website 
Version: 1.04
Author: Tom Davis, Web Made Good
Author URI: http://www.webmadegood.com/wordpress

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* ================================================================================================ */
/*                                  WP Plugin Update Server                                         */
/* ================================================================================================ */

/**
* Selectively uncomment the sections below to enable updates with WP Plugin Update Server.
*
* WARNING - READ FIRST:
* Before deploying the plugin or theme, make sure to change the following value
* - https://your-update-server.com  => The URL of the server where WP Plugin Update Server is installed
* - $prefix_updater                 => Replace "prefix" in this variable's name with a unique plugin prefix
*
* @see https://github.com/froger-me/wp-package-updater
**/

require_once plugin_dir_path( __FILE__ ) . 'lib/wp-package-updater/class-wp-package-updater.php';

/** Enable plugin updates with license check **/
$prefix_updater = new WP_Package_Updater(
 	'https://cloud.webmadegood.com/licences/',
 	wp_normalize_path( __FILE__ ),
 	wp_normalize_path( plugin_dir_path( __FILE__ ) ),
 	true
);

//add_options_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
function web_gp_add_settings_page()
{
	//$page_title: the title of the page
	//$menu_title: the name of the menu
	//$access_privileges: who has permission to access this page
	//$page_name: can be a unique string, but many developers prefer to use __FILE__ to ensure that the name is unique, and doesn’t have the potential to clash with any other pages
	//$callback: the function that will handle the creation of the options form

    add_options_page( 'Web GP Plugin', 'Web GP settings', 'manage_options', 'manage_web_gp_options', 'web_gp_build_settings_page' );
}

function web_gp_build_settings_page()
{
?>
    <h2>Web GP Plugin</h2>

    <form action="options.php" method="post">
        <?php 
        settings_fields('web_gp_settings_options');
        do_settings_sections(__FILE__); ?>
        <p><input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" /></p>
		<br />
		<p>For help and support with this plugin please email <a href="mailto:tom@kilodesign.com">tom@kilodesign.com</a></p>
    </form>

<?php
}

function web_gp_settings_options_validate($input)
{
	//no validation of this input at this time
    return $input;
}

function web_gp_build_settings_panel_section()
{
    echo '<p>This plugin allows control to show/hide the Web GP plugin and also insert a custom banner to show information when page loads. You will need to know your unique Web GP code.</p>';
}

function web_gp_build_settings_panel_field_key()
{
	$defaults = array(
		'web_gp_settings_key' => ''
	);
	$options = wp_parse_args(get_option('web_gp_settings_options'), $defaults);
    echo '<input id="web_gp_settings_key" name="web_gp_settings_options[web_gp_settings_key]" type="text" value="' . esc_attr($options['web_gp_settings_key']) . '" />';
}

function web_gp_build_settings_panel_field_active()
{
	$defaults = array(
		'web_gp_settings_active' => '0'
	);
	$options = wp_parse_args(get_option('web_gp_settings_options'), $defaults);
    //echo "<pre>" . print_r($options, true). "</pre>";
	?>
        <p><input type="radio" name="web_gp_settings_options[web_gp_settings_active]" value="1" <?php checked($options['web_gp_settings_active'] == 1); ?> /> Yes </p>
        <p><input type="radio" name="web_gp_settings_options[web_gp_settings_active]" value="0" <?php checked($options['web_gp_settings_active'] == 0); ?> /> No </p>
    <?php
}

function web_gp_build_settings_panel_field_banner_active()
{
	$defaults = array(
		'web_gp_settings_banner_active' => '0'
	);
	$options = wp_parse_args(get_option('web_gp_settings_options'), $defaults);
    //echo "<pre>" . print_r($options, true). "</pre>";
	?>
        <p><input type="radio" name="web_gp_settings_options[web_gp_settings_banner_active]" value="1" <?php checked($options['web_gp_settings_banner_active'] == 1); ?> /> Yes </p>
        <p><input type="radio" name="web_gp_settings_options[web_gp_settings_banner_active]" value="0" <?php checked($options['web_gp_settings_banner_active'] == 0); ?> /> No </p>
    <?php
}

function web_gp_build_settings_panel_field_banner_text()
{
    $options = get_option('web_gp_settings_options', array() );
    $content = isset($options['web_gp_banner_text'] ) ?  $options['web_gp_banner_text'] : false;
    wp_editor($content, 'web_gp_banner_text', array( 
        'textarea_name' => 'web_gp_settings_options[web_gp_banner_text]',
        'media_buttons' => false,
    ));
}

function web_gp_build_settings_panel_field_styles()
{
	echo <<<EOT
		<script>
			jQuery(document ).ready(function() {

				jQuery("#web_gp_settings_stylecolourtext").spectrum({
					//color: "#f00"
					showInput: true,
					preferredFormat: "hex",
				    allowEmpty: true
				});

				jQuery("#web_gp_settings_stylecolourbg").spectrum({
					//color: "#f00"
					showInput: true,
					preferredFormat: "hex",
				    allowEmpty: true
				});

				jQuery("#web_gp_settings_stylecolourlink").spectrum({
					//color: "#f00"
					showInput: true,
					preferredFormat: "hex",
				    allowEmpty: true
				});
			});
		</script>
		
EOT;

    $options = get_option('web_gp_settings_options', array() );
	$defaults = array(
		'web_gp_settings_stylecolourbg' => '',
		'web_gp_settings_stylecolourtext' => ''
	);
	$options = wp_parse_args(get_option('web_gp_settings_options'), $defaults);
    echo '<p style="padding-bottom: 12px;"><label style="min-width: 170px; display: inline-block;">Background colour:</label> <input id="web_gp_settings_stylecolourbg" name="web_gp_settings_options[web_gp_settings_stylecolourbg]" type="text" value="' . esc_attr($options['web_gp_settings_stylecolourbg']) . '" /></p>';
    echo '<p style="padding-bottom: 12px;"><label style="min-width: 170px; display: inline-block;">Text colour:</label> <input id="web_gp_settings_stylecolourtext" name="web_gp_settings_options[web_gp_settings_stylecolourtext]" type="text" value="' . esc_attr($options['web_gp_settings_stylecolourtext']) . '" /></p>';
    echo '<p><label style="min-width: 170px; display: inline-block;">Link colour:</label> <input id="web_gp_settings_stylecolourlink" name="web_gp_settings_options[web_gp_settings_stylecolourlink]" type="text" value="' . esc_attr($options['web_gp_settings_stylecolourlink']) . '" /></p>';
}



function web_gp_register_settings()
{
	//register setting in options table
	//option_group, option name, args (validation callback function)
    register_setting( 'web_gp_settings_options', 'web_gp_settings_options', 'web_gp_settings_options_validate');
    
    //create section in sections page
    //section name, title, text callback function, slug of settings page
    add_settings_section( 'main', 'General settings', 'web_gp_build_settings_panel_section', __FILE__);

	//create Web GP key field
	//string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() 
    add_settings_field( 'web_gp_settings_key', 'Web GP key', 'web_gp_build_settings_panel_field_key', __FILE__, 'main');

	//create Web active field to live within section
    add_settings_field( 'web_gp_settings_active', 'Web GP active', 'web_gp_build_settings_panel_field_active', __FILE__, 'main');

	//create field to manage whether the banner is activated or not
    add_settings_field( 'web_gp_settings_banner_active', 'Show homepage banner?', 'web_gp_build_settings_panel_field_banner_active', __FILE__, 'main');

	//create banner text field
    add_settings_field('web_gp_banner_text', 
            __('Homepage banner text', 'web_gp' ), 
            'web_gp_build_settings_panel_field_banner_text', __FILE__, 'main'
    );

	//create field to show text and background colour settings
    add_settings_field( 'web_gp_settings_styles', 'Web GP styles', 'web_gp_build_settings_panel_field_styles', __FILE__, 'main');

}

function webgp_add_plugin_page_settings_link( $links )
{
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=manage_web_gp_options' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}


//front end functions
function web_gp_scripts()
{
	wp_enqueue_style('web_gp-css', plugins_url('wmg-webgp/css/wmg-web-gp.css'),false, '1.1', 'all');
    wp_enqueue_script('web_gp-js-utils', plugins_url('wmg-webgp/js/wmg-web-gp.utils.js'), array('jquery'));
    wp_enqueue_script('web_gp-js', plugins_url('wmg-webgp/js/wmg-web-gp.js'), array('jquery'));
}

function web_gp_scripts_admin()
{
	wp_enqueue_style('web_gp-css-spectrum', plugins_url('wmg-webgp/css/vendor/spectrum.css'),false, '1.1', 'all');
    wp_enqueue_script('web_gp-js-spectrum', plugins_url('wmg-webgp/js/vendor/spectrum.min.js'), array('jquery'));
}


function web_gp_banner()
{
	$defaults = array(
		'web_gp_settings_key' => '',
		'web_gp_settings_active' => 0
	);
	$options = wp_parse_args(get_option('web_gp_settings_options'), $defaults);

	$webGP_ID = $options['web_gp_settings_key'];
	$webGP_active = ($options['web_gp_settings_active'] == 1);
	$webGP_banner_active = ($options['web_gp_settings_banner_active'] == 1);
	$webGP_banner = wpautop($options['web_gp_banner_text']);
	$webGP_banner_style_bg = $options['web_gp_settings_stylecolourbg'];
	$webGP_banner_style_text = $options['web_gp_settings_stylecolourtext'];
	$webGP_banner_style_link = $options['web_gp_settings_stylecolourlink'];
	
	
	if ($webGP_active)
	{
		echo <<<EOT
			<!-- eConsult banner code START -->
			<script type="text/javascript">var eConsultHost = (("https:" == document.location.protocol) ? "https" : "http");document.write(decodeURIComponent("%3Cscript src='" + eConsultHost + "://assets.webgp.com/js/practiceBanner/embedBanner-1.1.js' type='text/javascript'%3E%3C/script%3E"));</script>
			<div id="eConsultOverlay"></div>
			<script language="javascript">
				var eConsultParams = {
					bannerId: '$webGP_ID',
					overlay: true,
					target: 'eConsultOverlay'
				};
				showBanner(eConsultParams);
			</script>
			<!-- eConsult banner code END -->
EOT;

	}
	
	if ($webGP_banner_active)
	{

		echo <<<EOT
		<!-- WebGP secondary banner code START -->
		<style>
			.wgp * {
				color: $webGP_banner_style_text !important;
			}
			
			.wgp a { 
				color: $webGP_banner_style_link !important;
			}
		</style>
		<div class="wgp wgp-overlay hide">
			<div class="wgp-wrap" style="background-color: $webGP_banner_style_bg; color: $webGP_banner_style_text !important;">
				<div class="wgp-inner">
					<a href="#" tabindex="0" id="wgp-btn" class="wgp-btn wgp-btn-close"><span class="sr-only">Close</span></a>
					
					<div class="wgp-content">
						$webGP_banner
					</div>
				</div>
			</div>
		</div>
		<!-- WebGP secondary banner code END -->
EOT;
		
	}
	
}

//add scripts for front end
add_action('wp_enqueue_scripts', 'web_gp_scripts');

//add admin scripts
add_action('admin_enqueue_scripts', 'web_gp_scripts_admin');

//add overlay code to bottom of page template
add_action('wp_footer', 'web_gp_banner');

//add admin menu settings
add_action( 'admin_menu', 'web_gp_add_settings_page' );

//add additional menu link to plugin page
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'webgp_add_plugin_page_settings_link');

//register admin menu settings
add_action( 'admin_init', 'web_gp_register_settings' );



?>