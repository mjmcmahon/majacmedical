<?php
/**
 * Plugged functions
 * Any functions declared here are overwriting counterparts from a plugin or Storefront core.
 *
 * @package pharmacy
 */

 /**
 * Display Featured Products
 * Hooked into the `homepage` action in the homepage template
 * @since  1.0.0
 * @return void
 */
function storefront_featured_products( $args ) {

	if ( class_exists( 'WooCommerce' ) ) {

		$args = apply_filters( 'storefront_featured_products_args', array(
			'limit' 			=> 4,
			'columns' 			=> 4,
			'orderby'			=> 'date',
			'order'				=> 'desc',
			'title'				=> __( 'Featured Products', 'storefront' ),
			) );

		echo '<section class="pharmacy-product-section pharmacy-featured-products">';

			do_action( 'storefront_homepage_before_featured_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			echo storefront_do_shortcode( 'featured_products',
				array(
					'per_page' 	=> intval( $args['limit'] ),
					'columns'	=> intval( $args['columns'] ),
					'orderby'	=> esc_attr( $args['orderby'] ),
					'order'		=> esc_attr( $args['order'] ),
					) );

			do_action( 'storefront_homepage_after_featured_products' );

		echo '</section>';

	}
}

/**
 * Adds the description of the category on list of categories.
 * @since 1.0.0
 */
function pharmacy_category_description( $category ) {
	echo '<span class="category-description">' . $category->description . '</span>';
}

function pharmacy_add_category_description() {
	if ( 1 == get_theme_mod( 'pharmacy_category_description' ) ) {
    	add_action( 'woocommerce_after_subcategory', 'pharmacy_category_description', 1 );
    } else {
	    return;
    }
}
add_action( 'woocommerce_after_subcategory', 'pharmacy_add_category_description' );

/**
 * Cart Link
 * Plugged to remove 'items' text.
 * @since  1.0.0
 */
if ( ! function_exists( 'storefront_cart_link' ) ) {
	function storefront_cart_link() {
		?>
			<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'storefront' ); ?>">
				<?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?> <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></span>
			</a>
		<?php
	}
}