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
								<?php material_icon( 'error', array( 'size' => '2em' ) ); ?>
								<span class="operation-messages-list__om-event">
									<?php echo !empty( $message->meta['om_event'][0] ) ? $message->meta['om_event'][0] . ':' : NULL; ?>
								</span>
								<span class="operation-messages-list__om-info">
									<?php echo !empty( $message->meta['om_information_part_1'][0] ) ? $message->meta['om_information_part_1'][0] : NULL; ?>
								</span>
							</li>

						<?php endforeach; ?>
						<?php else : ?>

						<li class="done"><?php material_icon( 'error', array( 'size' => '2em' ) ); ?> Vi har för
							närvarande inga driftstörningar
						</li>

						<?php endif; ?>
					</ul>
				</div><!-- .operation-messages-list -->
			</div>
			<div class="col-md-3">
				<div class="operation-messages__button">
					<a class="btn btn-secondary btn-rounded btn-rounded" href="#"><?php _e( 'Gå till alla driftmeddelanden', 'sk_tivoli' );?></a>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</div><!-- .operation-messages -->