
<?php //Use for single pages

get_header();

 while(have_posts()) {
 the_post(); 
 pageBanner(); //See functions.php?>
    


  <div class="container container--narrow page-section">

    <?php
        $parentID = wp_get_post_parent_id(get_the_ID());

            if($parentID !== 0){ //Checks for a child page for selective rendering
                ?>
                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($parentID); //Gets permalink for parent id?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parentID); //Outputs parent title ?></a> <span class="metabox__main"><?php the_title()?></span></p>
                </div><?php
            }
    ?>

<?php
 $testArray = get_pages(array(
     'child_of' => get_the_ID()
 ));

if ($parentID or $testArray ) { ?>


    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($parentID) ?>"><?php echo get_the_title($parentID)?></a></h2>
      <ul class="min-list">
        <?php //Output all child elements [Menu of child page links]
            if ($parentID) {
                $findChildrenOf = $parentID; //Viewing child page
            } else {
                $findChildrenOf = get_the_ID(); //Viewing parent page
            }

            wp_list_pages(array(
                'title_li' => NULL, //No title title
                'child_of' => $findChildrenOf,
                'sort_column' => 'menu_order' //Changes order of list options
            ));
        ?>
      </ul>
    </div>
<?php } ?>
    <div class="generic-content">
      <?php the_content() ?>
    </div>

  </div>

 <?php } 

get_footer();
?> 

