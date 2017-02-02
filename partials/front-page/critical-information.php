<?php $criticals = SK_Critical_Information::output(); ?>
<?php if ( ! empty( $criticals ) ) : ?>
	<?php foreach ( $criticals as $critical ) : ?>
		<div class="critical-information">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-block">
								<h1><?php echo wpautop( $critical->post_title ); ?></h1>
								<?php echo wpautop( $critical->post_content ); ?>
							</div>
						</div>
					</div>
				</div><!-- .row -->
			</div><!-- .container-fluid -->
		</div><!-- .critical-information -->
	<?php endforeach; ?>
<?php endif; ?>