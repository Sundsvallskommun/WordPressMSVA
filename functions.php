<?php
/**
 * CSS setup for SK Child Theme.
 *
 * @author Daniel Pihlström
 *
 * @since 1.0.0
 *
 */
function sk_childtheme_enqueue_styles() {

	wp_enqueue_style( 'main', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/assets/css/style.css',
		array( 'main' ),
		wp_get_theme()->get('Version')
	);
}
add_action( 'wp_enqueue_scripts', 'sk_childtheme_enqueue_styles' );


/**
 * Get ACF-JSON from parent theme.
 *
 */
add_filter('acf/settings/save_json', function() {
	return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function( $paths ) {
	$paths = array(get_template_directory() . '/acf-json');

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