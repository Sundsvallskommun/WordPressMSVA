<?php

/**
 * Class wrapping the functionality for module settings.
 *
 * @package    SK_Municipality_Adaptation
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */

class SK_Municipality_Adaptation_Settings {


	/**
	 * Class constructor
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		// Add section to admin for valid post types used in adaptation
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );

	}


	/**
	 * Setup the settings functionality
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function settings_api_init() {

		// Add the section to reading settings so we can add our
		// fields to it
		add_settings_section(
			'municipality_adaptation_section',
			__( 'Inställningar för kommuntillhörighet', 'msva' ),
			array( $this, 'municipality_adaptation_section_callback' ),
			'reading'
		);

		// Add the field with the names and function to use for our new
		// settings, put it in our new section
		add_settings_field(
			'municipalit_adaptation_valid_post_types',
			'Ange posttyper som kommuntillhörighet skall appliceras på',
			array( $this, 'valid_post_types_field_callback' ),
			'reading',
			'municipality_adaptation_section'
		);

		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting( 'reading', 'municipality_adaptation_valid_post_types' );

	}


	public function municipality_adaptation_section_callback() {

	}


	/**
	 * Callback and HTML markup for the post types
	 * checkboxes on settings page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function valid_post_types_field_callback() {
		ob_start();

		$post_types = get_post_types();
		$valid_post_types = self::valid_post_types();

		?>
		<ul>
			<?php foreach( $post_types as $key => $post_type ) : ?>
				<li>
					<label class="selectit">
						<input type="checkbox" name="municipality_adaptation_valid_post_types[]" value="<?php echo $post_type; ?>" <?php echo SK_Municipality_Adaptation_Admin::checked( $post_type, $valid_post_types ); ?>/>
						<?php echo $post_type; ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		return ob_end_flush();
	}


	/**
	 * Get valid post types chosen on the settings page
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return array    array with the options
	 */
	public static function valid_post_types() {

		$options = get_option( 'municipality_adaptation_valid_post_types', true );
		if ( empty( $options ) ) $options = array();

		return $options;

	}

}