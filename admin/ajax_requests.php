<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once SOC_INCS . 'functions.php';


function githubOauth_callback(){
    $github_code =  ($_GET['code']) ? $_GET['code'] : false;

    if ($github_code){
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
        parse_str($response,$output);
        $access_token = $output['access_token'];


        if (! empty($access_token)) {
            $args = array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $access_token,
                ),
            );
            $response = wp_remote_get( 'https://api.github.com/user', $args );
            $response = json_decode($response['body']);


            if (!empty($response->login) && !empty($response->node_id )) {
                global $wpdb;
                $user_meta_table = $wpdb->prefix . 'usermeta';
                $users_table = $wpdb->prefix . 'users';

                $userId = $response->id;
                $userName = $response->login;

                $find_userId = $wpdb->get_results(
                    "SELECT * FROM $user_meta_table WHERE `meta_key`='git_user'AND `meta_value`='$userId'");
                $duplicateEntry = $wpdb->get_results(
                    "SELECT * FROM $users_table WHERE `user_login`='$userName'");

                if ($find_userId){
                    $userID = intval($find_userId[0]->user_id);
                    wp_safe_redirect(site_url(). '/auto_auth?pagename=auto_auth&s=' . $userID);
                } elseif ($duplicateEntry){
                    $user = $duplicateEntry;
                    wp_safe_redirect(site_url(). '/auto_auth?pagename=auto_auth&s=' . $user[0]->ID);
                } else {
                    $password = wp_generate_password(8,false);
                    $userMail = $response->email;
                    $userNickName = $response->name;

                    $userData = [
                        "user_login" => $userName,
                        "user_pass" => $password,
                        "user_email" => $userMail,
                        "user_nicename" => $userNickName,
                        "display_name" => $userName,
                        "role" => 'subscriber'
                    ];
                    try{
                        $res = wp_insert_user($userData);

                        if ($res) {
                            $login = loginUser($userName,$password);

                            if ($login){
                                $insertArray = [];
                                array_push($insertArray,[
                                    'user_id' => $res,
                                    'meta_key' => 'git_user',
                                    'meta_value' => absint($userId)
                                ]);

                                foreach ($insertArray as $array){
                                    $wpdb->insert( $user_meta_table , array(
                                        'user_id' => $array['user_id'],
                                        'meta_key' => $array['meta_key'],
                                        'meta_value' => $array['meta_value']
                                    ), array( '%d','%s', '%s' ));
                                }

                                wp_safe_redirect(site_url(). '/auto_auth?pagename=auto_auth&s=' . $res);
                            }
                        }
                    }catch (\Exception $e){
                        echo $e->getMessage();
                    }
                }
            }
        }
    } else {
        $result = array(
            'success' => false
        );
        wp_send_json($result);
        exit();
    }

    echo json_encode(false);
    exit();
}
add_action( 'wp_ajax_githubOauth', 'githubOauth_callback' );
add_action( 'wp_ajax_nopriv_githubOauth', 'githubOauth_callback' );