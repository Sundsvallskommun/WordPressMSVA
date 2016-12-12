<?php sk_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

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

				<?php

				if ( current_user_can( 'edit_post' ) ) {

					$external_text = get_post_meta( $post->ID, 'fritext_for_extern_visning', true );
					$internal_text = get_post_meta( $post->ID, 'fritext_for_intern_visning', true );

				} else {

					$internal_only = get_post_meta( $post->ID, 'visa_endast_internt', true );

					if ( get_post_meta( $post->ID, 'visa_endast_internt', true ) ) {

						$external_text = null;
						$internal_text = null;

					} else {

						$external_text = get_post_meta( $post->ID, 'fritext_for_extern_visning', true );

					}
				}

				?>

				<div class="faq-answer">

					<?php if ( ! empty( $external_text ) ) : ?>
						<div class="faq-external-answer">
							<?php echo $external_text ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $internal_text ) ) : ?>
						<div class="faq-internal-answer">
							<h3><?php _e( 'Internt', 'msva' ); ?></h3>
							<?php echo $internal_text ?>
						</div>
					<?php endif; ?>

				</div>

				<div class="clearfix"></div>

				<?php do_action('sk_after_page_content'); ?>

			</div>

		</div> <?php //.row ?>

	</div> <?php //.container-fluid ?>

<?php endwhile; ?>

<?php get_footer(); ?>
