<?php
/*----------------------------------------------------------

Description: 	WooCommerce functionality for Themedy themes
				Get the WooCommerce plugin for free at http://wordpress.org/plugins/woocommerce/

----------------------------------------------------------*/

add_theme_support( 'woocommerce' );

// Force layout to Full Width
add_filter('genesis_pre_get_option_site_layout', 'themedy_woo_layout');
function themedy_woo_layout($layout) {
	if (themedy_active_plugin() == 'woocommerce' and is_woocommerce()) {
    	$layout = 'full-width-content';
	}
    return $layout;
}

// Check For Shop Plugins
function themedy_active_plugin(){

	$active_plugins = get_option('active_plugins');
	$plugin_name = '';
	if ( class_exists( 'woocommerce' )) { $plugin_name = 'woocommerce'; }

	return ( $plugin_name <> '' ) ? $plugin_name : false;
}
global $themedy_active_plugin_name;
$themedy_active_plugin_name = themedy_active_plugin();

// Add sidebars for woocommerce templates
add_action('wp_head', 'woocommerce_sidebars');
function woocommerce_sidebars() {
	if (themedy_active_plugin() == 'woocommerce' and is_woocommerce()) {
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
	}
}

// Change number of products per row in loop
add_filter('loop_shop_columns', 'themedy_woo_loop_columns');
if ( ! function_exists( 'themedy_woo_loop_columns' ) ) { 
	function themedy_woo_loop_columns() {
		return 3;
	}
}

// Add body class for woo styles to work
if ( ! class_exists( 'WC_pac' ) and themedy_active_plugin() == 'woocommerce') { /* WooCommerce Archive Customizer workaround */
	add_action( 'wp', 'woocommerce_pac_columns', 1 );
	function woocommerce_pac_columns() {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			add_filter( 'body_class', 'themedy_wc_products_class' );
			function themedy_wc_products_class($classes) {
				$classes[] 	= 'columns-3';
				return $classes;
			}
		}
	}
}

// Change amount of products shown on shop page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 9;' ), 20 );