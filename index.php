<?php
get_header();
?>

<div class="container-fluid archive">

	<h1 class="archive__title"><?php single_post_title(); ?></h1>

	<?php get_template_part( 'partials/archive-tags' ); ?>

	<div class="row posts">

		<?php if ( have_posts() ): while ( have_posts() ): the_post();
			if ( SK_Municipality_Adaptation::page_access( $post->ID ) ) : ?>

				<div class="col-md-4 col-sm-6">

					<?php get_template_part( 'partials/archive-item' ); ?>

				</div>

			<?php endif; endwhile; endif; ?>

	</div>

	<div class="infinite-nav">
		<?php get_template_part( 'partials/pagination' ); ?>
	</div>

</div>

<?php get_footer(); ?>
