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
		add_action( 'edit_attachment', array ( $this, 'save_attachment' ) );
		add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_media_fields' ), 11, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'save_attachment_media_fields' ), 11, 2 );

		// Add extra columns for certain post types
        // in post types list page.
		add_action( 'manage_pages_custom_column', array( $this, 'add_extra_column_content' ), 10, 2  );
		add_filter( 'manage_pages_columns', array( $this, 'add_extra_column' ));
		add_action( 'manage_posts_custom_column', array( $this, 'add_extra_column_content' ), 10, 2  );
		add_filter( 'manage_posts_columns', array( $this, 'add_extra_column' ));

        // Set the column width of the municipality column
		add_action( 'admin_print_styles-edit.php', array( $this, 'limit_municipality_column_width' ) );

	}


	/**
	 * Set width of the municipality column
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function limit_municipality_column_width() {

		echo '<style> .column-kommun { width: 200px; }</style>';

    }


	/**
	 * Add the extra municipality column
	 *
	 * @since    1.0.0
	 * @access   public
     *
     * @param array     the columns
     *
     * @return array    the filtered columns
	 */
	public function add_extra_column( $columns ) {
        global $post_type;

        $valid_post_types = SK_Municipality_Adaptation_Settings::valid_post_types();

        if( in_array( $post_type, $valid_post_types ) ) {

            if ( is_array( $columns ) && ! isset( $columns['kommun'] ) ) {

                $columns['kommun'] = __( 'Kommun' );

            }

        }

		return $columns;

    }


	/**
	 * Add municipality column content
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function add_extra_column_content( $column_name, $post_id ) {

	    if ( $column_name == 'kommun') {

		    echo get_post_meta( $post_id, 'municipality_adaptation', true );

        }


    }


	/**
	 * Add meta box for post type edit page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function meta_boxes() {
        $valid_post_types = SK_Municipality_Adaptation_Settings::valid_post_types();

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
		<label class="selectit">
			<input type="checkbox" name="municipality_adaptation_override" <?php if ( self::override_parent( $post->ID  ) ) echo 'checked'; ?>/>
			<?php _e( 'Åsidosätt eventuell förälders kommuntillhörighet', 'msva' ); ?>
		</label>
		<p class="howto"><?php _e( 'Detta gör att även om en föräldrasida är låst till en specifik kommun så kan barnsidan ändå visas för ovan valda kommuner.' ); ?></p>
		<?php
		return ob_end_flush();

	}


	public static function override_parent( $post_id ) {

		$override = get_post_meta( $post_id, 'municipality_adaptation_override', true );
		if ( $override == true ) return true;

		return false;

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

		if ( isset( $_POST['municipality_adaptation_override'] ) ) {

			update_post_meta( $post_id, 'municipality_adaptation_override', true );

		} else {

			delete_post_meta( $post_id, 'municipality_adaptation_override' );

		}

	}


	/**
     * Need to have different save method for attachments when opening
     * in different kind of views.
     * This saves adaptation post meta for attachments.
     *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function save_attachment( $post_id ) {

		// Security checks
		if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		$post = get_post( $post_id );

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

		if ( isset( $_POST['municipality_adaptation_override'] ) ) {

			update_post_meta( $post_id, 'municipality_adaptation_override', true );

		} else {

			delete_post_meta( $post_id, 'municipality_adaptation_override' );

		}

    }


	/**
     * The html output for the new attachment smeta fields.
     *
	 * @param $form_fields
	 * @param null $post
	 *
	 * @return mixed
	 */
    public function attachment_media_fields( $form_fields, $post = null ) {

	    $municipality_adaptation = get_post_meta( $post->ID, 'municipality_adaptation', true );
	    $municipality_adaptation_override = get_post_meta( $post->ID, 'municipality_adaptation_override', true );


	    $form_fields['municipality_adaptation'] = array(
		    'label' => __('Kommuntillhörighet', 'msva'),
		    'input' => 'html',
		    'html' =>  $this->attachment_media_fields_output( $post, $municipality_adaptation )
	    );

	    $form_fields['municipality_adaptation_override'] = array(
		    'label' => __('Åsidosätt eventuell förälders kommuntillhörighet', 'msva'),
		    'input' => 'html',
		    'html' =>  $this->municipality_adaptation_overide_attachment_fields_output( $post, $municipality_adaptation_override )
	    );


	    return $form_fields;

    }


	/**
	 * Need to have different save method for attachments when opening
	 * in different kind of views.
	 * This saves adaptation post meta for attachments.
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
    public function save_attachment_media_fields( $post, $attachment ) {

	    // Check if this is a valid post type
	    if ( ! in_array( $post['post_type'], SK_Municipality_Adaptation_Settings::valid_post_types() ) ) return $post;

	    if ( isset( $attachment['municipality_adaptation'] ) || isset( $attachment['municipality_adaptation_override'] ) ) {

		    if ( isset( $attachment['municipality_adaptation'] ) ) {

			    $result = update_post_meta(
				    $post['ID'],
				    'municipality_adaptation',
				    $this->municipalities_to_string( $attachment['municipality_adaptation'] )
			    );


		    } else {

			    delete_post_meta( $post['ID'], 'municipality_adaptation' );

		    }

		    if ( isset( $attachment['municipality_adaptation_override'] ) ) {

			    update_post_meta( $post['ID'], 'municipality_adaptation_override', true );

		    } else {

			    delete_post_meta( $post['ID'], 'municipality_adaptation_override' );

		    }

		    return $post;


        }

	    return $post;

    }


	/**
     * HTML output fot extra attachment meta fields.
     *
	 * @param $post
	 * @param null $mao
	 *
	 * @return string
	 */
    private function municipality_adaptation_overide_attachment_fields_output( $post, $mao = null ) {

	    ob_start();
        ?>
            <input type='checkbox' name='attachments[<?php echo $post->ID; ?>][municipality_adaptation_override]'
                   id='attachments[<?php echo $post->ID; ?>][municipality_adaptation_override]' <?php echo isset( $mao ) ? 'checked="checked"' : ''; ?> />
        <?php
        return ob_get_clean();

    }


	/**
	 * HTML output fot extra attachment meta fields.
	 *
	 * @param $post
	 * @param null $ma
	 *
	 * @return string
	 */
    private function attachment_media_fields_output( $post, $ma = null ) {

	    $ma_array = array();
	    if ( ! empty( $ma ) ) {
		    $ma_array = explode( ',', $ma );
        }


	    ob_start();
        ?>
        <div style="width: 100%;">
            <input type='checkbox' value='sundsvall'
                   name='attachments[<?php echo $post->ID; ?>][municipality_adaptation][]'
                   id='attachments[<?php echo $post->ID; ?>][municipality_adaptation_sundsvall]' <?php if ( in_array( 'sundsvall', $ma_array ) ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo "attachments[{$post->ID}][municipality_adaptation_sundsvall]"; ?>"><?php _e( 'Sundsvall', 'msva' ); ?></label>
        </div>
        <div style="width: 100%;">
            <input type='checkbox' value='timra'
                name='attachments[<?php echo $post->ID; ?>][municipality_adaptation][]'
                id='attachments[<?php echo $post->ID; ?>][municipality_adaptation_timra]' <?php if ( in_array( 'timra', $ma_array ) ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo "attachments[{$post->ID}][municipality_adaptation_sundsvall]"; ?>"><?php _e( 'Timrå', 'msva' ); ?></label>
        </div>
        <div style="width: 100%;">
            <input type='checkbox' value='nordanstig'
                   name='attachments[<?php echo $post->ID; ?>][municipality_adaptation][]'
                   id='attachments[<?php echo $post->ID; ?>][municipality_adaptation_nordanstig]' <?php if ( in_array( 'nordanstig', $ma_array ) ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo "attachments[{$post->ID}][municipality_adaptation_nordanstig]"; ?>"><?php _e( 'Nordanstig', 'msva' ); ?></label>
        </div>
        <?php
        return ob_get_clean();

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
	 * Get chosen municipalities meta for a post
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $post_id  integer
	 *
	 * @return array    the chosen municipalities
	 */
	public static function post_municipalities( $post_id ) {

		if( ! $municipalities = self::top_parent_municipalities( $post_id ) ) {

			$municipalities = get_post_meta( $post_id, 'municipality_adaptation', true );

		} else {

			if ( self::override_parent( $post_id ) ) {
				$municipalities = get_post_meta( $post_id, 'municipality_adaptation', true );
			}

		}

		return self::municipalities_to_array( $municipalities );

	}


	/**
	 * Get chosen municipalities meta for a post also
	 * considering the top parent municipalities for the post.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param $post_id  integer
	 *
	 * @return array    the chosen municipalities
	 */
	public static function top_parent_municipalities( $post_id ) {

		$parents = get_post_ancestors( $post_id );

		if( ! is_array( $parents ) || count( $parents ) == 0 ) return false;

		// Loop post parents to check for adaptations
		foreach( $parents as $parent_id ) {

			$post_meta = get_post_meta( $parent_id, 'municipality_adaptation', true );

			if ( ! empty( $post_meta ) ) return $post_meta;

		}

		return null;

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