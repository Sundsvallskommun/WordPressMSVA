<div class="container-fluid wasteguide-results">
	<div class="row">
		<div class="alert alert-success alert-centered" role="alert">
			<?php _e('Visar alla resultat i sorteringsguiden för:', 'msva' ); ?> ”<?php echo esc_html( SK_Wasteguide_Search::get_search_string() ); ?>"
		</div>
	</div>
	<div class="row search-modules-row">
		<?php get_template_part('lib/sk-wasteguide/partials/results'); ?>
	</div> <?php //.row ?>
</div> <?php //.container-fluid ?>
