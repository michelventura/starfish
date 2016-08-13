<?php

/**
 * This function registers the default values for Themedy theme settings
 */

function themedy_theme_settings_defaults() {
	$defaults = array( // define our defaults
		'color1' => '#ca8f6b',
		'color2' => '#da855c',
		'color3' => '#201e1e',
		'style' => 'default',
		'header_bg' => get_stylesheet_directory_uri() . '/images/bg-headermain.jpg',
		'f_box1' => false,
		'f_box1_icon' => get_stylesheet_directory_uri() . '/images/icon-fbox1.png',
		'f_box1_sub' => '',
		'f_box1_title' => '',
		'f_box1_link' => '',
		'f_box1_image' => get_stylesheet_directory_uri() . '/images/bg-fbox1.jpg',
		'f_box2' => false,
		'f_box2_icon' => get_stylesheet_directory_uri() . '/images/icon-fbox2.png',
		'f_box2_sub' => '',
		'f_box2_title' => '',
		'f_box2_link' => '',
		'f_box2_image' => get_stylesheet_directory_uri() . '/images/bg-fbox2.jpg',
		'fp_featured' => 1,
		'features_area_link_text' => 'Find out what we\'re all about',
		'features_area_enable_widget' => false,
		'fp_content_image' => get_stylesheet_directory_uri() . '/images/bg-homecontent.jpg',
		'fp_menu' => 1,
		'fp_menu_title' => 'Today\'s Menu',
		'fp_menu_image' => get_stylesheet_directory_uri() . '/images/bg-homemenu.jpg',
		'fp_menu_button_link' => '',
		'fp_menu_button_text' => 'View Full Menu',
		'fp_events' => 1,
		'fp_events_title' => 'Upcoming Events',
		'fp_events_limit' => '5',
		'features_area_enable' => 1,
		'num_features' => 3,
		'features_area_enable_widget' => 0,
		'mobile_menu' => 1,
		'featured_area_title'=> 'Coming <strong>soon</strong>.',
		'featured_area_text'=> '',
		'button_text' => 'Notify Me',
		'button_link' => '#footer',
		'animations' => 1,
		'footer' => 1,
		'footer_text' => '&copy;'.date('Y') . ' <a href="'. home_url() .'">' . get_bloginfo('name') . '</a> &mdash;',
	);

	return apply_filters('themedy_theme_settings_defaults', $defaults);
}


/**
 * Easy access to our available theme styles
 */

function themedy_styles() {
	return array('default' => __("Default", 'themedy'));
}

/**
 * Image upload script
 */

add_action('admin_init', 'themedy_admin_footer_scripts');
function themedy_admin_footer_scripts() {

	add_action('admin_enqueue_scripts', 'themedy_admin_admin_scripts');
	function themedy_admin_admin_scripts() {
		$screen = get_current_screen();
		if ( $screen->id == 'appearance_page_themedy')
			wp_enqueue_media();
	}

	add_action('admin_footer', 'footer_scripts');
	function footer_scripts() {
		$screen = get_current_screen();
			if ( $screen->id != 'appearance_page_themedy') return;
		?>
        <script type='text/javascript'>
			jQuery(document).ready( function(){
			 function media_upload( button_class) {
				var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;
				jQuery('body').on('click',button_class, function(e) {
					e.preventDefault();
					var button_id ='#'+jQuery(this).attr('id');
					var self = jQuery(button_id);
					var send_attachment_bkp = wp.media.editor.send.attachment;
					var button = jQuery(button_id);
					var id = '#'+button.attr('id').replace('_button', ''); // Our URL field ID
					_custom_media = true;
					wp.media.editor.send.attachment = function(props, attachment){
						if ( _custom_media  ) {
							console.log(jQuery(id));
						   jQuery(id).val(attachment.url);
						} else {
							console.log(attachment);
							return _orig_send_attachment.apply( id, [props, attachment] );
						}
					}

					wp.media.editor.open(button);
					return false;
				});
			}
			media_upload( '.custom_media_upload');
			});
		</script>
        <?php
	}
}

