<?php

require_once 'class-sk-municipality-adaptation-settings.php';

class SK_Municipality_Adaptation {

	protected $valid_post_types;

	public function __construct() {

		$this->init();
		$this->add_hooks();

	}


	private function init() {

		$settings = new SK_Municipality_Adaptation_Settings();
		$this->valid_post_types = self::valid_post_types();

	}


	private function add_hooks() {

		// Add logic and markup for metabox
		add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 3 );

		// Add filter for querys
		add_action( 'pre_get_posts', array( $this, 'modify_query' ) );

	}



	public static function valid_post_types() {

		$options = get_option( 'municipality_adaptation_valid_post_types', true );
		if ( empty( $options ) ) $options = array();

		return $options;

	}


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


	public function metabox_municipality_markup( $post ) {

		$municipalities = get_post_meta( $post->ID, 'municipality_adaptation', true );
		$municipalities = $this->municipalities_to_array( $municipalities );
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


	public function save( $post_id, $post, $update ) {

		// Security checks
		if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		// Check if this is a valid post type
		if ( ! in_array( $post->post_type, $this->valid_post_types ) ) return $post_id;

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


	public function modify_query( $query ) {

		// Check if on frontend and that this is a post type we should modify results for
		if( ! is_admin() && $this->query_has_valid_post_type( $query->query_vars['post_type'] ) ) {

			$query->set(
				'meta_query',
				array(
					array(
						'key' => 'municipality_adaptation',
						'value' => 'sundsvall',
						'compare' => 'LIKE'
					)
				)
			);
		}

		return $query;

	}


	private function municipalities_to_string( $municipalities ) {

		if ( is_array( $municipalities ) ) return implode( ',', $municipalities );

		return '';

	}


	private function municipalities_to_array( $municipalities ) {

		if( ! empty( $municipalities ) ) return explode( ',', $municipalities );

		return array();

	}


	public static function checked( $needle, $haystack ) {

		if ( in_array( $needle, $haystack ) ) return 'checked';

		return '';

	}


	private function query_has_valid_post_type( $post_type ) {

		if ( in_array( $post_type, $this->valid_post_types ) ) return true;

		return false;

	}


}