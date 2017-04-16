/**
 * pharmacy.js
 *
 * Javascript used by the Pharmacy theme.
 */
( function() {
	jQuery( window ).load( function() {
		if ( jQuery( 'body' ).hasClass( 'woocommerce-page' ) && jQuery( '.scroll-wrap' ).length ) {
			jQuery( 'body' ).addClass( 'woocommerce-infinite-scroll' );
		}

		if ( jQuery( window ).width() > 767 ) {

			/**
			 * The homepage product tabs
			 */

			// Create an empty `ul` for the tabs
			if ( jQuery( '.pharmacy-featured-products' ).size() ) {
				jQuery( '.pharmacy-featured-products' ).after( '<ul class="tabs"></ul>' );
			} else if ( jQuery( '.storefront-product-categories' ).size() ) {
				jQuery( '.storefront-product-categories' ).after( '<ul class="tabs"></ul>' );
			} else {
				jQuery( '.storefront-product-section:first-child' ).before( '<ul class="tabs"></ul>' );
			}

			// Create the list items based on the titles in each of the product sections
			jQuery( '.storefront-product-section:not(".storefront-product-categories"):not(".storefront-reviews"):not(".storefront-blog"):not(".storefront-homepage-contact-section") .section-title' ).each( function( i ) {
			    var current = jQuery(this);
			    current.attr( 'section' );
			    jQuery( 'ul.tabs' ).append( '<li class="' + current.html().toLowerCase() + '"><a href="#section' +
			        i + '">' +
			        current.html() + '</a></li>' );
			} );

			// Now hide the actual section titles
			jQuery( '.storefront-product-section:not(".storefront-product-categories"):not(".storefront-reviews"):not(".storefront-blog"):not(".storefront-homepage-contact-section") .section-title' ).hide();

			// Give the first tab an active class
			jQuery( 'ul.tabs li:first-child a' ).addClass( 'active' );

			// Assign an id to each of the product sections for internal linking
			jQuery( '.storefront-product-section:not(".storefront-product-categories"):not(".storefront-reviews"):not(".storefront-blog"):not(".storefront-homepage-contact-section")' ).each( function(i) {
			    var current = jQuery(this);
			    current.attr( 'id', 'section' );
			    jQuery( this ).attr( 'id', 'section' + i );
			} );

			// The tabs
			jQuery( '.storefront-product-section:not(".storefront-product-categories"):not(".storefront-reviews"):not(".storefront-blog"):not(".storefront-homepage-contact-section")' ).hide();
			jQuery( '.storefront-product-section:not(".storefront-product-categories"):not(".storefront-reviews"):not(".storefront-blog"):not(".storefront-homepage-contact-section"):first' ).show();

			jQuery( '.tabs li' ).click(function( e ) {
				e.preventDefault();
			    jQuery( '.tabs li a' ).removeClass( 'active' );
			    jQuery( this ).find( 'a' ).addClass( 'active' );
			    jQuery( '.storefront-product-section:not(".storefront-product-categories"):not(".storefront-reviews"):not(".storefront-blog"):not(".storefront-homepage-contact-section")' ).hide();

			    var indexer = jQuery( this ).index(); //gets the current index of (this) which is .tabs li
			    jQuery( '.storefront-product-section:not(".storefront-product-categories"):eq( ' + indexer + ' )' ).fadeIn(); //uses whatever index the link has to open the corresponding box
			} );

	    }
	} );

} )();