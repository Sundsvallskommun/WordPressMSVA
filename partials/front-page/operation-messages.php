<?php $messages = SK_Operation_Messages::messages( array( 'publish' ), true ); ?>

<div class="operation-messages">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9">

				<div class="operation-messages-list">
					<ul>
						<?php if(!empty($messages)) : ?>
						<?php foreach ($messages as $message ) : ?>
							<li class="active">
								<a href="<?php echo get_permalink( $message->ID ); ?>" title="<?php echo !empty( $message->meta['om_event'][0] ) ? $message->meta['om_event'][0]. ', '  : NULL; ?><?php echo !empty( $message->meta['om_area_street'][0] ) ? $message->meta['om_area_street'][0] . ':' : NULL; ?>">
								<?php //util::Debug( $message ); ?>
								<?php material_icon( 'error', array( 'size' => '2em' ) ); ?>
								<span class="operation-messages-list__om-event">
									<?php echo !empty( $message->meta['om_event'][0] ) ? $message->meta['om_event'][0]. ', '  : NULL; ?>
								</span>
								<span class="operation-messages-list__om-area-street">
									<?php echo !empty( $message->meta['om_area_street'][0] ) ? $message->meta['om_area_street'][0] . ':' : NULL; ?>
								</span>
								<span class="operation-messages-list__om-info">
									<?php echo !empty( $message->meta['om_information_part_1'][0] ) ? $message->meta['om_information_part_1'][0] : NULL; ?>
								</span>
								</a>
							</li>

						<?php endforeach; ?>
						<?php else : ?>

						<li class="done"><?php material_icon( 'error', array( 'size' => '2em' ) ); ?> <?php _e('Vi har för
							närvarande inga driftstörningar.', 'msva' );?>
						</li>

						<?php endif; ?>
					</ul>
				</div><!-- .operation-messages-list -->
			</div>
			<div class="col-md-3">
				<div class="operation-messages__button">
					<a class="btn btn-secondary btn-rounded btn-rounded" href="<?php echo get_bloginfo('url') . '/driftstorningar/';?>" title="<?php _e( 'Arkiv driftstörningar', 'sk_tivoli' );?>"><?php _e( 'Arkiv driftstörningar', 'sk_tivoli' );?></a>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</div><!-- .operation-messages -->