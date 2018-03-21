<?php
/**
 * Add custom taxonomy for search aliases.
 * Terms are placed in the content as hidden html.
 *
 * Port from sundsvall.se
 *
 * @author      Johan Linder <johan@fmca.se>
 * @modifier    Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */

$posts_to_update_alias = array();

class SK_Search_Alias {

	/**
	 * Constructor.
	 */
	function __construct() {
		$this->init();
	}

	/**
	 * Init method.
	 *
	 */
	public function init() {
		add_action( 'delete_search_alias', [ $this, 'update_posts_with_deleted_alias' ], 10 );
		add_action( 'pre_delete_term', [ $this, 'before_alias_deleted' ], 10 );
		add_action( 'init', [ $this, 'register_taxonomy' ], 10 );
		add_action( 'save_post', [ $this, 'custom_search_data_in_post_content' ], 10 );
	}

	/**
	 * Register the taxonomy to be used for search alias.
	 *
	 */
	public function register_taxonomy() {
		// create a new taxonomy

		$labels = array(
			'name'                       => __( 'Synomymer', 'msva' ),
			'singular_name'              => __( 'Synonym', 'msva' ),
			'search_items'               => __( 'Sök synonymer', 'msva' ),
			'popular_items'              => __( 'Populära synonymer', 'msva' ),
			'all_items'                  => __( 'Alla synonymer', 'msva' ),
			'edit_item'                  => __( 'Ändra synonym', 'msva' ),
			'update_item'                => __( 'Uppdatera synonym', 'msva' ),
			'add_new_item'               => __( 'Lägg till synonym', 'msva' ),
			'new_item_name'              => __( 'Synonym', 'msva' ),
			'separate_items_with_commas' => __( 'Exempel: Socialbidrag är en synonym till ekonomiskt bistånd. Separera synonymer med kommatecken.', 'msva' ),
			'add_or_remove_items'        => __( 'Lägg till eller ta bort synonym', 'msva' ),
			'choose_from_most_used'      => __( 'Välj från de mesta använda synonymerna', 'msva' ),
			'not_found'                  => __( 'Inga synonymer funna.', 'msva' ),
		);

		register_taxonomy(
			'search_alias',
			['page', 'faq'],
			array(
				'labels'  => $labels,
				'rewrite' => array( 'slug' => 'alias' ),
			)
		);
	}

	/**
	 * Add hidden div with searchable data in post content on save.
	 *
	 * @param $post_id
	 */
	public function custom_search_data_in_post_content( $post_id ) {

		$post_type = get_post_type( $post_id );

		// Do not add searchable data on these post types
		if ( $post_type === 'acf-field-group' || $post_type === 'acf-field' ) {
			return;
		}

		$container_id = 'searchdata';

		$content = get_post_field( 'post_content', $post_id );

		// Remove previous search data from post content
		$content = preg_replace( '#<span id="' . $container_id . '" style="display: none;">(.*?)</span>#', '', $content );

		$aliases = wp_get_post_terms( $post_id, 'search_alias' );

		$alias_string = '';

		foreach ( $aliases as $alias ) {
			$alias_string .= ' ' . $alias->name;
		}

		$searchdata_container = sprintf( '<span id="' . $container_id . '" style="display: none;">%s</span>', $alias_string );

		// Insert search in post content.
		$content .= $searchdata_container;

		// Remove action while updating post to prevent infinite loop
		remove_action( 'save_post', array( $this, 'custom_search_data_in_post_content' ) );

		wp_update_post( array( 'ID' => $post_id, 'post_content' => $content ) );

		add_action( 'save_post', array( $this, 'custom_search_data_in_post_content') );
	}

	/**
	 * Update searchdata of pages after alias has been deleted.
	 */
	public function update_posts_with_deleted_alias() {
		global $posts_to_update_alias;

		foreach ( $posts_to_update_alias as $id ) {
			$this->custom_search_data_in_post_content( $id );
		}

	}

	/**
	 * Find posts with alias that are to be deleted before it is deleted.
	 *
	 * @param $term
	 * @param $taxonomy
	 */
	public function before_alias_deleted( $term, $taxonomy ) {

		if ( 'search_alias' !== $taxonomy ) {
			return;
		}

		$query = new WP_Query(
			array(
				'posts_per_page' => - 1,
				'post_type'      => 'page',
				'tax_query'      => array(
					array(
						'taxonomy' => 'search_alias',
						'field'    => 'term_id',
						'terms'    => $term,
					)
				)
			)
		);

		global $posts_to_update_alias;
		$posts_to_update_alias = wp_list_pluck( $query->posts, 'ID' );
	}

}