/**
 * Extra picker stuff
 */

add_action('admin_menu', 'themedy_footer_script');
function themedy_footer_script() {
	global $_themedy_theme_settings_pagehook;
	add_action('load-'.$_themedy_theme_settings_pagehook, 'themedy_enqueue_picker');
	add_action( 'admin_footer-'.$_themedy_theme_settings_pagehook, 'themedy_picker_options' );
}
function themedy_enqueue_picker( $hook_suffix ) {
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
function themedy_picker_options() {
	echo '<script type="text/javascript">jQuery(document).ready(function($){$(".themedy-date").datepicker({dateFormat : "M dd, yy"});});</script>';
	echo '<script type="text/javascript">jQuery(document).ready(function($){$(".themedy-color").wpColorPicker();});</script>';
}

/**
 * Add our meta boxes
 */

function themedy_theme_settings_boxes() {
	global $_themedy_theme_settings_pagehook;

	if (function_exists('add_screen_option')) { add_screen_option('layout_columns', array('max' => 2, 'default' => 2) ); }

	add_meta_box('themedy-theme-settings-version', __('Information', 'themedy'), 'themedy_theme_settings_info_box', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-appearance', __('Appearance', 'themedy'), 'themedy_theme_settings_appearance', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy-theme-settings-general', __('General Options', 'themedy'), 'themedy_theme_settings_general', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-homepage', __('Homepage Options', 'themedy'), 'themedy_theme_settings_homepage', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy_theme_settings_footer', __('Footer', 'themedy'), 'themedy_theme_settings_footer', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy-theme-settings-version', __('Information', 'themedy'), 'themedy_theme_settings_info_box', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-f-box1', __('Footer Box Feature One', 'themedy'), 'themedy_theme_settings_f_box1', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy-theme-settings-f-box2', __('Footer Box Feature Two', 'themedy'), 'themedy_theme_settings_f_box2', $_themedy_theme_settings_pagehook, 'side');

}

/**
 * This next section defines functions that contain the content of the meta boxes
 */

function themedy_theme_settings_info_box() { ?>
	<p><strong><?php echo CHILD_THEME_NAME; ?></strong> by <a href="http://themedy.com">Themedy.com</a></p>
	<p><strong><?php _e('Version:', 'themedy'); ?></strong> <?php echo CHILD_THEME_VERSION; ?></p>
    <p><span class="description"><?php _e('For support, please visit <a href="http://themedy.com/forum/">http://themedy.com/forum/</a>', 'themedy'); ?></span></p>

<?php
}

function themedy_theme_settings_appearance() { ?>
	<p><?php _e("Accent Color:", 'themedy'); ?><br />
	<input type="text" value="<?php echo themedy_get_option('color1'); ?>" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[color1]" data-default-color="#da855c" class="themedy-color" /></p>
    <p><?php _e("Link Color:", 'themedy'); ?><br />
	<input type="text" value="<?php echo themedy_get_option('color2'); ?>" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[color2]" data-default-color="#da855c" class="themedy-color" /></p>
    <p><?php _e("Header/Footer Background Color:", 'themedy'); ?><br />
	<input type="text" value="<?php echo themedy_get_option('color3'); ?>" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[color3]" data-default-color="#201e1e" class="themedy-color" /></p>
    <p><?php _e("Main Header Background <small>(can be overwritten by page featured image)</small>:", 'themedy'); ?><br />
    <input type="url" id="header_bg" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[header_bg]" value="<?php echo esc_attr( themedy_get_option('header_bg') ); ?>" size="47" />
    <input type="button" id="header_bg_button" class="button custom_media_upload" value="<?php _e("Upload Image", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    </p>
    <hr class="div" />
    <p><?php _e('You can change the header image <a href="themes.php?page=custom-header/">by clicking here</a>.', 'themedy'); ?></p>
<?php
}

function themedy_theme_settings_general() { ?>
   <p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[mobile_menu]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[mobile_menu]" value="1" <?php checked(1, themedy_get_option('mobile_menu')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[mobile_menu]"><?php _e("Use the <strong>jQuery Mobile Menu</strong>?", 'themedy'); ?></label></p>
    <p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[animations]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[animations]" value="1" <?php checked(1, themedy_get_option('animations')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[animations]"><?php _e("Enable <strong>CSS animations</strong> across site?", 'themedy'); ?></label></p>
<?php
}

function themedy_theme_settings_homepage() { ?>
    <hr class="div" />
    <p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_featured]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_featured]" value="1" <?php checked(1, themedy_get_option('fp_featured')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_featured]"><?php _e("Show the <strong>Featured Header</strong> section?", 'themedy'); ?></label></p>
    <p><?php _e("Scroll Down Link Text", 'themedy'); ?>:<br />
    <input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[features_area_link_text]" value="<?php echo esc_attr( themedy_get_option('features_area_link_text') ); ?>" size="40" /></p>
    <p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[features_area_enable_widget]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[features_area_enable_widget]" value="1" <?php checked(1, themedy_get_option('features_area_enable_widget')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[features_area_enable_widget]"><?php _e("Turn this section into a widget area?", 'themedy'); ?></label></p>
    <hr class="div" />
    <p><?php _e("Content Box Image", 'themedy'); ?>:<br />
    <input type="url" id="fp_content_image" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_content_image]" value="<?php echo esc_attr( themedy_get_option('fp_content_image') ); ?>" size="47" />
    <input type="button" id="fp_content_image_button" class="button custom_media_upload" value="<?php _e("Upload Image", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    </p>
    <hr class="div" />
    <p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu]" value="1" <?php checked(1, themedy_get_option('fp_menu')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu]"><?php _e("Show the <strong>Menu</strong> section?", 'themedy'); ?></label></p>
    <p><?php _e("Menu Section Title", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu_title]" value="<?php echo esc_attr( themedy_get_option('fp_menu_title') ); ?>" size="40" /></p>
    <p><?php _e("Menu Section Background", 'themedy'); ?>:<br />
    <input type="url" id="fp_menu_image" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu_image]" value="<?php echo esc_attr( themedy_get_option('fp_menu_image') ); ?>" size="47" />
    <input type="button" id="fp_menu_image_button" class="button custom_media_upload" value="<?php _e("Upload Image", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    <p><?php _e("Menu Section Button Text", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu_button_text]" value="<?php echo esc_attr( themedy_get_option('fp_menu_button_text') ); ?>" size="40" /></p>
    <p><?php _e("Menu Section Button Link", 'themedy'); ?>:<br />
	<input placeholder="Full URL including http://" type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_menu_button_link]" value="<?php echo esc_attr( themedy_get_option('fp_menu_button_link') ); ?>" size="40" /></p>
    </p>
    <hr class="div" />
    <p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_events]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_events]" value="1" <?php checked(1, themedy_get_option('fp_events')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_events]"><?php _e("Show the <strong>Upcoming Events</strong> section? <small>(requires <em><a target=\"_blank\" href=\"https://wordpress.org/plugins/the-events-calendar/\">The Events Calendar</a></em>)</small>", 'themedy'); ?></label></p>
    <p><?php _e("Upcoming Events Section Title", 'themedy'); ?>:<br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_events_title]" value="<?php echo esc_attr( themedy_get_option('fp_events_title') ); ?>" size="40" /></p>
    <p><?php _e("Amount of Events to Display", 'themedy'); ?>:<br/>
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[fp_events_limit]" value="<?php echo esc_attr( themedy_get_option('fp_events_limit') ); ?>" size="5" /></p>
<?php
}

