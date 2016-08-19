<?php

/**
 * Template Name: Menu
 */

// Force layout to full-width-content
add_filter('genesis_pre_get_option_site_layout', 'themedy_portfolio_layout');
function themedy_portfolio_layout($layout) {
    $layout = 'full-width-content';
    return $layout;
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

// Remove title
remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_footer', 'themedy_menu_area', 1);
function themedy_menu_area() {
	if (themedy_get_option('fp_menu') != '') {
		echo '<section class="t-menu section" style="background-image: url(\''.esc_url(themedy_get_option('fp_menu_image')).'\');">';
		echo '<div class="wrap menu-bg fadeInUp animate" data-wow-delay=".5s">';
			echo '<div class="menu-wrap"><div class="menu-wrap-inner">';
			if (themedy_get_option('fp_menu_title') != '') {
				echo '<h2>'.themedy_get_option('fp_menu_title').'</h2>';
			}
			echo '<div class="menu-items">';
			$terms = get_terms( 'themedy_menu_cat');
			$i = 1;
			foreach ($terms as $term) {
				echo '<div class="menu-section '.(($i==1) ? " first" : " last").'">';
				echo '<h4>'.$term->name.'</h4>';

				$menu_items = get_posts( array(
					'posts_per_page'   => 4,
					'post_type' => 'themedy_menu_item',
					'tax_query' => array(
						array(
							'taxonomy' => 'themedy_menu_cat',
							'field' => 'slug',
							'terms' => $term->slug
						)
					)
				) );
				foreach ($menu_items as $item) {
					echo '<div class="themedy-menu-item">';
					$price = get_post_meta($item->ID, 'themedy_menuprice');
					if (!empty($price)) { echo '<span class="price">'.esc_html($price[0]).'</span>'; }
					echo '<h5>'.get_the_title($item->ID).'</h5>';
					echo '<div class="entry">'.$item->post_content.'</div>';
					echo '</div>';
				}

				echo '</div>';
				$i++;
				if ($i==3) { $i=1; }
			}
			echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</section>';
	}
}

genesis();