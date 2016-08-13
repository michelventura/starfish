<?php

// Add Page Tag Meta Box
add_action('add_meta_boxes', 'themedy_add_menuprice_meta_box');
function themedy_add_menuprice_meta_box() {
	$screens = array( 'themedy_menu_item' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'themedy_menuprice_meta_box',
			__( 'Themedy Options', 'themedy' ),
			'themedy_menuprice_meta_box',
			$screen, 'side' 
		);
	}
}

function themedy_menuprice_meta_box( $post ) { 
	$themedy_menuprice = get_post_meta($post->ID, 'themedy_menuprice', true);
	echo '<p><strong>'.__("Menu Item Price", "themedy").'</strong></p>';
	echo '<input type="text" rows="1" class="large-text" id="themedy_menuprice" name="themedy_menuprice" value="' . esc_textarea($themedy_menuprice) . '" />';

	wp_nonce_field( 'menuprice-nonce', 'menuprice_name', false );
}

add_action('save_post', 'themedy_save_menuprice_postdata');
function themedy_save_menuprice_postdata( $post_id ) {
    // make sure we're on a supported post type
    if ( empty($_POST['post_type']) or $_POST['post_type'] != 'themedy_menu_item') return;  

    // verify this came from our screen and with proper authorization.
    if ( !wp_verify_nonce( $_POST['menuprice_name'], 'menuprice-nonce' )) return;

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

    // Check permissions
    if ( 'page' == $_POST['post_type'] || $_POST['post_type'] == 'portfolio') {
        if ( !current_user_can( 'edit_page', $post_id ) ) return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) ) return;
    }

    // OK, we're authenticated: we need to find and save the data
    if ( isset( $_POST["themedy_menuprice"] ) ) update_post_meta( $post_id, "themedy_menuprice", wp_kses_post( $_POST["themedy_menuprice"], "<strong><em>" ) );
}