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
                    <div class="wasteguide-results">
                    <?php
					$sorting_terms = wp_get_post_terms( $post->ID, 'material_sorting' );
					$leave_terms   = wp_get_post_terms( $post->ID, 'material_deposit' );
					?>
					<div class="card">
						<div class="card-block">
							<?php if ( ! empty( $sorting_terms ) ) : ?>
								<div class="card-inner sort-as">
									<div class="row">
										<div class="col-md-3">
											<p><?php _e( 'Sorteras som:', 'msva' ); ?></p>
										</div>
										<div class="col-md-9">
											<?php foreach ( $sorting_terms as $term ) : ?>
												<strong><?php echo $term->name; ?></strong>
												<?php echo get_field( 'beskrivning', $term ); ?>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
							<?php endif; ?>


							<?php if ( ! empty( $leave_terms ) ) : ?>
								<div class="card-inner">
									<?php foreach ( $leave_terms as $key => $term ) : ?>

										<div class="row">
											<div class="col-md-3">
												<p><?php $key === 0 ? _e( 'Lämnas till:', 'msva' ) : _e( '...eller till:', 'msva' ); ?></p>
											</div>
											<div class="col-md-9">
												<strong><?php echo $term->name; ?></strong>
												<?php echo get_field( 'beskrivning', $term ); ?>
											</div>
										</div>

									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div><!-- .card-block -->
                    </div><!-- .card -->
                    </div>

                    <div class="clearfix"></div>
                    <br>
                    <a href="<?php echo get_home_url(); ?>/sorteringsguiden/">
                        <button class="btn btn-secondary">Klicka här för att söka på mer saker i våran sorteringsguide</button>
                    </a>

					<?php do_action('sk_after_page_content'); ?>


				</div>

			</div> <?php //.row ?>

		</div> <?php //.container-fluid ?>

		<?php else : ?>

		<?php echo SK_Municipality_Adaptation::municipality_no_access_markup(); ?>

	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
