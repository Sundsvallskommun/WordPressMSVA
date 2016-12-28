<section class="news">
	<h3 class="front-page__heading"><?php _e('Nyheter', 'sk_tivoli')?></h3>
	<div class="news-slider">
	<ul class="list-unstyled widget-latest-news">

<?php
		$posts_category = get_field( 'news_category', get_option( 'page_on_front' ) );
		$sticky = get_option( 'sticky_posts' );
		$latest_posts = get_posts( array(
			'posts_per_page' => 3,
			'category' => $posts_category,
			'exclude' => $sticky
		) );

		foreach ($latest_posts as $post) : setup_postdata( $post );
		?>

			<li class="media widget-latest-news__post archive-post">
				<a href="<?php the_permalink(); ?>">

					<div class="media-body">
						<h4 class="media-heading">
							<?php the_title(); ?>
						</h4>
						<div class="archive-post__date">
							<?php printf('%s, %s', get_the_date(), get_the_time()); ?>
						</div>
						<div class="archive-post__excerpt hidden-sm-up">
							<?php echo get_the_excerpt(); ?>

						</div>
					</div>

					<?php if(has_post_thumbnail()): ?>
						<div class="media-left">
							<div class="large">
								<?php the_post_thumbnail('news-thumb-medium'); ?>
							</div>
							<div class="small">
								<?php the_post_thumbnail('news-thumb-small'); ?>
							</div>
						</div>
					<?php //else: ?>
						<!--
						<div class="media-left">
							<div class="img-placeholder">
							</div>
						</div>
						-->
					<?php endif; ?>

				</a>
			</li>

		<?php
		endforeach;
		wp_reset_postdata();
		?>

	</ul>

	<div class="slider-controls hidden-md-up">
		<button id="prevslide" class="btn btn-link"><?php the_icon('arrow-right-circle')?></button>
		<button id="nextslide" class="btn btn-link"><?php the_icon('arrow-right-circle')?></button>
	</div>
	</div>

<?php
		$all_posts_page = get_option( 'page_for_posts' );
?>

	<?php /* <a href="<?php echo get_permalink( $all_posts_page ); ?>" class="btn btn-purple btn-action"><?php the_icon('arrow-right'); ?> Alla nyheter </a> */ ?>
	<a class="btn btn-secondary btn-rounded btn-rounded btn-block" href="<?php echo get_permalink( $all_posts_page ); ?>">Visa alla nyheter</a>

</section>
