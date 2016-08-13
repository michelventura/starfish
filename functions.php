<?php
// Start Genesis and Themedy options
include_once( get_template_directory() . '/lib/init.php' );
include_once(get_stylesheet_directory() . '/lib/init.php');

// Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

// Add Genesis structural wraps
add_theme_support( 'genesis-structural-wraps', array( 'header', 'inner', 'footer' ) );

// Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// Add WP custom background support
add_theme_support( 'custom-background', array(
	'default-color'          => 'f3f3f3',
) );

// Add WP header image support
add_theme_support( 'custom-header', array(
	'default-image'			=> get_stylesheet_directory_uri() . '/images/logo.png',
	'default-text-color'	=> 'fff',
	'flex-width'        	=> true,
	'width'					=> 170,
	'flex-height'       	=> true,
	'height'				=> 184,
	'wp-head-callback'		=> 'header_style',
	'admin-head-callback'	=> 'admin_header_style'
) );

function header_style() {
	$text_color = get_theme_mod( 'header_textcolor', get_theme_support( 'custom-header', 'default-text-color' ) );
	if ( get_header_textcolor() != 'blank' )
		echo "<style type= \"text/css\">.title-area .site-title a { color: #$text_color }</style>\n";
}

function admin_header_style() {
	echo '<style type="text/css"> #headimg { width: '.get_custom_header()->width.'px; height: '.get_custom_header()->height.'px; background-repeat: no-repeat; } #headimg h1 { margin: 0; } #headimg h1 a { text-decoration: none; display: block; padding: 0.5em 0; background: #fff; } #headimg #desc { background: #fff; height: '.get_custom_header()->height.'px; }</style>';
}

// Header image output control
if ( get_header_textcolor() == 'blank' ) {
	remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
	remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
	add_action( 'genesis_site_title', 'custom_site_title' );
	function custom_site_title() {
		$header_image = esc_url(get_header_image());
		if (!empty($header_image))
			echo
			"<h1 class=\"site-title logo\">",
				"<a href=\"", esc_url(home_url()), "\">",
					"<img width=\"", get_custom_header()->width, "\" height=\"", get_custom_header()->height, "\" src=\"", $header_image, "\" alt=\"", get_bloginfo('title'), "\" />",
				"</a>",
			"</h1>";
	}
}

// Add Image sizes
add_image_size('post-image', 756, 300, TRUE);

// Enqueue external scripts and styles
add_action('wp_enqueue_scripts', 'themedy_enqueue', 1);
function themedy_enqueue() {

	// Custom CSS Legacy
	if (is_file(CHILD_DIR.'/custom/custom.css')) {
		wp_enqueue_style('themedy-child-theme-custom-style', CHILD_URL.'/custom/custom.css',CHILD_THEME_NAME,CHILD_THEME_VERSION);
	}

	// Mobile Menu
	if (themedy_get_option('mobile_menu')) {
		wp_enqueue_style('mmenu', CHILD_THEME_LIB_URL.'/css/jquery.mmenu.css','','5.5.3');
		wp_enqueue_script('mmenu', CHILD_THEME_LIB_URL.'/js/jquery.mmenu.min.js', array('jquery'), '5.5.3', TRUE);
	}

	// Animate CSS
	if (themedy_get_option('animations')) {
		wp_enqueue_style('animate', CHILD_THEME_LIB_URL.'/css/animate.min.css');
		wp_enqueue_script('wow', CHILD_THEME_LIB_URL.'/js/wow.min.js', array('jquery'), '1.1.2', TRUE);
	}

	// Smooth Scroll
	wp_enqueue_script('smooth-scroll', CHILD_THEME_LIB_URL.'/js/smooth-scroll.min.js', array('jquery'), '7.1.1', TRUE);

	// Core Script
	wp_enqueue_script('starfish', CHILD_THEME_LIB_URL.'/js/starfish.js', array('jquery'), CHILD_THEME_VERSION, TRUE);
}

