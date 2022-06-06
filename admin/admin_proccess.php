<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('admin_menu', function (){
    global $wpsoc_page_hook;
    
    $wpsoc_page_hook = add_menu_page(
        __('Social Login', 'socialite_lan'),
        __('Social Login', 'socialite_lan'),
        'administrator',
        'wp_soc',
        function(){include(SOC_ADMIN_VIEW . 'settings/index.php');},
        'dashicons-post-status' // plugin_dir_url( __FILE__ ) . '/../../assets/images/icons_images/car.png',
    );


//    add_submenu_page(
//        'wpsoc',
//        __('Agencies', 'socialite_lan'),
//        __('Agencies', 'socialite_lan'),
//        'edit_posts',
//        'rad_agencies',
//        function(){include(RAD_ADMIN_VIEW . 'agencies_settings.php');}
//    );
});


require_once SOC_ADMIN_VIEW . 'settings/partials/general_settings.php';


