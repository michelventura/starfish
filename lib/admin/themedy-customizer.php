<?php
/**
 *
 * Appearance > Customizer
 * * #TODO 	- Code this cleanier.
 			- Add homepage stuff
 */

class Themedy_Customizer extends Genesis_Customizer_Base {

	/**
	 * Settings field.
	 */
	public $settings_field = CHILD_THEME_SETTINGS;

	/**
	 *
	 */
	public function register( $wp_customize ) {

		$this->styles( $wp_customize );

	}

	private function styles( $wp_customize ) {

		$wp_customize->add_setting(
			$this->get_field_name( 'color1' ),
			array(
				'default' => '#da855c',
				'type'    => 'option'
			)
		);
		
		$wp_customize->add_setting(
			$this->get_field_name( 'color2' ),
			array(
				'default' => '#da855c',
				'type'    => 'option'
			)
		);
		
		$wp_customize->add_setting(
			$this->get_field_name( 'color3' ),
			array(
				'default' => '#201e1e',
				'type'    => 'option'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'themedy_style1',
				array(
					'label'      => __( 'Accent Color', 'themedy' ),
					'section'    => 'colors',
					'settings'   => $this->get_field_name( 'color1' ),
				)
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'themedy_style2',
				array(
					'label'      => __( 'Link Color', 'themedy' ),
					'section'    => 'colors',
					'settings'   => $this->get_field_name( 'color2' ),
				)
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'themedy_style3',
				array(
					'label'      => __( 'Header/Footer BG Color', 'themedy' ),
					'section'    => 'colors',
					'settings'   => $this->get_field_name( 'color3' ),
				)
			)
		);

	}

}

add_action( 'init', 'themedy_customizer_init' );
/**
 *
 */
function themedy_customizer_init() {
	new Themedy_Customizer;
}
