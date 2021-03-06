<?php //Template File for Specific Page 'past-events' ?>

<?php
get_header();
pageBanner(array(
  'title' => "Past Events",
  'subtitle' => "See what we've done!"
));

?>

  <div class="container container--narrow page-section">
    <?php
    //Custom Queries!
    $today = date('Ymd');
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1), //Allows for pagination, with a default number of 1
      'posts_per_page' => 2, //Post Count [-1 == all posts that meet conditions] / [3 == 3 posts]
      'post_type' => 'event', //Post type -- useful for custom types
      'meta_key'=> 'event_date', //Necessary to orderby meta_value
      'orderby' =>  'meta_value_num', //default behavior is 'post_date', 'rand' = random, 'meta_value'
      'order' =>  'ASC', //default 'DESC'
      'meta_query' => array(
        array( //Serves as a further filter for custom queries --> only show events later than or equal to the current day
          'key' => 'event_date',
          'compare' => '<',
          'value' => $today,
          'type' => 'numeric'
        )
      )
   ));
    while($pastEvents->have_posts()) {
        $pastEvents->the_post(); ?>
        <div class="event-summary">
                    <a class="event-summary__date t-center" href="<?php the_permalink();?>">
                    <span class="event-summary__month"><?php $eventDate = new DateTime(get_field('event_date')); //Defaults to current date
                    echo $eventDate->format('M')?></span>
                    <span class="event-summary__day"><?php echo $eventDate->format('d')?></span>
                </a>
                <div class="event-summary__content">
                  <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                  <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink();?>" class="nu gray">Learn more</a></p>
                </div>
              </div>
    <?php }
    echo paginate_links(array(
        'total' => $pastEvents->max_num_pages
    )); //Pagination links for multiple pages **** Only work with default queries!!
    ?>
    
    </div>

<?php
get_footer();
?>