<?php
/**
 * Override parent tiny mce settings.
 * 
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 * 
 * @param integer $toolbar
 * @return void
 */
function get_tinymce_toolbar_items($toolbar = 1) {
	if ( 1 === intval($toolbar) ) return 'formatselect, bold, link, unlink, blockquote, bullist, numlist, table, spellchecker, eservice_button, youtube_button, sk_collapse, sk_buttonlink, rml_folder';
	if ( 2 === intval($toolbar) ) return 'pastetext, removeformat, charmap, undo, redo';
	return false;
}
add_filter('tiny_mce_before_init', 'tiny_mce_settings', 99);

function tiny_mce_settings($settings) {

	/**
	 * Select what to be shown in tinymce toolbars.
	 *
	 * Original settings:
	 *
	 * toolbar1 = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv,eservice_button'
	 * toolbar2 = 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
	 */
	$settings['toolbar1'] = get_tinymce_toolbar_items(1);
	$settings['toolbar2'] = get_tinymce_toolbar_items(2);

	/**
	 * Always show toolbar 2
	 */
	$settings['wordpress_adv_hidden'] = false;

	/**
	 * Block formats to show in editor dropdown. We remove h1 as we set page
	 * title to h1 in the theme.
	 */
	$settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;';

	return $settings;

}

/**
 * Adding template path to script.
 * 
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 * @return void
 */
function msva_template_dir_js_var() {
	?>
	<script>
		var msvaTemplateDir = "<?php echo get_stylesheet_directory_uri(); ?>";
	</script>
	<?php
}
add_action('admin_head', 'msva_template_dir_js_var');


/**
 * Override gettext translation.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 * @param $translated_text
 * @param $text
 * @param $domain
 *
 * @return string|void
 */
function override_translation( $translated_text, $text, $domain ) {
	if(is_search() ) {
		switch ( $translated_text ) {
			case 'Bilder och dokument' :
				$translated_text = __( 'Dokument', 'sk_tivoli' );
				break;
		}

	}
	return $translated_text;
}
add_filter( 'gettext', 'override_translation', 20, 3 );


/**
 * Remove images from search results.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 * @param $args
 *
 * @return mixed
 */
function remove_images_from_search( $args ) {
	$remove_mimes           = array(
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/bmp',
		'image/tiff',
		'image/x-icon'
	);
	$all_mimes              = get_allowed_mime_types();
	$accepted_mimes         = array_diff( $all_mimes, $remove_mimes );
	$args['post_mime_type'] = $accepted_mimes;

	return $args;
}

add_filter( 'search_attachments_args', 'remove_images_from_search', 10, 1 );


/**
 * Adding custom images sizes
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
function msva_add_image_size() {
	add_image_size( 'full-page', 1170, 9999 );
	add_image_size( 'msva-content-full', 800, 9999 );
}

add_action( 'after_setup_theme', 'msva_add_image_size' );

/**
 * Redirect function for event location.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
function msva_redirect_event_location() {
	$queried_post_type = get_query_var( 'post_type' );
	if ( is_single() && $queried_post_type === 'event_location' ) {

		if ( get_field( 'msva_redirect_type' ) === 'internal' && ! empty( get_field( 'msva_internal_link' ) ) ) {
			wp_redirect( get_field( 'msva_internal_link' ), 301 );
			exit;
		}

		if ( get_field( 'msva_redirect_type' ) === 'external' && ! empty( get_field( 'msva_external_link' ) ) ) {
			wp_redirect( get_field( 'msva_external_link' ), 301 );
			exit;
		}

	}
}

add_action( 'template_redirect', 'msva_redirect_event_location', 10 );


/**
 *
 * Adding google analytics before </head>
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
function google_analytics() { ?>
	<script>
		(function (i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function () {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

		ga('create', 'UA-93844217-1', 'auto');
		ga('send', 'pageview');
	</script>
<?php }

add_action( 'wp_head', 'google_analytics', 99 );


/**
 * Adding custom ACF settings page for MSVA.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
function msva_options_page( $subpages ) {
	$subpages[] = array(

		'page_title'  => 'Anpassade inställningar för MSVA',
		'menu_title'  => 'MSVA',
		'parent_slug' => 'general-settings',

	);

	return $subpages;
}

add_filter( 'sk_acf_options_page', 'msva_options_page', 999, 1 );

/**
 * CSS setup for SK Child Theme.
 *
 * @author Daniel Pihlström
 *
 * @since  1.0.0
 *
 */
function sk_childtheme_enqueue_styles() {

	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/assets/css/style.css',
		array( 'main' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script( 'main-child', get_stylesheet_directory_uri() . '/assets/js/app.js', [
		'jquery',
		'handlebars',
		'typeahead'
	] );

	$front_page = false;
	if ( is_front_page() ) {
		$front_page = true;
	}

	wp_localize_script( 'main-child', 'ajax_object', array(
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ),
			'area'       => SK_Municipality_Adaptation_Cookie::print_value(),
			'page'       => $front_page === true ? '1' : null
		)
	);
}

