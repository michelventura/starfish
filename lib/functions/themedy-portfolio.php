<?php
/*----------------------------------------------------------

Description: 	Portfolio functionality for Themedy themes

----------------------------------------------------------*/

// Image Size
add_image_size('portfolio-thumbnail', 352, 250, TRUE);

// Portfolio Post Type
add_action('init','themedy_create_portfolio_init');
function themedy_create_portfolio_init()  {
	$labels = array
	(
		'name' => _x('Portfolio', 'post type general name', 'themedy'),
		'singular_name' => _x('Portfolio Item', 'post type singular name', 'themedy'),
		'add_new' => _x('Add New', 'Portfolio Item', 'themedy'),
		'add_new_item' => __('Add New Portfolio Item', 'themedy'),
		'edit_item' => __('Edit Portfolio Item', 'themedy'),
		'new_item' => __('New Portfolio Item', 'themedy'),
		'view_item' => __('View Portfolio Item', 'themedy'),
		'search_items' => __('Search Portfolio', 'themedy'),
		'not_found' =>  __('No portfolio items found', 'themedy'),
		'not_found_in_trash' => __('No portfolio items found in Trash', 'themedy'), 
		'parent_item_colon' => ''
	);
	$support = array
	(
		'title',
		'editor',
		'author',
		'thumbnail',
		'custom-fields',
		'comments',
		'excerpt',
		'genesis-seo', 
		'genesis-layouts',
		'revisions'
	);
	$args = array
	(
		'labels' => $labels,
		'public' => TRUE,
		'rewrite' => array('slug'=>'portfolio'),
		'capability_type' => 'page',
		'hierarchical' => FALSE,
		'query_var' => true,
		'supports' => $support,
		'taxonomies' => array('portfolio-category'),
		'menu_position' => 5
	); 
	register_post_type('portfolio',$args);
	
	register_taxonomy(
        'portfolio-category',        
        'portfolio',
        array(
            'hierarchical' => TRUE,
            'label' => 'Portfolio Categories',
            'query_var' => TRUE,
            'rewrite' => TRUE,
        )
    );  
}

// jQuery Script for Edit Page
add_action( 'admin_enqueue_scripts', 'themedy_portfolio_template_scripts' );
function themedy_portfolio_template_scripts($hook) {
    if( 'post.php' != $hook )
        return;
    wp_enqueue_script( 'themedy-admin', CHILD_THEME_LIB_URL.'/js/themedy-admin.js', array('jquery'));
}

// Only Show Meta Box on Proper Pages
add_filter( 'admin_body_class', 'themedy_portfolio_admin_body_class' );
function themedy_portfolio_admin_body_class( $classes ) {
	global $post_id;
	if ( isset($_GET['post']) or isset($_POST['post_ID']) ) {
		$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
		if ('page_portfolio.php' == get_post_meta($post_id,'_wp_page_template',TRUE) ) {
			$classes .= 'themedy-portfolio';
		}
	}
	return $classes;
}

// Add Portfolio Meta Box
add_action('add_meta_boxes', 'themedy_add_portfolio_meta_box');
function themedy_add_portfolio_meta_box() {
	add_meta_box( 'themedy_portfolio_meta_box', 'Portfolio Options', 'themedy_portfolio_meta_box', 'page', 'side' );
}

function themedy_portfolio_meta_box( $post ) { 
	$portfolio_category = get_post_meta($post->ID, 'themedy_portfolio_category', true);
	$portfolio_amount = get_post_meta($post->ID, 'themedy_portfolio_amount', true);
	$portfolio_excerpt_length = get_post_meta($post->ID, 'themedy_portfolio_excerpt_length', true);
	
	$portfolio_amount = !empty($portfolio_amount) ? $portfolio_amount : '9';
	$portfolio_excerpt_length = !empty($portfolio_excerpt_length) ? $portfolio_excerpt_length : '20';
	
	$taxonomy = 'portfolio-category';
	$terms = get_terms($taxonomy );
	if ( !empty( $terms ) ) {
		?> <p><strong><?php echo __("Category", "themedy"); ?></strong></p>
		<label class="screen-reader-text" for="themedy_portfolio_category"><?php echo __('Portfolio Category ID', 'themedy'); ?></label>
		<select id="portfolio_category" name="themedy_portfolio_category">
			<option <?php if ($portfolio_category == '') echo 'selected="selected"'; ?> value=""><?php echo __("[ Show All Items ]", "themedy"); ?></option> 
            <?php foreach ( $terms as $term ) {
                ?>
                <option <?php if ($portfolio_category == $term->slug) echo 'selected="selected"'; ?> value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                <?php
            }
		echo '</select>';
	} else { echo '<input type="hidden" id="themedy_portfolio_category" name="themedy_portfolio_category" value="" />'; }
	
	echo '<p><strong>'.__("Items per Page", "themedy").'</strong></p>';
	echo '<input type="text" size="4" id="themedy_portfolio_amount" name="themedy_portfolio_amount" value="'.$portfolio_amount.'" />';
	
	echo '<p><strong>'.__("Excerpt Length", "themedy").'</strong></p>';
	echo '<input type="text" size="4" id="themedy_portfolio_excerpt_length" name="themedy_portfolio_excerpt_length" value="'.$portfolio_excerpt_length.'" />';
}

add_action('save_post', 'themedy_save_portfolio_postdata');
function themedy_save_portfolio_postdata( $post_id ) {
	global $post;
	
	if (isset( $_POST['themedy_portfolio_category'] ) ) {
    	update_post_meta( $post_id, 'themedy_portfolio_category', strip_tags( $_POST['themedy_portfolio_category'] ) );
	}
	if (isset( $_POST['themedy_portfolio_amount'] ) ) {
		update_post_meta( $post_id, 'themedy_portfolio_amount', strip_tags( $_POST['themedy_portfolio_amount'] ) );
	}
	if (isset( $_POST['themedy_portfolio_excerpt_length'] ) ) {
		update_post_meta( $post_id, 'themedy_portfolio_excerpt_length', strip_tags( $_POST['themedy_portfolio_excerpt_length'] ) );
    }

}

// Remove post meta for portfolio items
add_action('wp_head','themedy_remove_archive_meta');
function themedy_remove_archive_meta() {
	if (get_post_type() == 'portfolio') {
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	}
}

// Remove "page" from portfolio string to fix conflict with "portfolio" page template
add_filter('request', 'themedy_remove_page_from_portfolio_string');
function themedy_remove_page_from_portfolio_string($query_string)
{
	if (!empty($query_string['post_type']) and $query_string['post_type'] == 'portfolio' and !empty($query_string['name']) and $query_string['name'] == 'page' && isset($query_string['page'])) {
		$post_type = $query_string['post_type'];
		list($delim, $page_index) = explode('/', $query_string['page']);
		$query_string = array();
		$query_string['pagename'] = $post_type;
		$query_string['paged'] = $page_index;
	}
	return $query_string;
}