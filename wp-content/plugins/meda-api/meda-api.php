<?php

/***** 
 * 
 * Plugin Name: Meda's Custom API
 * Description: Voodoo Child is the Best
 * Version: 1.0
 * Author: Meda (Voodoo Child)
 * Auto UTI: http://medafx.com
 * 
 * ******/

 // Replaces the excerpt "Read More" text by a link
function custom_excerpt_more($more) {
    global $post;
    $more_text = '...Meda What';
    return '… <a href="'. get_permalink($post->ID) . '">' . $more_text . '</a>';
 }
 add_filter('excerpt_more', 'custom_excerpt_more');

function meda_get_all_pages() {
    return 'Get all pages route';
}

function meda_all_posts(){
        
    $args = [
        'numberposts' => 99999,
        'post_type' => 'post',
    ];

    $posts = get_posts($args);
    $data = [];
    $i = 0;

    foreach($posts as $post) {
        $data[$i]['id'] = $post ->ID;
        $data[$i]['title'] = $post->post_title;
        $data[$i]['date'] = get_the_time('Y-m-d', $post->ID);
        $data[$i]['excerpt'] = wp_trim_words(get_the_excerpt($post->ID, null), 55, null);  
        $data[$i]['slug'] = $post->post_name;       
        $data[$i]['content'] = $post->post_content;        
        $data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        $data[$i]['featured_image']['medium'] = get_the_post_thumbnail_url($post->ID, 'medium');
        $data[$i]['featured_image']['large'] = get_the_post_thumbnail_url($post->ID, 'large');
        $i++;
    }

    return $data;
};

function meda_one_single_post( $slug ) {

    $args = [
        'name' => $slug['slug'],
        'post_type' => 'post'
    ];

    $post = get_posts( $args );

    $data['id'] = $post[0]->ID;
    $data['title'] = $post[0]->post_title;
    $data['date'] = get_the_time('Y-m-d', $post[0]->ID);
    $data['excerpt'] = get_the_excerpt($post[0]->ID, null);
    $data['content'] = $post[0]->post_content;
    $data['slug'] = $post[0]->post_name;
    $data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
    $data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
    $data['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large'); 

    return $data;
};

add_action('rest_api_init', function() {

    // registering route to access all posts
    register_rest_route( 'voodoo/v1', 'posts', [
        'methods' => 'GET',
        'callback' => 'meda_all_posts',
    ]);

    // registering route to access a single post by using a slug
    register_rest_route( 'voodoo/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'meda_one_single_post',
    ));

    //registering route to access all pages
    register_rest_route( 'voodoo/v1', 'pages', array(
        'methods' => 'GET',
        'callback' => 'meda_get_all_pages',
    ));
});
