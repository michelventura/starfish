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

// Add Google Tag Manager code immediately below opening body tag
/*
add_action( 'genesis_before', 'mv_google_tag_manager' );
function mv_google_tag_manager() { ?>
	<!-- Google Tag Manager -->
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WCWDTB"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-WCWDTB');</script>
<!-- End Google Tag Manager -->
<?php }
*/
