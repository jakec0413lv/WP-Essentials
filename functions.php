<?php

//Reusable Page Banner Function

function pageBanner($args = NULL) { //Allows for values to be optional!

    //Intelligent Defaults

if(!$args['title']){
    $args['title'] = get_the_title();
}

if(!$args['subtitle']){
    $args['subtitle'] = get_field('page_banner_subtitle');
}

if(!$args['photo']){
    if(get_field('page_banner_background_image') AND !is_archive() AND !is_home()){
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    }else{
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
}


?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']//Custom Subtitle?></p>
      </div>
    </div>  
  </div>

<?php }
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

    //Dynamic Menu Items
    register_nav_menu('headerMenuLocation', 'Header Menu Location');//2nd var will show up in WP admin screen, dynamic menu initialization
    register_nav_menu('footerLocation1', 'Footer Menu Location 1');
    register_nav_menu('footerLocation2', 'Footer Menu Location 2');
    add_theme_support('title-tag'); //Add dynamic titles based on URL [check out add_theme_support documentation!]
    
    //Featured Images

        add_theme_support('post-thumbnails'); //Featured Images Support

        //Image Sizes [Regenerate Thumbnails Plug In / Manual Image Crop by Tomasz for cropping needs]
            add_image_size('professorLandscape', 400, 260, true); //Additional Image Sizes with cropping array( [left, right, center], [top, center, bottom])
            add_image_size('professorPortrait', 480, 650, true); //Additional Image Sizes with automatic cropping on the center of the photograph
            add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

//Remember "npm run dev"!!


function university_post_types() { //Custom post types
    
    //Event Post Type

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

    //Program Post Type

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

    //Professor Post Type

    register_post_type('professor', array(
        'show_in_rest' => true, //Modern block editor support
    "supports" => array('title', 'editor', 'thumbnail' /* 'custom-fields' Use Advanced Custom Fields ACF */), //Supporting excerpts
        'public' => true, // Post types are visible
        'labels' => array(
            'name' => 'Professors', //Name
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor',

        ),
        'menu_icon' => 'dashicons-welcome-learn-more' //Dashicon!
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

//Program Default 
    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) { //DIesn;t effect back end, only does event archive, and prevents affecting custom queries
        $query->set('post_per_page', -1); //-1 all that match
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
}


add_action('pre_get_posts', 'university_adjust_queries');

?>

