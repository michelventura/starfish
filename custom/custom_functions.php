<?php

/* ---:[ place your custom code below this line ]:--- */

//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

//* Allow shortcodes to execute in widget areas
add_filter('widget_text', 'do_shortcode');

//* Customize the footer credits
add_filter( 'genesis_footer_creds_text', 'my_custom_footer_creds' );
function my_custom_footer_creds(){

    $creds = '[footer_copyright] <a href="http://starfish.dev/">Starfish VIP</a>';
    return $creds;

}
