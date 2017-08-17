<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
 * Customise the content of the email copy the customer receives()
 */
add_filter( 'pep_customer_email_content', 'customise_content', 10, 1 );
function customise_content($cust_mail)
{
    $add_to_content =
    "<tr>
       <th style='width:25%;text-align:left'>" . __( 'Time', 'pep_text_domain' ) . " </th>
       <td style='width:75%'>:" . date('l jS \of F Y h:i:s A') .  "</td>
     </tr>";
    return $add_to_content.$cust_mail;
}
