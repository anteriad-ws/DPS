<?php
/**
* Plugin Name: WS - Rest APIs
* Description: Rest API for Analytics Dashboard
* Version: 1
* Author: Shilpi
**/

add_action( 'rest_api_init', 'create_custon_endpoint' );
 
function create_custon_endpoint(){
    register_rest_route(
        'wp/v2',
        '/custom-ep',
        array(
            'methods' => 'GET',
            'callback' => 'get_response',
        )
    );
}
 
function get_response() {
    // your code
//    return 'This is your data!';
    $posts = get_posts();
    $response = new WP_REST_Response($posts);
    $response->set_status(200);

    return $response;
}
