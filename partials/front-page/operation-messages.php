<?php $messages = SK_Operation_Messages::messages(); ?>

<div class="operation-messages">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9">
				<div class="operation-messages-list">
					<ul>
						<?php if ( ! empty( $messages ) ) : ?>
							<?php foreach ( $messages as $message ) :
									$type_of_action = $message->meta['om_event'][0];
									if( $message->meta['om_event'][0] === '1' ){
										$type_of_action = $message->meta['om_custom_event'][0];
									}

								?>
								<li class="active">
									<a href="<?php echo get_permalink( $message->ID ); ?>"
									   title="<?php echo ! empty( $type_of_action ) ? $type_of_action . ', ' : null; ?><?php echo ! empty( $message->post_title ) ? $message->post_title . ':' : null; ?>">
										<?php material_icon( 'error', array( 'size' => '2em' ) ); ?>
										<span class="operation-messages-list__om-event">
									<?php echo ! empty( $type_of_action ) ? $type_of_action . ', ' : null; ?>
								</span>
										<span class="operation-messages-list__om-area-street">
									<?php echo ! empty( $message->post_title ) ? $message->post_title . ':' : null; ?>
								</span>
										<span class="operation-messages-list__om-info">
									<?php echo ! empty( $message->meta['om_information_part_1'][0] ) ? $message->meta['om_information_part_1'][0] : null; ?>
								</span>
									</a>
								</li>

							<?php endforeach; ?>
						<?php else : ?>

							<li class="done"><?php material_icon( 'error', array( 'size' => '2em' ) ); ?><?php _e( 'Vi har för
							närvarande inga driftstörningar.', 'msva' ); ?>
							</li>

						<?php endif; ?>
					</ul>
				</div><!-- .operation-messages-list -->
			</div>
			<div class="col-md-3">
				<div class="operation-messages__button">
					<a class="btn btn-secondary btn-rounded btn-rounded"
					   href="<?php echo get_bloginfo( 'url' ) . '/driftstorningar/'; ?>"
					   title="<?php _e( 'Arkiv driftstörningar', 'sk_tivoli' ); ?>"><?php _e( 'Arkiv driftstörningar', 'sk_tivoli' ); ?></a>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</div><!-- .operation-messages -->