<?php 
/**
 * Template Name: Landing Page
 */

wp_enqueue_style('themedy-landingpage-style', CHILD_THEME_LIB_URL.'/css/landingpage.css','',1,'screen');
wp_deregister_style('themedy-child-theme-style'); 	

$post_obj = $wp_query->get_queried_object();
$post_name = $post_obj->post_name;

do_action( 'genesis_doctype' );
do_action( 'genesis_title' );
wp_head(); 
?>
    </head>
 
    <body id="<?php echo $post_name; ?>" class="custom">
        <div id="wrap">
			<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div id="content" class="hfeed">
                    <div class="post-<?php the_ID(); ?> type-landingpage hentry">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php edit_post_link('Edit This Page', '<p class="edit-this"></p>'); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </body>
</html>