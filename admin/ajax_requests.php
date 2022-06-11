<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );



function githubOauth_callback(){
    //check_ajax_referer( 'vovJ0hYrMya1ywMG', 'security' );

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


//            $result = array(
//                'success' => true,
//                'res' => $response,
//                'res2' => $response2,
//            );
//            wp_send_json($result);
//            exit();



            if (!empty($response->login) && !empty($response->node_id )) {
                $userId = $response->id;
                $userName = $response->login;

                global $wpdb;
                $user_meta_table = $wpdb->prefix . 'usermeta';
                $users_table = $wpdb->prefix . 'users';

                $find_userId = $wpdb->get_results(
                    "SELECT user_id FROM $user_meta_table WHERE `user_id`='$userId' AND `meta_key`='git_user'");
                $duplicateEntry = $wpdb->get_results(
                    "SELECT * FROM $users_table WHERE `user_login`='$userName'");

                if ($find_userId){
//                    $result = array(
//                        'success' => true,
//                        'res' => 2,
//                    );
//                    wp_send_json($result);
//                    exit();

                    $user = get_user_by_id($find_userId);
                    //login
                } elseif ($duplicateEntry){
//                    $result = array(
//                        'success' => true,
//                        'res' => 3,
//                    );
//                    wp_send_json($result);
//                    exit();
                    $user = $duplicateEntry;
                    //login
                } else {
//                    $result = array(
//                        'success' => true,
//                        'res' => 4,
//                    );
//                    wp_send_json($result);
//                    exit();

                    $password = random_password();
                    $userMail = $response->email;
                    $userNickName = $response->name;

                    $userData = [
                        "user_login" => $userName,
                        "user_pass" => $password,
                        "user_email" => $userMail,
                        "user_nicename" => $userNickName,
                        "display_name" => $userName,
                        "role" => 'customer'
                    ];
                    $res = wp_insert_user($userData);

                    if ($res) {
                        $credientals = array(
                            'user_login'     => sanitize_user($userName),
                            'user_password'  => esc_attr($password)
                        );

                        $login = wp_signon( $credientals, false );
                        if ($login){
                            $insertArray = [];
                            array_push($insertArray,[
                                'user_id' => absint($userId),
                                'meta_key' => 'git_user',
                                'meta_value' => true
                            ]);

                            foreach ($insertArray as $array){
                                $wpdb->insert( $user_meta_table , array(
                                    'user_id' => $array['user_id'],
                                    'meta_key' => $array['meta_key'],
                                    'meta_value' => $array['meta_value']
                                ), array( '%d','%s', '%s' ));
                            }

                            wp_set_current_user( $userId, $userName );
                            wp_set_auth_cookie( $userId );


//                            setcookie("auth" , json_encode($response_msg));
                            //header("Location: http://localhost/wordpress");
                        }
                    }
                }




            }
        }
    }


    $result = array(
        'success' => false
    );
    wp_send_json($result);
    exit();
}
add_action( 'wp_ajax_githubOauth', 'githubOauth_callback' );
add_action( 'wp_ajax_nopriv_githubOauth', 'githubOauth_callback' );