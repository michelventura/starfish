<?php
/**
 * Template Name: Homepage
 */

// Add homepage body classes
add_filter('body_class', 'add_homepage_class');
function add_homepage_class($classes) {
	$classes[] = 'home-template';
	if (themedy_get_option('fp_featured') != '') {
		$classes[] = 'featured-template';
	}
	return $classes;
}

// Force layout to Full Width
add_filter('genesis_pre_get_option_site_layout', 'themedy_home_layout');
function themedy_home_layout($layout) {
    $layout = 'full-width-no-wrap';
    return $layout;
}

// Hide Headline
remove_action('genesis_entry_header', 'genesis_do_post_title');

// Add the Featured Area
add_action('genesis_header', 'themedy_featured_area', 10);
function themedy_featured_area() {
	if (themedy_get_option('fp_featured') != '') {
		echo '<div class="t-featured">';
		if (themedy_get_option('features_area_enable_widget') != 1) { #default features layout
			query_posts(array('posts_per_page' => apply_filters('themedy_features_amount', 3), 'post_type' => apply_filters('themedy_features_post_type', 'themedy_features')));
			if ( have_posts() ) :
			$i = 1;
			echo '<div class="features">';
			while ( have_posts() ) : the_post();
				global $post;
				echo '<div data-wow-delay=".'.($i*2).'s" class="feature animate fadeInUp feature-'.$i.' one-third'.(($i==1) ? ' first' : '').'">';
				if (has_post_thumbnail( $post->ID )) {
					echo '<div class="feature-image">';
					the_post_thumbnail( 'full' );
					echo '</div>';
					echo '<div class="feature-content">';
				}
				echo '<h4 class="widget-title widgettitle">'.get_the_title($post->ID).'</h4>';
				the_content();
				if (has_post_thumbnail( $post->ID )) {
					echo '</div>';
				}
				echo '</div>';
				$i++;
				if ($i >= 4) { $i = 1; }
			endwhile;
			echo '</div>';
			else: endif; wp_reset_query();
		} else if ( is_active_sidebar('features-widget-area') ) { #alternate widget area features layout
		    echo '<div class="features features-widget-area">';
		    	dynamic_sidebar('features-widget-area');
		    echo '</div>';
		}
		echo '</div>';
		if (!empty(themedy_get_option('features_area_link_text'))) {
			echo '<div class="scroller animate fadeInUp" data-wow-delay=".5s"><a class="continue" href="'.apply_filters('themedy_features_link', '#find-out-more').'">'.esc_html(themedy_get_option('features_area_link_text')).'</a></div>';
		}
	}
}

// Replace the Content Area
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action('genesis_before_footer', 'themedy_content_area', 1);
function themedy_content_area() {
	echo '<div id="find-out-more"></div>';
	if (themedy_get_option('fp_menu') != '') {
		echo '<section class="t-content section"><div class="wrap">';
		echo '<div class="text">';
		genesis_do_loop();
		echo '</div>';
		echo '<a class="foobox image" href="https://youtu.be/0fJ4RFbe_gs" style="background-image:url(\''.esc_url(themedy_get_option('fp_content_image')).'\');"><img src="'.esc_url(themedy_get_option('fp_content_image')).'" alt="" /></a>';
		echo '</div></section>';
	}
}

// Add the Menu Area
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
					'posts_per_page'   => -1,
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
			if (!empty(themedy_get_option('fp_menu_button_link')) and !empty(themedy_get_option('fp_menu_button_text'))) {
				echo '<a class="button" href="'.esc_url(themedy_get_option('fp_menu_button_link')).'">'.esc_html(themedy_get_option('fp_menu_button_text')).'</a>';
			}
			echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</section>';
	}
}

// Add the Upcoming Events Area
add_action('genesis_before_footer', 'themedy_events_area', 1);
function themedy_events_area() {
	if (themedy_get_option('fp_events') != '' and function_exists( 'tribe_get_events' )) {
		echo '<section class="t-events section">';
		echo '<div class="wrap fadeIn animate">';
			if (themedy_get_option('fp_events_title') != '') {
				echo '<h2>'.themedy_get_option('fp_events_title').'</h2>';
			}
			themedy_upcoming_events();
		echo '</div>';
		echo '</section>';
	} elseif (themedy_get_option('fp_events') != '' and !function_exists( 'tribe_get_events' ) and (current_user_can('editor') or current_user_can('administrator'))) {
		echo "<div class=\"section center\">The latest events section requires <em><a target=\"_blank\" href=\"https://wordpress.org/plugins/the-events-calendar/\">The Events Calendar</a></em> plugin.</div>";
	}
}

// Add the Widget Area
add_action('genesis_before_footer', 'themedy_homewidget_area', 1);
function themedy_homewidget_area() {
	dynamic_sidebar('homepage-widget-area');
}

genesis();