<?php
/**
 * Template Name: Portfolio
 */

// Force layout to full-width-content
add_filter('genesis_pre_get_option_site_layout', 'themedy_portfolio_layout');
function themedy_portfolio_layout($layout) {
    $layout = 'full-width-content';
    return $layout;
}

// Class management
add_filter('post_class', 'portfolio_post_class');
function portfolio_post_class( $classes ) {
	global $loop_counter;
	
	// Remove 'entry' class
	$classes = array_diff($classes, array('entry'));	
	
	// Add classes
    $classes[] = 'portfolio-teaser one-third';
	if ( $loop_counter == 0 ) {
		$classes[] .= ' first';
    }
	
    return $classes;
}

// Remove Breadcrumbs
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

// Remove post info and meta info
remove_action('genesis_entry_footer', 'genesis_post_meta');
remove_action('genesis_entry_header', 'genesis_post_info', 12);

// Remove default content for this Page Template
remove_action('genesis_entry_content', 'genesis_do_post_image', 8);
remove_action('genesis_entry_content', 'genesis_do_post_content');
remove_action('genesis_entry_content', 'the_excerpt');

// Add Featured Image for the portfolio posts in this Page Template
add_action('genesis_entry_content', 'themedy_portfolio_do_post_image');
function themedy_portfolio_do_post_image() {
	if (genesis_get_image()) {
		echo '<div class="portfolio-thumb"><a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'">'.genesis_get_image( array( 'format' => 'html', 'size' => 'portfolio-thumbnail' ) ).'</a></div>';
	}
}

// Remove title
remove_action('genesis_entry_header', 'genesis_do_post_title');

// New Excerpt Length
global $portfolio_excerpt_length;
$portfolio_excerpt_length = get_post_meta($post->ID, 'themedy_portfolio_excerpt_length', true);
$portfolio_excerpt_length = !empty($portfolio_excerpt_length) ? $portfolio_excerpt_length : '20';
add_filter('excerpt_length', 'new_excerpt_length');
function new_excerpt_length() {
	global $portfolio_excerpt_length;
	return $portfolio_excerpt_length;
}

// New Excerpt More
add_filter('excerpt_more', 'new_excerpt_more');
function new_excerpt_more($more) {
	return '...';
}

// Add Content for the Portfolio posts in this Page Template
add_action('genesis_entry_content', 'themedy_portfolio_do_post_content');
function themedy_portfolio_do_post_content() { 
	global $loop_counter;
	$loop_counter++;
	?> 
    <h2 class="entry-title portfolio-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></h2>
    <div class="excerpt"><?php the_excerpt(); ?></div>
	<?php
} 

// Clear float using genesis_custom_loop() $loop_counter variable
// Outputs clearing div after every 3 posts
// $loop_counter is incremented after this function is run
add_action('genesis_after_entry', 'portfolio_after_post');
function portfolio_after_post() {
    global $loop_counter;
    
    if ( $loop_counter == 3 ) {
        $loop_counter = 0;
        echo '<div class="clear"></div>';
    }
}

// Remove standard loop
remove_action('genesis_loop', 'genesis_do_loop');

// Add custom loop
add_action('genesis_loop', 'portfolio_loop');
function portfolio_loop() {
	global $post;
	$portfolio_category = get_post_meta($post->ID, 'themedy_portfolio_category', true);
	$portfolio_amount = get_post_meta($post->ID, 'themedy_portfolio_amount', true);
	$portfolio_amount = !empty($portfolio_amount) ? $portfolio_amount : '9';
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        
    $args = array('post_type' => 'portfolio', 'showposts' => $portfolio_amount, 'paged' => $paged, 'portfolio-category' => $portfolio_category);
    
    genesis_custom_loop( $args );
}

genesis();