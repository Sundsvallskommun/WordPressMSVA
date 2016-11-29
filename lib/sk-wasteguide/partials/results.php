<?php
global $sk_search, $wpdb;


if ( isset( $_GET['search_wasteguide'] ) && ! empty( $_GET['search_wasteguide'] ) ) {
	$title   = $_GET['search_wasteguide'];
	$sql     = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_title LIKE '%s' AND post_type='material'", '%' . $title . '%' );
	$results = $wpdb->get_results( $sql );
}
?>
<div class="wasteguide-results">
	<?php if ( isset( $results ) ) : ?>

		<div class="wasteguide-search-result">

			<div class="wasteguide-search-result-header">
				<!--<h2><?php echo $result['title']; ?></h2>-->
				<div class="post-count">
					<?php printf( '<span class="post-count__total">%d %s</span>', count( $results ), count( $results ) === 1 ? __( 'träff', 'msva' ) : __( 'träffar', 'msva' ) ); ?>
				</div>
			</div>

			<?php if ( count( $results ) ): ?>
				<?php foreach ( $results as $post ): ?>

					<?php
					$sorting_terms = wp_get_post_terms( $post->ID, 'material_sorting' );
					$leave_terms   = wp_get_post_terms( $post->ID, 'material_deposit' );
					?>
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><?php echo $post->post_title; ?></h3>
						</div>
						<div class="card-block">
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

							<?php if ( ! empty( $leave_terms ) ) : ?>
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
							<?php endif; ?>
							<div class="material-content">
								<?php echo apply_filters( 'the_content', $post->post_content );; ?>
							</div>
						</div><!-- .card-block -->
					</div><!-- .card -->
				<?php endforeach; // end post loop ?>


			<?php else: ?>
				<p class="m-t-2 text-xs-center text-muted"><?php _e( 'Inget resultat för', 'msva' ); ?><?php echo $result['title']; ?></p>
			<?php endif; ?>

		</div><!-- .wasteguide-search-result -->

	<?php endif; ?>
</div><!-- .wasteguide-results -->


