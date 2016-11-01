<?php

class SK_Municipality_Adaptation_Settings {

	public function __construct() {

		// Add section to admin for valid post types used in adaptation
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );

	}


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


	public function valid_post_types_field_callback() {
		ob_start();

		$post_types = get_post_types(
			array(
				'public' => true
			)
		);
		$valid_post_types = SK_Municipality_Adaptation::valid_post_types();

		?>
		<ul>
			<?php foreach( $post_types as $key => $post_type ) : ?>
				<li>
					<label class="selectit">
						<input type="checkbox" name="municipality_adaptation_valid_post_types[]" value="<?php echo $post_type; ?>" <?php echo SK_Municipality_Adaptation::checked( $post_type, $valid_post_types ); ?>/>
						<?php echo $post_type; ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		return ob_end_flush();
	}


}