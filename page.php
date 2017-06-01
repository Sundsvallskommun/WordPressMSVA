<?php sk_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php if( SK_Municipality_Adaptation::page_access( get_the_ID() ) ) : ?>

		<div class="container-fluid">

			<div class="single-post__row">

				<aside class="sk-sidebar single-post__sidebar">

					<a href="#post-content" class="focus-only"><?php _e('Hoppa över sidomeny', 'sk_tivoli'); ?></a>

					<?php do_action('sk_page_helpmenu'); ?>

				</aside>

				<div class="single-post__content" id="post-content">

					<?php do_action('sk_before_page_title'); ?>

					<h1 class="single-post__title"><?php the_title(); ?></h1>

					<?php do_action('sk_after_page_title'); ?>

					<?php do_action('sk_before_page_content'); ?>

					<?php the_content(); ?>

					<div class="clearfix"></div>

					<?php do_action('sk_after_page_content'); ?>


				</div>

			</div> <?php //.row ?>

		</div> <?php //.container-fluid ?>

		<?php else : ?>

		<?php echo SK_Municipality_Adaptation::municipality_no_access_markup(); ?>

	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