function themedy_theme_settings_f_box1() { ?>
	<p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1]" value="1" <?php checked(1, themedy_get_option('f_box1')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1]"><?php _e("Show featured box one?", 'themedy'); ?></label>
    </p>
    <p><?php _e("Icon Image:", 'themedy'); ?><br />
    <input type="url" id="f_box1_icon" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1_icon]" value="<?php echo esc_attr( themedy_get_option('f_box1_icon') ); ?>" size="47" />
    <input type="button" id="f_box1_icon_button" class="button custom_media_upload" value="<?php _e("Upload Icon", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    </p>
    <p><?php _e("Subtitle:", 'themedy'); ?><br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1_sub]" value="<?php echo esc_attr( themedy_get_option('f_box1_sub') ); ?>" size="47" /></p>
    <p><?php _e("Title:", 'themedy'); ?><br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1_title]" value="<?php echo esc_attr( themedy_get_option('f_box1_title') ); ?>" size="47" /></p>
    <p><?php _e("Feature Link (URL):", 'themedy'); ?><br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1_link]" value="<?php echo esc_attr( themedy_get_option('f_box1_link') ); ?>" size="47" /></p>
    <p><?php _e("Background Image:", 'themedy'); ?><br />
    <input type="url" id="f_box1_image" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box1_image]" value="<?php echo esc_attr( themedy_get_option('f_box1_image') ); ?>" size="47" />
    <input type="button" id="f_box1_image_button" class="button custom_media_upload" value="<?php _e("Upload Image", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    </p>
