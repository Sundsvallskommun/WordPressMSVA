<?php
	$stickies = get_option( 'sticky_posts' );
	rsort( $stickies );
	$sticky = get_post( $stickies[0] );

	$post_thumbnail_id = get_post_thumbnail_id( $sticky->ID );
	$image = wp_get_attachment_image_src( $post_thumbnail_id, 'content-full' );

?>
<?php if( SK_Municipality_Adaptation::page_access( $stickies[0] ) ) : ?>
<div class="col-md-6 block-grid-border sticky-post-wrapper" style="background-image: url('<?php echo $image[0]; ?>')">
<div class="sticky-post">
	<div class="sticky-post__title"><?php echo get_the_title( $sticky->ID ); ?></div>
	<div class="sticky-post__content"><?php echo wp_trim_words( $sticky->post_content, 16, ' ...' ); ?></div>
	<div class="sticky-post__link"><a href="<?php echo get_permalink( $sticky->ID ); ?>" class="btn btn-secondary btn-rounded"><?php _e('Läs mer', 'msva');?></a></div>

</div><!-- .sticky-post -->
</div>
<?php else : ?>
	<div class="col-md-6 block-grid-border"></div>
	<?php endif; ?>