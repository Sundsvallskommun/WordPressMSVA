<?php

/**
 * Class for page template flexible
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
class SK_Blocks_Public {

	/**
	 * SK_Blocks_Public constructor.
	 */
	function __construct() {
		$this->init();
	}


	public function init(){

	}


	public function print_section( $section = '' ){
		if( empty ($section ))
			return false;
		?>
		<div class="row">
		<?php foreach ($section['sk-row'] as $row ) : ?>
			<div class="col-md-<?php echo $row['sk-grid']; ?>">
				<?php self::column_content( $row ); ?>
			</div>
			<?php endforeach; ?>
		</div><!-- .row -->
		<?php

		util::debug( $section );


	}

	public static function print_shortcode( $column ) {
		//util::debug( $column );

		if ( intval( $column['sk-grid-border'] ) === 1 ) : ?>
			<div class="sk-grid-border-inner">
				<?php echo do_shortcode( $column['sk-short-code'] );?>
			</div>
		<?php else : ?>
			<?php echo do_shortcode( $column['sk-short-code'] ); ?>
		<?php endif; ?>
		<?php
	}

	public static function print_block( $block_id ){

		$block = get_post( $block_id );

		$type = wp_get_post_terms($block_id, 'block-type', array('fields' => 'slugs'));
		if(empty( $type ))
			return false;

		echo self::get_block( $block_id, $type[0] );


		//util::debug( $block );
	}

	private static function get_block( $block_id = '', $type = '' ){

		switch ($type ) {
			case 'bild':
				echo self::get_block_image( $block_id );
				break;

			case 'bild-och-text':
				echo self::get_block_image_with_text( $block_id );
				break;

			default:
				echo "något har gått fel";
		}


	}


	private static function get_block_image( $block_id = '' ){
		$block = get_post( $block_id );

		/*
		$block->{'block'} = array(
			'image' => get_field( 'sk-blocks-image', $block_id )
		);
		*/

		$image_id = get_field( 'sk-blocks-image', $block_id );
		$image = wp_get_attachment_image_src( $image_id, 'full' );

		ob_start();

		?>

		<div class="block block-image">
			<div class="block-block__image"><img src="<?php echo $image[0];?>"></div>
		</div>

		<?php
		$block = ob_get_clean();

		return $block;

	}


	private static function get_block_image_with_text( $block_id = '' ){
		$block = get_post( $block_id );

		/*
		$block->{'block'} = array(
			'image' => get_field( 'sk-blocks-image', $block_id )
		);
		*/

		$image_id = get_field( 'sk-block-image-and-text', $block_id );
		$image    = wp_get_attachment_image_src( $image_id, 'full' );
		$title    = get_field( 'sk-block-image-and-text-title', $block_id );
		$content  = get_field( 'sk-block-image-and-text-content', $block_id );
		$theme    = get_field( 'sk-block-image-and-text-theme', $block_id );


		$links['internal'] = get_field( 'sk-block-link-internal', $block_id );
		$links['external'] = get_field( 'sk-block-link-external', $block_id );

		$link = $links['internal'];

		if( empty ($link ) ) {
			$link = $links['external'];
		}

		ob_start();
		//util::debug( $link );
		?>

		<div class="block block-image-and-text<?php echo !empty( $theme ) ? ' '.$theme : NULL; ?>">
			<div class="block-block__image"><img src="<?php echo $image[0];?>"></div>
			<div class="block-footer">
				<div class="block-footer__title"><h3><?php echo $title; ?></h3></div>
				<div class="block-footer__content"><?php echo $content; ?></div>
				<?php if( !empty( $link )) : ?>
					<div class="block-footer__link"><a href="<?php echo $link; ?>"><?php _e( 'Läs mer', 'sk-tivoli' );?><?php material_icon( 'keyboard arrow right', array('size' => '1.3em' ) ); ?></a></div>
				<?php endif; ?>
			</div>

		</div>

		<?php
		$block = ob_get_clean();

		return $block;

	}










}
