<?php
/**
 * Register the shortcode and handle search logic
 *
 * @package    Wp_Plugin_Wasteguide
 * @subpackage Wp_Plugin_Wasteguide/includes
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Wasteguide_Search {

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
		<form method="get" action="<?php echo home_url( '/' ); ?>">
			<label for="sorting-material"><?php _e('Sök i sorteringsguiden', 'msva' ); ?></label>
			<div class="input-group">
				<input type="text" class="form-control" id="sorting-material" placeholder="Ange Material" name="s">
				<input type="hidden" name="search_type" value="wasteguide" />
				<span class="input-group-btn">
                <button class="btn btn-secondary" type="submit">Sök</button>
            </span>
			</div>
		</form>
		<?php
		return ob_get_clean();

	}


	/**
	 * Filter the search query listening
	 * for the material post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return   object     the query used in search
	 */
	public function search_filter( $query ) {

		if ($query->is_search && !is_admin() ) {

			if( isset( $_GET['search_type'] ) ) {

				$type = $_GET['search_type'];

				if($type == 'wasteguide') {

					$query->set( 'search_type', ['wasteguide'] );

				}
			}
		}

		return $query;

	}

	public function template( $template ) {
		global $wp_query;

		$search_type = get_query_var( 'search_type' );

		if( $wp_query->is_search && is_array( $search_type ) && $search_type[0] == 'wasteguide' ) {

			return locate_template('lib/sk-wasteguide/template.php');

		}

		return $template;
	}

}