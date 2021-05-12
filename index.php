<?php //Fallback page
get_header();

pageBanner(array(
  'title' => "Welcome to our blog!",
  'subtitle' => 'Keep up with our latest news!'
));
?>

  <div class="container container--narrow page-section">
    <?php
    
    while(have_posts()) {
        the_post(); ?>
        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink();?>"> <?php the_title(); ?></a></h2>

            <div class="metabox">
                <p>Posted by <?php the_author_posts_link();?> on <?php  the_time('n.j.y'); //Look up params?> in <?php echo get_the_category_list(', '); //'get' only returns values, need to echo ?></p>
            </div>

            <div class="generic-content">
                <?php the_excerpt(); //Small preview of content?>
                <p><a class="btn btn--blue" href= "<?php the_permalink();?>">Continue Reading &raquo;</a></p>
            </div>
        </div>
    <?php }
    echo paginate_links(); //Pagination links for multiple pages
    ?>
    
    </div>

<?php
get_footer();
?>