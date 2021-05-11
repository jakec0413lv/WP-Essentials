<?php //Used for custom post types [Filename => single-[custom_post_type_name].php]

get_header();

 while(have_posts()) {
 the_post(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg')?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title() ?></h1>
      <div class="page-banner__intro">
        <p>Custom Subtitle!</p>
      </div>
    </div>  
  </div>

  <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo site_url('/programs'); //Go to event?>"><i class="fa fa-home" aria-hidden="true"></i>All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>
    <div class = "generic-content">
        <?php the_content();
        $today = date('Ymd');
          $relatedEvents = new WP_Query(array(
            'posts_per_page' => 2, //Post Count [-1 == all posts that meet conditions] / [3 == 3 posts]
            'post_type' => 'event', //Post type -- useful for custom types
            'meta_key'=> 'event_date', //Necessary to orderby meta_value
            'orderby' =>  'meta_value_num', //default behavior is 'post_date', 'rand' = random, 'meta_value'
            'order' =>  'ASC', //default 'DESC'
            'meta_query' => array(
              array( //Serves as a further filter for custom queries --> only show events later than or equal to the current day
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ),
              array( //If the array of related programs contains the id number of the current program post, post it
                  'key' => 'related_programs',
                  "compare" => 'LIKE',
                  "value" => '"' . get_the_ID() .'"'
              )
            )
         ));
         if($relatedEvents->have_posts()){
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Event(s):</h2>';
            while ($relatedEvents->have_posts()) {
                   $relatedEvents->the_post(); //Gets appropriate data ready ?>
                   <div class="event-summary">
                       <a class="event-summary__date t-center" href="<?php the_permalink();?>">
                       <span class="event-summary__month"><?php 
                       $eventDate = new DateTime(get_field('event_date')); //Defaults to current date
                       echo $eventDate->format('M')?></span>
                       <span class="event-summary__day"><?php echo $eventDate->format('d')?></span>
                   </a>
                   <div class="event-summary__content">
                     <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                     <p><?php if (has_excerpt()){ // has handcrafted excerpt
                                 echo get_the_excerpt();
                           }else{
                             echo wp_trim_words(get_the_content(), 18);
                           }?> <a href="<?php the_permalink();?>" class="nu gray">Learn more</a></p>
                   </div>
                 </div>
             <?php } wp_reset_postdata(); //Custom Query cleanup
         }
          ?>
    </div>
 </div>

 <?php }

get_footer();
?> 