// Mobile body class
add_filter('body_class', 'themedy_body_class');
function themedy_body_class($class) {
	if (themedy_get_option('mobile_menu')) {
		$class[] = 'mobile-enabled';
	}
	if (themedy_get_option('animations')) {
		$class[] = 'animate-enabled';
	}
	return $class;
}

// Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'themedy_google_fonts' );
function themedy_google_fonts() {
	wp_enqueue_style( 'google-fonts_pathwaygothicone', '//fonts.googleapis.com/css?family=Pathway+Gothic+One', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts_nunito', '//fonts.googleapis.com/css?family=Nunito:400,700,300', array(), CHILD_THEME_VERSION );
}

// Remove Desc
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Add the Navs In Header
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_site_title', 'genesis_do_nav', 1);
remove_action('genesis_after_header', 'genesis_do_subnav');
add_action('genesis_site_title', 'genesis_do_subnav', 25);

// Add Mobile Menu Toggle
add_action('genesis_header', 'themedy_nav_menu_toggle', 5);
function themedy_nav_menu_toggle() {
	if (themedy_get_option('mobile_menu')) {
		echo '<div class="toggle-menu"><i class="navicon"></i><span class="screen-reader-text">'.__('Toggle Mobile Menu', 'themedy').'</span></div>';
	}
}

// WP Head CSS
add_action('wp_head', 'themedy_head_css', 20);
function themedy_head_css() {
	global $post;
	if (has_post_thumbnail()) $imgurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	if (!empty($imgurl) and (is_page() or is_singular('post')))  {
		echo '<style type="text/css">.site-header { background-image: url("'.$imgurl.'");</style>';
	} else if (themedy_get_option('header_bg') != '') {
		echo '<style type="text/css">.site-header { background-image: url("'.esc_url(themedy_get_option('header_bg')).'");</style>';
	}

	$color1 = themedy_get_option('color1');
	$color2 = themedy_get_option('color2');
	$color3 = themedy_get_option('color3');

	list($r, $g, $b) = sscanf($color1, "#%02x%02x%02x");
	$color1_rgb = "$r,$g,$b";

	if ($color1 != '#da855c') {
		echo "<style type=\"text/css\">.menu-items .price{color:$color1}.menu-items h4{border-color:rgba($color1_rgb,.3)}</style>\n";
	}
	if ($color2 != '#da855c') {
		echo "<style type=\"text/css\">a,.nav-primary .genesis-nav-menu a:hover,.nav-primary .genesis-nav-menu .current-menu-item > a,.nav-secondary .genesis-nav-menu a:hover,.nav-secondary .genesis-nav-menu .current-menu-item > a{color:$color2;}</style>\n";
	}
	if ($color3 != '#201e1e') {
		echo "<style type=\"text/css\">.site-header,.site-footer{background-color:$color3;}</style>\n";
	}
}

// Add no script tag in header to make JS-required animations visible
add_action('genesis_meta', 'themedy_no_script');
function themedy_no_script() {
	echo '<noscript><style type="text/css">.animate {visibility:visible!important;}</style></noscript>';
}

// Remove uneeded widget areas
unregister_sidebar('header-right');
unregister_sidebar('sidebar-alt');

// Remove some layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Hide footer entry meta on blog pages
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Add custom post class to posts
add_filter('post_class', 'themedy_custom_post_class');
function themedy_custom_post_class($classes) {
	if (get_post_type() != 'tribe_events') {
		$classes[] .= esc_attr(sanitize_html_class('animate'));
		$classes[] .= esc_attr(sanitize_html_class('fadeIn'));
	}
	return $classes;
}

// Set default genesis layout
genesis_set_default_layout('full-width-content');

// Change comment avatar size
add_filter( 'genesis_comment_list_args', 'themedy_comment_list_args' );
function themedy_comment_list_args( $args ) {
    $args['avatar_size'] = 32;
	return $args;
}

// Add animation to the site-header
add_filter( 'genesis_attr_title-area', 'themedy_add_title_animation' );
function themedy_add_title_animation( $attributes ){
    $attributes['class'] .= ' animate fadeInDown';
	return $attributes;
}

// Add the feature boxes "above footer" area
add_action('genesis_before_footer', 'themedy_feature_boxes');
function themedy_feature_boxes() {
	if (themedy_get_option('f_box1') == 1 or themedy_get_option('f_box2') == 1) {
		echo '<section class="feature-boxes">';
			?>
			<?php if (themedy_get_option('f_box1') == 1) { ?>
            <?php echo '<a class="featured-box-link" href="'.esc_url(themedy_get_option('f_box1_link')).'">'; ?>
            <div class="feature-box-1 fadeIn animate feature-box<?php if (themedy_get_option('f_box2') != 1) echo ' full-width' ?>"<?php if (themedy_get_option('f_box1_image') != '') echo ' style="background-image: url('.esc_url(themedy_get_option('f_box1_image')).');"'; ?>>
            	<div class="text">
                <?php
				echo (themedy_get_option('f_box1_icon') != '') ? '<img class="icon" src="'.esc_url(themedy_get_option('f_box1_icon')).'" alt="" />' : '';
                echo (themedy_get_option('f_box1_sub') != '') ? '<h4>'.esc_attr(themedy_get_option('f_box1_sub')).'</h4>' : '';
                echo (themedy_get_option('f_box1_title') != '') ? '<h3>'.esc_attr(themedy_get_option('f_box1_title')).'</h3>' : '';
                ?>
                </div>
                <div class="fade"></div>
            </div>
            <?php echo '</a>'; ?>
            <?php } ?>

            <?php if (themedy_get_option('f_box2') == 1) { ?>
            <?php echo '<a class="featured-box-link" href="'.esc_url(themedy_get_option('f_box2_link')).'">'; ?>
            <div class="feature-box-2 fadeIn animate feature-box<?php if (themedy_get_option('f_box1') != 1) echo ' full-width' ?>"<?php if (themedy_get_option('f_box2_image') != '') echo ' style="background-image: url('.esc_url(themedy_get_option('f_box2_image')).');"'; ?>>
            	<div class="text">
                <?php
				echo (themedy_get_option('f_box2_icon') != '') ? '<img class="icon" src="'.esc_url(themedy_get_option('f_box2_icon')).'" alt="" />' : '';
                echo (themedy_get_option('f_box2_sub') != '') ? '<h4>'.esc_attr(themedy_get_option('f_box2_sub')).'</h4>' : '';
                echo (themedy_get_option('f_box2_title') != '') ? '<h3>'.esc_attr(themedy_get_option('f_box2_title')).'</h3>' : '';
                ?>
                </div>
                <div class="fade"></div>
            </div>
            <?php echo '</a>'; ?>
            <?php } ?>
			<?php
		echo '</section>';
	}
}

// Move footer widgets in footer container and add logo block
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_footer', 'themedy_footer_widget_areas', 6 );
function themedy_footer_widget_areas() {
	echo '<div class="footer-widgets-wrap">';
	echo '<div class="footer-logo">';

		$header_image = esc_url(get_header_image());
		if (!empty($header_image))
			echo "<a href=\"", esc_url(home_url()), "\">",
					"<img width=\"", get_custom_header()->width, "\" height=\"", get_custom_header()->height, "\" src=\"", $header_image, "\" alt=\"", get_bloginfo('title'), "\" />",
				"</a>";

	echo '</div>';
	genesis_footer_widget_areas();
	echo '</div>';
}

// Custom footer markup
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
add_action( 'genesis_footer', 'themedy_footer_markup_open', 5 );
function themedy_footer_markup_open() {
	echo '<div class="site-footer"><div class="wrap fadeIn animate">';
}
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
add_action( 'genesis_footer', 'themedy_footer_markup_close', 15 );
function themedy_footer_markup_close() {
	echo '</div></div>';
}

// Customize footer text (set in Themedy options)
if (themedy_get_option('footer')) {
	add_filter('genesis_footer_creds_text', 'custom_footer_creds_text');
	function custom_footer_creds_text() {
    	return do_shortcode(themedy_get_option('footer_text'));
	}
}

// Register Sidebars
if (themedy_get_option('features_area_enable_widget') == 1) {
	genesis_register_sidebar(array(
		'name' => __('Homepage: Features Area', 'themedy'),
		'id' => 'features-widget-area',
		'description' => __('This is the replacement widget area where the features area would of been on the homepage.', 'themedy'),
		'before_widget' =>  '<div id="%1$s" class="widget %2$s">',
		'after_widget' =>  '</div>'
	));
}

genesis_register_sidebar(array(
	'name' => __('Homepage: Bottom Area', 'themedy'),
	'id' => 'homepage-widget-area',
	'description' => __('This is the widget area at the bottom of the homepage if you need more sections.', 'themedy'),
	'before_widget' =>  '<section id="%1$s" class="section widget-section %2$s"><div class="wrap fadeIn animate">',
	'after_widget' =>  '</div></section>',
	'before_title' => '<h2>',
	'after_title' => '</h2>'
));

// Events List
function themedy_upcoming_events() {
	$meta_date = array(
		array(
			'key' => '_EventEndDate',
			'value' => date( 'Y-m-d' ),
			'compare' => '>=',
			'type' => 'DATETIME'
		)
	);
	$posts = get_posts( array(
		'post_type' => 'tribe_events',
		'posts_per_page' => (themedy_get_option('fp_events_limit') != '') ? esc_attr(themedy_get_option('fp_events_limit')) : '5',
		'meta_key' => '_EventEndDate',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array( $meta_date )
	) );
	$output = '';
	if ($posts) {
		$output .= '<ul class="themedy-event-list">';
		foreach( $posts as $post ) {
			$output .= '<li class="event">';
			$output .= get_the_post_thumbnail($post->ID, 'thumbnail' );
			$output .= '<h4 class="entry-title summary"><a href="' . tribe_get_event_link($post->ID) . '" rel="bookmark">' . apply_filters( 'themedy_event_list_title', get_the_title($post->ID) ) . '</a></h4>';
			$output .= '<div class="entry-meta"><span class="duration time">' . apply_filters( 'themedy_event_list_details', tribe_events_event_schedule_details($post->ID) ) . '</span>';
			if (tribe_get_venue($post->ID) != '') {
				$output .= '<span class="duration venue">' . apply_filters( 'themedy_event_list_venue', tribe_get_venue($post->ID) ) . '</span>';
			}
			$output .= '</div></li>';
		}
		$output .= '</ul>';
		$output .= '<a class="button all-events" href="' . apply_filters( 'themedy_event_list_viewall_link', tribe_get_events_link() ) .'" rel="bookmark">' . translate( 'View All Events', 'tribe-events-calendar' ) . '</a>';
	} else {
		echo '<p>'.__('There are no upcoming events at this time.', 'tribe-events-calendar').'</p>';
	}
	echo $output;
	wp_reset_query();
}

// Add a custom layout and remove sidebar from it
add_action( 'init', 'themedy_create_nowrap_layout' );
function themedy_create_nowrap_layout() {
	 genesis_register_layout( 'full-width-no-wrap', array(
		'label' => __('Full Width (No Wrap)', 'genesis'),
		'img' => get_stylesheet_directory_uri() . '/images/layout-nowrap.gif'
	) );
}
add_action( 'wp_head', 'themedy_setup_nowrap_layout' );
function themedy_setup_nowrap_layout() {
	$site_layout = genesis_site_layout();
	if ( $site_layout == 'full-width-no-wrap' ) {
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
	}
}

if (is_file(CHILD_DIR.'/custom/custom_functions.php')) { include(CHILD_DIR.'/custom/custom_functions.php'); } // Include Custom Functions