add_action( 'wp_enqueue_scripts', 'sk_childtheme_enqueue_styles' );

/**
 * Get ACF-JSON from parent theme.
 *
 */
add_filter( 'acf/settings/save_json', function () {
	return get_stylesheet_directory() . '/acf-json';
} );


/**
 * Get ACF json fields from parent theme, child theme and plugins having acf fields
 *
 */
add_filter( 'acf/settings/load_json', function ( $paths ) {

	$parent_theme_paths = array( get_template_directory() . '/acf-json' );
	$paths              = array_merge( $paths, $parent_theme_paths );

	if ( is_child_theme() ) {
		$paths[] = get_stylesheet_directory() . '/acf-json';
	}

	return $paths;
} );


/**
 * Trigger overlay if no cookie is set.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 * @param $attr
 *
 * @return string
 */
function msva_region_selector( $attr ) {
	if ( ! SK_Municipality_Adaptation_Cookie::exists() ) {
		return 'onload="openNav();"';
	}
}

add_filter( 'sk_body_attr', 'msva_region_selector' );


/**
 * Adding text/ingress to navigation card templates.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 * @return bool
 */
function msva_ingress_navigation_card() {
	global $post;

	if ( basename( get_page_template() ) !== 'page-navigation.php' ) {
		return false;
	}

	if ( empty( $post->post_content ) ) {
		return false;
	}

	?>
	<div class="navigation-card-ingress"><p><?php echo $post->post_content; ?></p></div>
	<?php

}

add_action( 'sk_after_page_title', 'msva_ingress_navigation_card' );

/**
 * Add survey popup to page
 */
function addSurvey() {
	echo '<script src="https://www.defgo.net/surveys/se/msva/float/defgo_float_init.js"></script>';
}
// Hook for <head></head>
add_action( 'wp_head', 'addSurvey' );


/**
 * REQUIRE NAME
 * ============
 *
 * DESC
 */
//require_once locate_template( 'lib/XXXX' );
//$sk_name = new SK_NAME();

/* SK Button Link */
require_once locate_template( 'lib/sk-buttonlink/class-sk-buttonlink.php' );
$sk_buttonlink = new SK_Buttonlink();

/* SK Waste Guide */
require_once locate_template( 'lib/sk-wasteguide/class-sk-wasteguide.php' );
$sk_wasteguide = new SK_Wasteguide();

/* SK Municipality Adaptation */
require_once locate_template( 'lib/sk-municipality-adaptation/class-sk-municipality-adaptation.php' );
$sk_municipality_adaptation = new SK_Municipality_Adaptation();

/* SK Connection Fee */
require_once locate_template( 'lib/sk-connection-fee/class-sk-connection-fee.php' );
$sk_connection_fee = new SK_Connection_Fee();

/* SK Garbage Scheme */
require_once locate_template( 'lib/sk-garbage-scheme/class-sk-garbage-scheme.php' );
$sk_garbage_scheme = new SK_Garbage_Scheme();


/* SK Ongoing projects */
require_once locate_template( 'lib/sk-ongoing-projects/class-sk-ongoing-projects.php' );
$sk_ongoing_projects = new SK_Ongoing_Projects();

/* SK FAQ */
require_once locate_template( 'lib/sk-faq/class-sk-faq.php' );
$sk_faq = new SK_FAQ();

/* SK (MSVA) Blocks */
require_once locate_template( 'lib/sk-blocks/class-sk-blocks.php' );
$sk_blocks = new SK_Blocks();

/* SK Operation Messages */
require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages.php' );
$sk_operation_messages = new SK_Operation_Messages();

/* SK My Pages */
require_once locate_template( 'lib/sk-my-pages/class-sk-my-pages.php' );
$sk_my_pages = new SK_My_Pages();

/* SK Custom News Feed */
require_once locate_template( 'lib/sk-news-feed/class-sk-news-feed.php' );
$sk_news_feed = new SK_News_Feed();

/* SK Custom News Feed */
require_once locate_template( 'lib/sk-news/class-sk-news.php' );
$sk_news = new SK_News();

/* SK Critical Information */
require_once locate_template( 'lib/sk-critical-information/class-sk-critical-information.php' );
$sk_critical_information = new SK_Critical_Information();

/* SK Search Alias */
require_once locate_template( 'lib/sk-search-alias/class-sk-search-alias.php' );
$sk_search_alias = new SK_Search_Alias();

/* SK Info Banner */
require_once locate_template( 'lib/sk-info-banner/class-sk-info-banner.php' );
$sk_info_banner = new SK_Info_Banner();

/* SK Highlight */
require_once locate_template( 'lib/sk-highlight/class-sk-highlight.php' );
$sk_highlight = new SK_Highlight();