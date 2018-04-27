<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying archives pages
 *
 * Please do not overload this file directly. Instead have a look at framework/templates/archive.php: you should find all
 * the needed hooks there.
 */

add_filter( 'wpseo_canonical', 'yoast_return_homepage' );
us_load_template( 'templates/author' );

function yoast_return_homepage( ) {
	return "https://visitdfw.com";
}