<?php
	$region_selector['title'] = get_field( 'msva_region_selector_title', 'options');
	$region_selector['content'] = get_field( 'msva_region_selector_content', 'options');
	$has_logo = function_exists( 'the_custom_logo' ) && has_custom_logo();
?>
<div id="set-region" class="overlay">
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="region-selector">
					<div class="shadow"></div>
					<h1><?php echo $region_selector['title']; ?></h1>
					<div class="content"><p><?php echo $region_selector['content']; ?></p></div>
					<div class="actions">
						<ul>
							<li><a class="region-selected btn btn-secondary btn-rounded btn-rounded" data-region="sundsvall" data-url="<?php echo get_permalink(); ?>" href="#">Sundsvall</a></li>
							<li><a class="region-selected btn btn-secondary btn-rounded btn-rounded" data-region="nordanstig" data-url="<?php echo get_permalink(); ?>" href="#">Nordanstig</a></li>
							<li><a class="region-selected btn btn-secondary btn-rounded btn-rounded" data-region="timra" data-url="<?php echo get_permalink(); ?>" href="#">Timrå</a></li>
						</ul>
					</div><!-- .actions -->

					<div class="logotype">
						<?php
						if ( $has_logo ) {
							the_custom_logo();
						} else {
							echo '<a href="'.get_bloginfo('url').'">';
							printf( '<span class="site-title">%s</span>', get_bloginfo( 'name' ) );
							echo '</a>';
						}
						?>
					</div><!-- .logotype -->
				</div><!-- .region-selector -->
			</div><!-- .col -->
		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- #set-region -->

<script>
	function openNav() {
		document.getElementById("set-region").style.height = "100%";
		document.getElementsByTagName('body')[0].className+=' overlay-popup';
	}

	function closeNav() {
		document.getElementById("set-region").style.height = "0%";
	}
</script>