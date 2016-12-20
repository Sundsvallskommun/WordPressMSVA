<?php
/**
 * Archive for operation messages
 */
get_header();
$messages = SK_Operation_Messages::messages();
?>

<div class="container-fluid archive">
	<div class="single-post__row">
		<aside class="sk-sidebar single-post__sidebar">
			<a href="#post-content" class="focus-only"><?php _e( 'Hoppa över sidomeny', 'sk_tivoli' ); ?></a>
			<?php do_action( 'sk_page_helpmenu' ); ?>

		</aside>
		<div class="single-post__content">
			<h1 class="archive__title"><?php _e( 'Arkiv, driftstörningar', 'msva' ); ?></h1>

			<div class="row posts">
				<div class="col-md-12">
					<?php if ( ! empty( $messages ) ) : ?>
						<table class="table">
							<thead>
							<tr>
								<th><?php _e( 'Område', 'msva' ); ?></th>
								<th><?php _e( 'Typ', 'msva' ); ?></th>
								<th><?php _e( 'Kommun', 'msva' ); ?></th>
								<th><?php _e( 'Publicerades', 'msva' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ( $messages as $message ) : ?>
								<tr>
									<td>
										<a href="<?php echo get_permalink( $message->ID ); ?>"><?php echo $message->meta['om_area_street'][0]; ?></a>
									</td>
									<td><?php echo $message->meta['om_event'][0]; ?></td>
									<td><?php echo $message->meta['om_custom_municipality'][0]; ?></td>
									<td><?php echo $message->meta['om_publish_at_date'][0]; ?> <?php echo $message->meta['om_publish_at_hour'][0]; ?>
										:<?php echo $message->meta['om_publish_at_minute'][0]; ?></td>
								</tr>


							<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="infinite-nav">
			<?php get_template_part( 'partials/pagination' ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
