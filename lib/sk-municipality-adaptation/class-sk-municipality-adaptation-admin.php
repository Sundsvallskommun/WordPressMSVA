<?php

/**
 * Class wrapping post type administration.
 * Setting and removing municipality connection on posts.
 *
 * @package    SK_Municipality_Adaptation
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Municipality_Adaptation_Admin {


	/**
	 * Class constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		$this->add_hooks();

	}


	/**
	 * Add hooks used by class and module
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	private function add_hooks() {

		// Add logic and markup for metabox
		add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 3 );

	}


	/**
	 * Add meta box for post type edit page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function meta_boxes() {
		$valid_post_types = array( 'post', 'page' );

		add_meta_box(
			'municipality',
			__( 'Kommuntillhörighet', 'msva' ),
			array( $this, 'metabox_municipality_markup' ),
			$valid_post_types,
			'side'
		);

	}


	/**
	 * The callback function giving the markup for the
	 * meta box on post type edit page.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $post
	 */
	public function metabox_municipality_markup( $post ) {

		$municipalities = self::chosen_municipalities( $post->ID );
		ob_start();
		?>
		<ul class="municipality-adaptation-checklist">
			<li>
				<label class="selectit">
					<input value="sundsvall" type="checkbox" name="municipality_adaptation[]" <?php echo self::checked( 'sundsvall', $municipalities ); ?>/>
					Sundsvall
				</label>
			</li>
			<li>
				<label class="selectit">
					<input value="nordanstig" type="checkbox" name="municipality_adaptation[]" <?php echo self::checked( 'nordanstig', $municipalities ); ?>/>
					Nordanstig
				</label>
			</li>
			<li>
				<label class="selectit">
					<input value="timra" type="checkbox" name="municipality_adaptation[]" <?php echo self::checked( 'timra', $municipalities ); ?>/>
					Timrå
				</label>
			</li>
		</ul>
		<p class="howto">Ange för vilken/vilka kommuner posten är aktuell</p>
		<?php
		return ob_end_flush();

	}


	/**
	 * Save given values to a meta field on post
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $post_id  integer
	 * @param $post     object
	 * @param $update   boolean
	 */
	public function save( $post_id, $post, $update ) {

		// Security checks
		if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		// Check if this is a valid post type
		if ( ! in_array( $post->post_type, SK_Municipality_Adaptation_Settings::valid_post_types() ) ) return $post_id;

		if ( isset( $_POST['municipality_adaptation'] ) ) {

			update_post_meta(
				$post_id,
				'municipality_adaptation',
				$this->municipalities_to_string( $_POST['municipality_adaptation'] )
			);

		} else {

			delete_post_meta( $post_id, 'municipality_adaptation' );

		}

	}


	/**
	 * Get chosen municipalities meta for a post
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $post_id  integer
	 *
	 * @return array    the chosen municipalities
	 */
	public static function chosen_municipalities( $post_id ) {

		$municipalities = get_post_meta( $post_id, 'municipality_adaptation', true );
		return self::municipalities_to_array( $municipalities );

	}


	/**
	 * Turn the municipalities array into a
	 * comma separated string.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $municipalities   array
	 *
	 * @return array    municipalities
	 */
	private static function municipalities_to_string( $municipalities ) {

		if ( is_array( $municipalities ) ) return implode( ',', $municipalities );

		return '';

	}


	/**
	 * Turn the chosen municipalities into an array
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $municipalities   string
	 *
	 * @return string   municipalities
	 */
	private static function municipalities_to_array( $municipalities ) {

		if( ! empty( $municipalities ) ) return explode( ',', $municipalities );

		return array();

	}


	/**
	 * Check if a value is in an array
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $needle   string
	 * @param $haystack array
	 *
	 * @return string
	 */
	public static function checked( $needle, $haystack ) {

		if ( in_array( $needle, $haystack ) ) return 'checked';

		return '';

	}

}