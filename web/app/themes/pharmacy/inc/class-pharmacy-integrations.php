<?php
/**
 * Pharmacy_Integrations Class
 * Provides integrations with Storefront extensions by removing/changing incompatible controls/settings. Also adjusts default values
 * if they need to differ from the original setting.
 *
 * @author   WooThemes
 * @since    1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Pharmacy_Integrations' ) ) {

class Pharmacy_Integrations {

	/**
	 * Setup class.
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		add_action( 'after_switch_theme', array( $this, 'edit_theme_mods' ) );
		add_action( 'customize_register', array( $this, 'edit_controls' ), 99 );
		add_action( 'customize_register', array( $this, 'set_extension_default_settings' ), 99 );
		add_action( 'init',               array( $this, 'default_theme_mod_values' ) );
	}
	
	/**
	 * Returns a default theme_mod value if there is none set.
	 * @uses get_pharmacy_extension_defaults()
	 * @return void
	 */
	public function default_theme_mod_values() {
		foreach ( Pharmacy_Integrations::get_pharmacy_extension_defaults() as $mod => $val ) {
			add_filter( 'theme_mod_' . $mod, function( $setting ) use ( $val ) {
				return $setting ? $setting : $val;
			} );
		}
	}

	/**
	 * Returns an array of the desired Storefront extension settings
	 * @return array
	 */
	public function get_pharmacy_extension_defaults() {
		return apply_filters( 'pharmacy_default_extension_settings', $args = array(
			'sd_content_background_color' => '#ffffff',
			'swc_product_columns'         => 4,
		) );
	}

    /**
	 * Set default settings for Storefront extensions to provide compatibility with Pharmacy.
	 * @uses get_pharmacy_extension_defaults()
	 * @return void
	 */
	public function set_extension_default_settings( $wp_customize ) {
		foreach ( Pharmacy_Integrations::get_pharmacy_extension_defaults() as $mod => $val ) {
			$setting = $wp_customize->get_setting( $mod );

			if ( is_object( $setting ) ) {
				$setting->default = $val;
			}
		}
	}

    /**
	 * Remove unused/incompatible controls from the Customizer to avoid confusion
	 * @return void
	 */
	public function edit_controls( $wp_customize ) {
		$wp_customize->remove_control( 'sd_header_layout' );
		$wp_customize->remove_control( 'sd_max_width' );
		$wp_customize->remove_control( 'sd_button_rounded' );
		$wp_customize->remove_control( 'sd_button_flat' );
		$wp_customize->remove_control( 'sd_header_layout_divider_before' );
	}

	/**
	 * Remove any pre-existing theme mods for settings that are incompatible with Pharmacy.
	 * @return void
	 */
	public function edit_theme_mods() {
		remove_theme_mod( 'sd_header_layout' );
		remove_theme_mod( 'sd_max_width' );
		remove_theme_mod( 'sd_button_rounded' );
		remove_theme_mod( 'sd_button_flat' );
		remove_theme_mod( 'sd_header_layout_divider_before' );
	}
}

}

return new Pharmacy_Integrations();