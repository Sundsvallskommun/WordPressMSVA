<?php

/**
 * Register the shortcode and handle search logic
 *
 * @package    Wp_Plugin_Wasteguide
 * @subpackage Wp_Plugin_Wasteguide/includes
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Wasteguide_Search {

	private $search_string = '';

	/**
	 * Register the shortcode for use
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		add_shortcode( 'sorteringsguide', array( $this, 'output' ) );

		// Add material as post type to search for
		add_filter( 'tivoli_search_included_post_types', array( $this, 'add_material_post_type_to_search' ) );

	}


	public function add_material_post_type_to_search( $extra_post_types = array() ) {

		array_push( $extra_post_types, 'material' );

		return $extra_post_types;

	}


	/**
	 * HTML output for the search form
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return   string
	 */
	public function output( $atts, $content ) {
		ob_start();
		?>
		<form method="get" action="">
			<label for="sorting-material"><?php _e( 'Sök i sorteringsguiden', 'msva' ); ?></label>
			<div class="input-group">
				<input type="text" class="form-control" id="sorting-material"
				       placeholder="<?php _e( 'Ange vad du vill sortera', 'msva' ); ?>" name="search_wasteguide">
				<span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Sök</button>
                </span>
			</div>
		</form>
		<?php

		if ( isset( $_GET['search_wasteguide'] ) ) {
			require_once( get_stylesheet_directory() . '/lib/sk-wasteguide/template.php' );
		}

		return ob_get_clean();

	}

	/**
	 * Get searched string.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @return mixed
	 */
	public static function get_search_string() {
		$search_string = $_GET['search_wasteguide'];

		return $search_string;
	}

}