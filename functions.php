<?php

function university_files() {
    //wp_enqueue_script('main-university-scripts', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true); //Load javascript files [nickname, get_theme_file_uri(relative_path), dependencies ?, version #, load_at_bottom]
    //wp_enqueue_style('university_main_styles', get_stylesheet_uri());  //import css
    wp_enqueue_style('custom_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); //Import custom fonts

    if (strstr($_SERVER['SERVER_NAME'], 'http://fictional-university.local/')) { //Local server
        wp_enqueue_script('main-university-scripts', 'http://localhost:3000/bundled.js', NULL, '1.0', true); //Automatic workflow / refresh without manually refreshing
    }else { //Not local domain
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true); //Load javascript files [nickname, get_theme_file_uri(relative_path), dependencies ?, version #, load_at_bottom]
        wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'), NULL, '1.0', true); 
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'));  //import css
    }
    wp_enqueue_style('university_icons', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //Import link buttons
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');//2nd var will show up in WP admin screen, dynamic menu initialization
    register_nav_menu('footerLocation1', 'Footer Menu Location 1');
    register_nav_menu('footerLocation2', 'Footer Menu Location 2');
    add_theme_support('title-tag'); //Add dynamic titles based on URL [check out add_theme_support documentation!]
}

add_action('after_setup_theme', 'university_features');

//Remember "npm run dev"!!


function university_post_types() { //Custom post types
    register_post_type('event', array(
        'show_in_rest' => true, //Modern block editor support
    "supports" => array('title', 'editor', 'excerpt', /* 'custom-fields' Use Advanced Custom Fields ACF */), //Supporting excerpts
        'rewrite' => array('slug' => 'events'), //custom slug
        'has_archive' => true, //Support archive
        'public' => true, // Post types are visible
        'labels' => array(
            'name' => 'Events', //Name
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event',

        ),
        'menu_icon' => 'dashicons-calendar-alt' //Dashicon!
    ));

    register_post_type('program', array(
        'show_in_rest' => true, //Modern block editor support
    "supports" => array('title', 'editor', /* 'custom-fields' Use Advanced Custom Fields ACF */), //Supporting excerpts
        'rewrite' => array('slug' => 'programs'), //custom slug
        'has_archive' => true, //Support archive
        'public' => true, // Post types are visible
        'labels' => array(
            'name' => 'Programs', //Name
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program',

        ),
        'menu_icon' => 'dashicons-clipboard' //Dashicon!
    ));
    
}

add_action('init', 'university_post_types'); //Custom post types

//Default Query Adjustment
function university_adjust_queries($query) {
    $today = date('Ymd');
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) { //DIesn;t effect back end, only does event archive, and prevents affecting custom queries
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array( 
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            )));
    }

    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) { //DIesn;t effect back end, only does event archive, and prevents affecting custom queries
        $query->set('post_per_page', -1); //-1 all that match
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
}


add_action('pre_get_posts', 'university_adjust_queries');

?>

