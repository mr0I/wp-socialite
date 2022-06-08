<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('init',function (){
    add_shortcode('github_oauth' , 'show_github_oauth_btn');
});


function show_github_oauth_btn($atts, $content = null)
{
    ob_start();
    include(plugin_dir_path( __FILE__ ).'../front/views/github_btn.php');
    return do_shortcode(ob_get_clean());
}
