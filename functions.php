<?php

function university_files() {
    wp_enqueue_script('main-university-scripts', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true); //Load javascript files [nickname, get_theme_file_uri(relative_path), dependencies ?, version #, load_at_bottom]
    wp_enqueue_style('custom_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); //Import custom fonts
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());  //import css
    wp_enqueue_style('university_icons', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //Import link buttons
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');//2nd var will show up in WP admin screen, dynamic menu initialization
    register_nav_menu('footerLocation1', 'Footer Menu Location 1');
    register_nav_menu('footerLocation2', 'Footer Menu Location 2');
    add_theme_support('title-tag'); //Add dynamic titles based on URL [check out add_theme_support documentation!]
}

add_action('after_setup_theme', 'university_features')
?>

