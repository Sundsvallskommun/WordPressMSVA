<?php

function msva_options_page() {
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_sub_page(array(
			'page_title'  => 'Inställningar för MSVA',
			'menu_title'  => 'MSVA',
			'parent_slug' => 'general-settings'
		));
	}
}
add_filter('init', 'msva_options_page');



/**
 * CSS setup for SK Child Theme.
 *
 * @author Daniel Pihlström
 *
 * @since 1.0.0
 *
 */
function sk_childtheme_enqueue_styles() {

	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/assets/css/style.css',
		array( 'main' ),
		wp_get_theme()->get('Version')
	);

	wp_enqueue_script( 'main-child', get_stylesheet_directory_uri() . '/assets/js/app.js', ['jquery', 'handlebars', 'typeahead'] );
}
add_action( 'wp_enqueue_scripts', 'sk_childtheme_enqueue_styles' );

/**
 * Get ACF-JSON from parent theme.
 *
 */
add_filter('acf/settings/save_json', function() {
	return get_stylesheet_directory() . '/acf-json';
});


/**
 * Get ACF json fields from parent theme, child theme and plugins having acf fields
 *
 */
add_filter('acf/settings/load_json', function( $paths ) {

	$parent_theme_paths = array( get_template_directory() . '/acf-json' );
	$paths = array_merge( $paths, $parent_theme_paths );

	if(is_child_theme())
	{
		$paths[] = get_stylesheet_directory() . '/acf-json';
	}
	return $paths;
});



/**
 * REQUIRE NAME
 * ============
 *
 * DESC
 */
//require_once locate_template( 'lib/XXXX' );
//$sk_name = new SK_NAME();

/* SK Waste Guide */
require_once locate_template( 'lib/sk-wasteguide/class-sk-wasteguide.php' );
$sk_wasteguide = new SK_Wasteguide();

/* SK Municipality Adaptation */
require_once locate_template( 'lib/sk-municipality-adaptation/class-sk-municipality-adaptation.php' );
$sk_municipality_adaptation = new SK_Municipality_Adaptation();

/* SK Connection Fee */
require_once locate_template( 'lib/sk-connection-fee/class-sk-connection-fee.php' );
$sk_connection_fee = new SK_Connection_Fee();