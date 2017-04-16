<?php
/**
 * Pharmacy engine room
 *
 * @package pharmacy
 */

/**
 * Set the theme version number as a global variable
 */
 $theme              = wp_get_theme( 'pharmacy' );
 $pharmacy_version = $theme['Version'];

/**
 * Load the individual classes required by this theme
 */
require_once( 'inc/class-pharmacy.php' );
require_once( 'inc/class-pharmacy-customizer.php' );
require_once( 'inc/class-pharmacy-template.php' );
require_once( 'inc/class-pharmacy-integrations.php' );
require_once( 'inc/plugged.php' );

/**
 * Do not add custom code / snippets here.
 * While Child Themes are generally recommended for customisations, in this case it is not
 * wise. Modifying this file means that your changes will be lost when an automatic update
 * of this theme is performed. Instead, add your customisations to a plugin such as
 * https://github.com/woothemes/theme-customisations
 */