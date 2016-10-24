<?php

global $sk_search;

$results = $sk_search->get_search_results();
$pagenum = get_query_var('paged', 1);

$module_i = 0;
$module_count = count($results);

echo '<div class="col-lg-12">';

foreach ($results as $type => $result): ?>

<?php
	//echo '<pre>' . print_r( $result['found_posts'], true ) . '</pre>';
	if( $result['found_posts'] > 0 ) {

		if ( $result['posts'][0]['type'] != 'material' ) {
			continue;
		}

	} else {

		continue;

	}
	$module_i += 1;

	if($module_i == ceil($module_count / 2) + 1) {
		echo '</div>';
		echo '<div class="col-lg-6">';
	}


?>

	<div class="wasteguide-search-result">

		<div class="search-module__header">
		<h2 class="search-module__title"><?php echo $result['title']; ?></h2>
			<div class="post-count">
			<?php
				$posts_per_page = $sk_search->posts_per_page;
				$count = $posts_per_page < count($result['posts']) ? $posts_per_page : $wp_query->post_count;

				$count = count($result['posts']);

				if($pagenum > 1 && $wp_query->found_posts >= $posts_per_page * $pagenum - $posts_per_page) {
					$startnum = $count * ( $pagenum - 1 ) + 1;
					$count = $startnum.'-'.($startnum + $count-1);
				}
				printf('Visar <span class="post-count__count">%s</span> av <span class="post-count__total">%d</span>', $count, $result['found_posts']);
			?>
			</div>
		</div>

		<?php if ( count($result['posts']) ): ?>

		<ol class="search-module__items">

		<?php foreach ($result['posts'] as $post): ?>
			<li>
			<?php
				$post_data = get_post( $post['id'] );
				$sorting_terms = wp_get_post_terms( $post['id'], 'material_sorting' );
				$leave_terms = wp_get_post_terms( $post['id'], 'material_deposit' );

				?>

				<!-- <h3 class="search-module__item__title"><?php echo $post_data->post_title; ?></h3> -->



				<div class="card">
					<div class="card-header" role="tab" id="headingOne">
						<h5 class="mb-0">
							<?php echo $post_data->post_title; ?>
						</h5>
					</div>

					<div id="collapseOne" class="collapse in" role="tabpanel" aria-labelledby="headingOne">
						<div class="card-block">

							<div class="">
								<h6>Sorteras som...</h6>
								<div class="">
									<?php foreach( $sorting_terms as $term ) : ?>
										<strong><?php echo $term->name; ?></strong>
										<?php echo get_field( 'beskrivning', $term ); ?>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="">
								<h6>Lämnas till...</h6>
								<div class="">
									<?php
									foreach( $leave_terms as $term ) {
										?>
										<strong><?php echo $term->name; ?></strong>
										<?php
										echo get_field( 'beskrivning', $term );
									}
									?>
								</div>
							</div>

						</div>
					</div>
				</div>



				<!--<div class="">
					<div class="" style="">Sorteras som...</div>
					<div class="">
						<?php /*foreach( $sorting_terms as $term ) : */?>
							<div class=""><?php /*echo $term->name; */?></div>
							<?php /*echo get_field( 'beskrivning', $term ); */?>
						<?php /*endforeach; */?>
					</div>
				</div>
				<div class="">
					<div class="">Lämnas till...</div>
					<div class="">
						<?php
/*						foreach( $leave_terms as $term ) {
							*/?>
							<div class=""><?php /*echo $term->name; */?></div>
							<?php
/*							echo get_field( 'beskrivning', $term );
						}
						*/?>
					</div>
				</div>-->
			</li>
		<?php endforeach; ?>

		</ol>

			<div class="search-module__footer" data-load-more="<?php echo $type; ?>">
				<?php if( $result['found_posts'] > $count ) :
				next_posts_link( 'Visa fler', 0 );
				endif;
			?>
			</div>

		<?php else: ?>
			<p class="m-t-2 text-xs-center text-muted">Inget resultat för <?php echo $result['title']; ?></p>
		<?php endif; ?>

	</div>

<?php endforeach; ?>

<?php echo '</div>'; ?>
