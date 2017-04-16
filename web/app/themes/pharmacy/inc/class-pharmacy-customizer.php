<?php
/**
 * Pharmacy_Customizer Class
 * Makes adjustments to Storefront cores Customizer implementation.
 *
 * @author   WooThemes
 * @since    1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Pharmacy_Customizer' ) ) {

class Pharmacy_Customizer {

	/**
	 * Setup class.
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		$theme 					= wp_get_theme( 'storefront' );
		$storefront_version 	= $theme['Version'];

		add_action( 'wp_enqueue_scripts', 	             array( $this, 'add_customizer_css' ), 1000 );
		add_action( 'customize_register', 	             array( $this, 'add_controls' ), 99 );
		add_filter( 'storefront_setting_default_values', array( $this, 'get_pharmacy_defaults' ) );

		/**
		 * The following can be removed when Storefront 2.1 lands
		 */
		add_action( 'customize_register', 	             array( $this, 'edit_default_customizer_settings' ), 99 );
		add_action( 'init',               	             array( $this, 'default_theme_mod_values' ) );
		if ( version_compare( $storefront_version, '2.0.0', '<' ) ) {
			add_action( 'init',				             array( $this, 'default_theme_settings' ) );
		}
	}

	/**
	 * Returns an array of the desired default Storefront options
	 * @return array
	 */
	public function get_pharmacy_defaults() {
		return array(
			'background_color'                           => '#ffffff',
			'storefront_header_background_color'         => '#2eb343',
			'storefront_header_text_color'               => '#ffffff',
			'storefront_header_link_color'               => '#ffffff',
			'storefront_heading_color'                   => '#2b2b2b',
			'storefront_background_color'                => '#ffffff',
			'storefront_text_color'                      => '#2b2b2b',
			'storefront_accent_color'                    => '#2eb343',
			'storefront_footer_background_color'         => '#f9f9f9',
			'storefront_footer_link_color'               => '#2eb343',
			'storefront_footer_heading_color'            => '#2b2b2b',
			'storefront_footer_text_color'               => '#2b2b2b',
			'storefront_button_background_color'         => '#2eb343',
			'storefront_button_text_color'               => '#ffffff',
			'storefront_button_alt_background_color'     => '#3a7bce',
			'storefront_button_alt_text_color'           => '#ffffff',
		);
	}

	/**
	 * Set default Customizer settings based on Pharmacy design.
	 * @uses get_pharmacy_defaults()
	 * @return void
	 */
	public function edit_default_customizer_settings( $wp_customize ) {
		$wp_customize->get_setting( 'background_color' )->transport                   = 'refresh';
		$wp_customize->get_setting( 'storefront_header_text_color' )->transport       = 'refresh';
		$wp_customize->get_setting( 'storefront_header_link_color' )->transport       = 'refresh';
		$wp_customize->get_setting( 'storefront_heading_color' )->transport           = 'refresh';
		$wp_customize->get_setting( 'storefront_text_color' )->transport              = 'refresh';
		$wp_customize->get_setting( 'storefront_accent_color' )->transport            = 'refresh';
		$wp_customize->get_setting( 'storefront_footer_background_color' )->transport = 'refresh';
		$wp_customize->get_setting( 'storefront_footer_heading_color' )->transport    = 'refresh';
		$wp_customize->get_setting( 'storefront_footer_text_color' )->transport       = 'refresh';
		$wp_customize->get_setting( 'storefront_footer_link_color' )->transport       = 'refresh';

		foreach ( Pharmacy_Customizer::get_pharmacy_defaults() as $mod => $val ) {
			$setting = $wp_customize->get_setting( $mod );

			if ( is_object( $setting ) ) {
				$setting->default = $val;
			}
		}
	}

	/**
	 * Returns a default theme_mod value if there is none set.
	 * @uses get_pharmacy_defaults()
	 * @return void
	*/
	public function default_theme_mod_values() {
		foreach ( Pharmacy_Customizer::get_pharmacy_defaults() as $mod => $val ) {
			add_filter( 'theme_mod_' . $mod, function( $setting ) use ( $val ) {
				return $setting ? $setting : $val;
			} );
		}
	}

	/**
	 * Sets default theme color filters for storefront color values.
	 * This function is required for Storefront < 2.0.0 support
	 * @uses get_pharmacy_defaults()
	 * @return void
	 */
	public function default_theme_settings() {
		$prefix_regex = '/^storefront_/';
		foreach ( self::get_pharmacy_defaults() as $mod => $val) {
			if ( preg_match( $prefix_regex, $mod ) ) {
				$filter = preg_replace( $prefix_regex, 'storefront_default_', $mod );
				add_filter( $filter, function( $_ ) use ( $val ) {
					return $val;
				}, 99 );
			}
		}
	}

	/**
	 * Adds custom controls
	 * @return void
	 */
	public function add_controls( $wp_customize ) {
		if ( function_exists( 'Storefront_WooCommerce_Customiser' ) ) {
			/* Category Description */
			$wp_customize->add_setting( 'pharmacy_category_description', array(
				'sanitize_callback' => 'storefront_sanitize_checkbox',
			) );
			$wp_customize->add_control( 'pharmacy_category_description', array(
				'label'             => __( 'Display product categories description', 'pharmacy' ),
				'description'       => __( 'Toggle the display of the product categories description.', 'pharmacy' ),
				'section'           => 'storefront_homepage',
				'priority'          => 23,
				'type'              => 'checkbox',
			) );
		}
	}

	/**
	 * Add CSS using settings obtained from the theme options.
	 * @return void
	 */
	public function add_customizer_css() {
		$header_background_color     = get_theme_mod( 'storefront_header_background_color', apply_filters( 'storefront_default_header_background_color', '#3a7bce' ) );
		$header_text_color           = get_theme_mod( 'storefront_header_text_color', apply_filters( 'storefront_default_header_text_color', '#ffffff' ) );
		$header_link_color           = get_theme_mod( 'storefront_header_link_color', apply_filters( 'storefront_default_header_link_color', '#ffffff' ) );
		$heading_color               = get_theme_mod( 'storefront_heading_color', apply_filters( 'storefront_default_heading_color', '#2b2b2b' ) );
		$text_color                  = get_theme_mod( 'storefront_text_color', apply_filters( 'storefront_default_text_color', '#2b2b2b' ) );
		$accent_color                = get_theme_mod( 'storefront_accent_color', apply_filters( 'storefront_default_accent_color', '#2eb343' ) );
		$background_color            = storefront_get_content_background_color();
		$footer_link_color           = get_theme_mod( 'storefront_footer_link_color', apply_filters( 'storefront_default_footer_link_color', '#2eb343' ) );
		$footer_heading_color        = get_theme_mod( 'storefront_footer_heading_color', apply_filters( 'storefront_default_footer_heading_color', '#2b2b2b' ) );
		$footer_text_color           = get_theme_mod( 'storefront_footer_text_color', apply_filters( 'storefront_default_footer_text_color', '#2b2b2b' ) );
		$button_background_color     = get_theme_mod( 'storefront_button_background_color', apply_filters( 'storefront_default_button_background_color', '#2eb343' ) );
		$button_text_color           = get_theme_mod( 'storefront_button_text_color', apply_filters( 'storefront_default_button_text_color', '#ffffff' ) );
		$button_alt_background_color = get_theme_mod( 'storefront_button_alt_background_color', apply_filters( 'storefront_default_button_alt_background_color', '#3a7bce' ) );
		$button_alt_text_color       = get_theme_mod( 'storefront_button_alt_text_color', apply_filters( 'storefront_default_button_alt_text_color', '#ffffff' ) );

		$style = '
			.woocommerce-tabs ul.tabs li.active a,
			.woocommerce-tabs ul.tabs li a:focus,
			.page-template-template-homepage-php ul.tabs li a.active,
			.page-template-template-homepage-php ul.tabs li a:focus {
				color: ' . $accent_color . ';
			}

			.woocommerce-tabs ul.tabs li.active a:before,
			.page-template-template-homepage-php ul.tabs li a.active:before {
				background-color: ' . $accent_color . ';
			}

			.woocommerce-tabs ul.tabs li.active a:after {
				background-color: ' . $background_color . ';
			}

			.page-template-template-homepage-php ul.tabs li a.active {
				box-shadow: 0 1px ' . $background_color . ';
			}

			.site-header {
				color: ' . $header_text_color . ';
			}

			.site-branding h1 a {
				color: ' . storefront_adjust_color_brightness( $header_text_color, 50 ) . ';
			}

			.site-branding h1 a:hover {
				color: ' . storefront_adjust_color_brightness( $header_text_color, 10 ) . ';
			}

			.main-navigation ul li.smm-active ul li .widget h3.widget-title {
				color: ' . $heading_color . ';
			}

			.main-navigation ul li.smm-active li a,
			.main-navigation ul li.smm-active li:hover a {
				color: ' . $text_color . '!important;
			}

			.main-navigation ul.menu li.current_page_item > a,
			.main-navigation ul.menu li.current-menu-item > a,
			.main-navigation ul.menu li.current_page_ancestor > a,
			.main-navigation ul.menu li.current-menu-ancestor > a,
			.main-navigation ul.nav-menu li.current_page_item > a,
			.main-navigation ul.nav-menu li.current-menu-item > a,
			.main-navigation ul.nav-menu li.current_page_ancestor > a,
			.main-navigation ul.nav-menu li.current-menu-ancestor > a {
				color: ' . $accent_color . ';
			}

			.main-navigation ul li.smm-active,
			.main-navigation ul li.smm-active li:hover a:active,
			.main-navigation ul li.smm-active li:hover a:focus,
			.main-navigation ul li.smm-active li:hover a:hover {
				color: ' . $text_color . '!important;
			}

			.site-search .widget_product_search,
			.header-widget-region .widget_product_search {
				color: ' . $header_background_color . ';
			}

			.pharmacy-product-section {
				border-color: ' . $button_alt_background_color . ';
			}

			.single-product div.product .summary .price {
				color: ' . $accent_color . ';
			}

			.header-widget-region {
				color: ' . $footer_text_color . ';
			}

			.header-widget-region a:not(.button) {
				color: ' . $footer_link_color . ';
			}

			.single-product div.product .summary .price,
			#infinite-handle span button,
			#infinite-handle span button:active,
			#infinite-handle span button:focus,
			#infinite-handle span button:hover {
				color: ' . $button_text_color . ';
				background-color: ' . $button_background_color . ';
			}

			#infinite-handle span button:active,
			#infinite-handle span button:focus,
			#infinite-handle span button:hover {
				background-color: ' . storefront_adjust_color_brightness( $button_background_color, -15 ) . ';
			}

			.onsale,
			.site-header-cart a.cart-contents:after,
			.main-navigation ul.products li.product.product-category h3,
			.main-navigation ul.products li.product.product-category .category-description,
			.site-main ul.products li.product.product-category h3,
			.site-main ul.products li.product.product-category .category-description {
				color: ' . $button_alt_text_color . ';
				background-color: ' . $button_alt_background_color . ';
			}

			.main-navigation ul.products li.product.product-category a:before,
			.site-main ul.products li.product.product-category a:before {
				border-color: ' . $button_alt_background_color . ';
			}

			.site-header-cart a.cart-contents .count {
				color: ' . $button_alt_text_color . ';
				background-color: ' . storefront_adjust_color_brightness( $button_alt_background_color, -30 ). ';
			}

			.header-widget-region h1,
			.header-widget-region h2,
			.header-widget-region h3,
			.header-widget-region h4,
			.header-widget-region h5,
			.header-widget-region h6 {
				color: ' . $footer_heading_color . ';
			}

			@media screen and (min-width: 768px) {
				.pharmacy-primary-navigation,
				.sticky-wrapper,
				.sd-sticky-navigation,
				.sd-sticky-navigation:before,
				.sd-sticky-navigation:after,
				.site-header-cart .widget_shopping_cart, .main-navigation ul.menu ul.sub-menu, .main-navigation ul.nav-menu ul.children {
					background-color: ' . $background_color . ';
				}

				.smm-mega-menu {
					background-color: ' . $background_color . ' !important;
				}

				.main-navigation ul li a,
				ul.menu li a {
					color: ' . $text_color . ';
				}

				.main-navigation ul.menu a:active,
				.main-navigation ul.menu a:focus,
				.main-navigation ul.menu a:hover,
				.main-navigation ul.menu li:hover > a,
				.main-navigtaion ul.nav-menu li:hover > a,
				.main-navigation ul.nav-menu a:active,
				.main-navigation ul.nav-menu a:focus,
				.main-navigation ul.nav-menu a:hover,
				.main-navigation ul.menu > li.current-menu-item > a,
				.main-navigation ul.nav-menu > li.current-menu-item > a,
				.main-navigation ul.menu > li:hover > a,
				.main-navigation ul.nav-menu > li:hover > a,
				.site-header-cart:hover > li > a,
				a.cart-contents:hover,
				.site-header-cart:hover > li > a,
				ul.menu li.current-menu-item > a {
					color: ' . $header_background_color . ';
				}

				.main-navigation ul.menu a:before:active,
				.main-navigation ul.menu a:before:focus,
				.main-navigation ul.menu a:before:hover,
				.main-navigation ul.nav-menu a:before:active,
				.main-navigation ul.nav-menu a:before:focus,
				.main-navigation ul.nav-menu a:before:hover,
				.main-navigation ul.menu > li.current-menu-item > a:before,
				.main-navigation ul.nav-menu > li.current-menu-item > a:before,
				.main-navigation ul.menu > li:hover > a:before,
				.main-navigation ul.nav-menu > li:hover > a:before {
					background-color: ' . $header_background_color . ';
				}

				.main-navigation ul.menu ul li a:hover,
				.main-navigation ul.nav-menu ul li a:hover {
					color: ' . $header_background_color . ';
				}

				.main-navigation ul.menu ul,
				.main-navigation ul.nav-menu ul {
					background-color: ' . $background_color . ';
				}

				.site-header-cart .widget_shopping_cart {
					background-color: ' . $background_color . ';
					border-color: ' . $button_alt_background_color . ';
				}

				.site-header-cart .widget_shopping_cart,
				.site-header .product_list_widget li .quantity {
					color: ' . $text_color . ';
				}

				.site-header-cart .widget_shopping_cart a {
					color: ' . $accent_color . ';
				}

				a.cart-contents:hover {
					color: ' . $button_alt_background_color . ';
				}

				.site-header-cart .widget_shopping_cart a:hover {
					color: ' . $text_color . ';
				}

				#order_review,
				#payment .payment_methods li .payment_box {
					background-color: ' . storefront_adjust_color_brightness( $background_color, -5 ) . ';
				}

				#payment .payment_methods li {
					background-color: ' . storefront_adjust_color_brightness( $background_color, -9 ) . ';
				}

				#payment .payment_methods li:hover {
					background-color: ' . storefront_adjust_color_brightness( $background_color, -12 ) . ';
				}
			}';

		wp_add_inline_style( 'storefront-child-style', $style );
	}
}

}

return new Pharmacy_Customizer();