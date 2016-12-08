<?php

/**
 * Class for wrapping shortcode functionality
 *
 * @package    SK_Ongoing_Projects
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */


class SK_Ongoing_Projects_Shortcode {


	/**
	 * Register the shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function register_shortcode() {

		add_shortcode( 'ongoing_projects', array( $this, 'callback' ) );

	}


	/**
	 * The callback for the shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array     the shortcode attributes
	 *
	 * @return the shortcode html output
	 *
	 */
	public function callback( $atts = array() ) {
		$attributes = shortcode_atts(
			array(
			'group' => 'all'
			),
			$atts
		);


		$projects = SK_Ongoing_Projects::projects( $attributes['group'] );

		ob_start();
		?>

		<?php if ( count( $projects ) > 0 && is_array( $projects ) ) : ?>
			<ul>
				<?php foreach ( $projects as $project ) : ?>
				<li>
					<a href="<?php echo get_permalink( $project->ID ); ?>"><?php echo $project->post_title; ?></a>
				</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>

			<p><?php _e( 'Inga pågående arbeten just nu.', 'msva' ); ?></p>

		<?php endif; ?>

		<?php
		return ob_get_clean();

	}

}