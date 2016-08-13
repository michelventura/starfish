<?php
/**
 * The functions in this file act as shortcuts for
 * accessing Themedy-specific Settings that have been
 * stored in the options table and as post meta data.
 */

/**
 * These functions pull options/settings
 * from the options database.
 *
 **/
function themedy_get_option($key, $setting = null, $use_cache = true ) {

	// get setting
	$setting = $setting ? $setting : STARFISH_SETTINGS_FIELD;

	// allow child theme to short-circuit this function
	$pre = apply_filters('themedy_pre_get_option_'.$key, false, $setting);
	if ( false !== $pre )
		return $pre;

	//* Bypass cache if viewing site in customizer
	if ( genesis_is_customizer() ) {
		$use_cache = false;
	}

	//* If we need to bypass the cache
	if ( ! $use_cache ) {
		$options = get_option( $setting );

		if ( ! is_array( $options ) || ! array_key_exists( $key, $options ) )
			return '';

		return is_array( $options[$key] ) ? stripslashes_deep( $options[$key] ) : stripslashes( wp_kses_decode_entities( $options[$key] ) );
	}

	//* Setup caches
	static $settings_cache = array();
	static $options_cache  = array();

	//* Check options cache
	if ( isset( $options_cache[$setting][$key] ) )
		//* Option has been cached
		return $options_cache[$setting][$key];

	//* Check settings cache
	if ( isset( $settings_cache[$setting] ) )
		//* Setting has been cached
		$options = apply_filters( 'themedy_options', $settings_cache[$setting], $setting );
	else
		//* Set value and cache setting
		$options = $settings_cache[$setting] = apply_filters( 'themedy_options', get_option( $setting ), $setting );

	//* Check for non-existent option
	if ( ! is_array( $options ) || ! array_key_exists( $key, (array) $options ) )
		//* Cache non-existent option
		$options_cache[$setting][$key] = '';
	else
		//* Option has not been previously been cached, so cache now
		$options_cache[$setting][$key] = is_array( $options[$key] ) ? stripslashes_deep( $options[$key] ) : stripslashes( wp_kses_decode_entities( $options[$key] ) );

	return $options_cache[$setting][$key];

}
function themedy_option($key, $setting = null) {
	echo themedy_get_option($key, $setting);
}