<?php
}

function themedy_theme_settings_f_box2() { ?>
	<p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2]" value="1" <?php checked(1, themedy_get_option('f_box2')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2]"><?php _e("Show featured box two?", 'themedy'); ?></label>
	</p>
    <p><?php _e("Icon Image:", 'themedy'); ?><br />
    <input type="url" id="f_box2_icon" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2_icon]" value="<?php echo esc_attr( themedy_get_option('f_box2_icon') ); ?>" size="47" />
    <input type="button" id="f_box2_icon_button" class="button custom_media_upload" value="<?php _e("Upload Icon", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    </p>
    <p><?php _e("Subtitle:", 'themedy'); ?><br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2_sub]" value="<?php echo esc_attr( themedy_get_option('f_box2_sub') ); ?>" size="47" /></p>
    <p><?php _e("Title:", 'themedy'); ?><br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2_title]" value="<?php echo esc_attr( themedy_get_option('f_box2_title') ); ?>" size="47" /></p>
    <p><?php _e("Feature Link (URL):", 'themedy'); ?><br />
	<input type="text" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2_link]" value="<?php echo esc_attr( themedy_get_option('f_box2_link') ); ?>" size="47" /></p>
    <p><?php _e("Background Image:", 'themedy'); ?><br />
    <input type="url" id="f_box2_image" class="custom_media_url" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[f_box2_image]" value="<?php echo esc_attr( themedy_get_option('f_box2_image') ); ?>" size="47" />
    <input type="button" id="f_box2_image_button" class="button custom_media_upload" value="<?php _e("Upload Image", 'themedy'); ?>" />
    <label><span class="description"><?php _e("Enter URL or upload an image", 'themedy'); ?></span></label>
    </p>
<?php
}

function themedy_theme_settings_footer() { ?>
	<p><input type="checkbox" name="<?php echo STARFISH_SETTINGS_FIELD; ?>[footer]" id="<?php echo STARFISH_SETTINGS_FIELD; ?>[footer]" value="1" <?php checked(1, themedy_get_option('footer')); ?> /> <label for="<?php echo STARFISH_SETTINGS_FIELD; ?>[footer]"><?php _e("Use custom footer text?", 'themedy'); ?></label>
	</p>
	<p><?php _e('Footer text', 'themedy'); ?>:<br />
	<textarea name="<?php echo STARFISH_SETTINGS_FIELD; ?>[footer_text]" rows="5" cols="42"><?php echo htmlspecialchars( themedy_get_option('footer_text') ); ?></textarea></p>

<?php
}