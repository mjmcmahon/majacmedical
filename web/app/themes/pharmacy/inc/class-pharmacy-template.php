<?php
/**
 * Pharmacy_Template Class
 *
 * @author   WooThemes
 * @since    1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Pharmacy_Template' ) ) {

class Pharmacy_Template {

	/**
	 * Setup class.
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'layout_adjustments' ) );
		add_filter( 'storefront_products_per_page', array( $this, 'products_per_page' ) );
	}

	/**
	 * Layout adjustments
	 * @return rearrange markup through add_action and remove_action
	 */
	public function layout_adjustments() {
		if ( class_exists( 'WooCommerce' ) ) {
			remove_action( 'homepage', 'storefront_featured_products', 40 );
			add_action( 'homepage', 'storefront_featured_products', 25 );

			remove_action( 'storefront_content_top', 'storefront_shop_messages', 1 );
			add_action( 'storefront_content_top', 'storefront_shop_messages', 10 );

			remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10 );
			add_action( 'storefront_content_top', 'woocommerce_breadcrumb', 1 );
		}

		remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
		add_action( 'storefront_header', 'storefront_secondary_navigation', 20 );

		remove_action( 'storefront_header', 'storefront_site_branding', 20 );
		add_action( 'storefront_header', 'storefront_site_branding', 30 );

		add_action( 'storefront_header', array( 'Pharmacy_Template', 'pharmacy_secondary_navigation_wrapper' ), 15 );
		add_action( 'storefront_header', array( 'Pharmacy_Template', 'pharmacy_secondary_navigation_wrapper_close' ), 25 );

		add_action( 'storefront_header', array( 'Pharmacy_Template', 'pharmacy_primary_navigation_wrapper' ), 45 );
		add_action( 'storefront_header', array( 'Pharmacy_Template', 'pharmacy_primary_navigation_wrapper_close' ), 65 );

		add_action( 'storefront_footer', array( 'Pharmacy_Template', 'pharmacy_site_info_wrapper' ), 15 );
		add_action( 'storefront_footer', array( 'Pharmacy_Template', 'pharmacy_site_info_wrapper_close' ), 25 );
	}

	/**
	 * Primary navigation wrapper
	 * @return void
	 */
	public static function pharmacy_primary_navigation_wrapper() {
		echo '<section class="pharmacy-primary-navigation">';
	}

	/**
	 * Primary navigation wrapper close
	 * @return void
	 */
	public static function pharmacy_primary_navigation_wrapper_close() {
		echo '</section>';
	}

	/**
	 * Secondary navigation wrapper
	 * @return void
	 */
	public static function pharmacy_secondary_navigation_wrapper() {
		echo '<section class="pharmacy-secondary-navigation">';
	}

	/**
	 * Secondary navigation wrapper close
	 * @return void
	 */
	public static function pharmacy_secondary_navigation_wrapper_close() {
		echo '</section>';
	}

	/**
	 * Site info wrapper
	 * @return void
	 */
	public static function pharmacy_site_info_wrapper() {
		echo '<div class="pharmacy-site-info-wrapper">';
	}

	/**
	 * Site info wrapper close
	 * @return void
	 */
	public static function pharmacy_site_info_wrapper_close() {
		echo '</div>';
	}

	/**
	 * Products per page
	 * @return int products to display per page
	 */
	public function products_per_page( $per_page ) {
		$per_page = 12;
		return intval( $per_page );
	}
}

}

return new Pharmacy_Template();