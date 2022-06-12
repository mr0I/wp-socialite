<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function loginUser($user_name,$password)
{
    $credentials = array(
        'user_login'     => sanitize_user($user_name),
        'user_password'  => esc_attr($password)
    );

    return wp_signon( $credentials, false );
}