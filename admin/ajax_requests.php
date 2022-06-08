<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );



function githubOauth_callback(){
    //check_ajax_referer( 'vovJ0hYrMya1ywMG', 'security' );

    $github_code =  ($_GET['code']) ? $_GET['code'] : false;

    $url  = 'https://github.com/login/oauth/access_token';
        $body = array(
                "client_id" => "e03b8f55700cf59a7e79",
                "client_secret" => "2ba313e5ce6a8cdc3b500b8cd618d8ac03c26655",
                "code" => $github_code,
            );
        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'sslverify'   => false,
            'headers'     => array(
                'Accept' => 'application/json, text/plain, */*',
                'Content-Type'  => 'application/json',
            ),
            'body'        => json_encode($body),
        );

        $request = wp_remote_post( $url, $args );
        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            error_log( print_r( $request, true ) );
        }

        $response = wp_remote_retrieve_body( $request );
        echo json_encode($response);
        exit();

}
add_action( 'wp_ajax_githubOauth', 'githubOauth_callback' );
add_action( 'wp_ajax_nopriv_githubOauth', 'githubOauth_callback' );