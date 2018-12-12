<?php if( SK_Info_Banner::is_active() ) : ?>
<?php $banner = SK_Info_Banner::get_data(); ?>
		<div class="info-banner" <?php echo !empty( $banner['bg_color'] ) ? 'style="background-color: '.$banner['bg_color'].'"' : null; ?>>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php echo isset( $banner['content'] ) ? $banner['content'] : null; ?>
					</div>
				</div><!-- .row -->
			</div><!-- .container-fluid -->
		</div><!-- .info-banner -->
<?php endif;?>
