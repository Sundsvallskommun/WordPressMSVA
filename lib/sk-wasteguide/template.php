<?php sk_header(); ?>

<div class="alert alert-success alert-centered" role="alert">
	Visar alla resultat i sorteringsguiden för: ”<?php echo esc_html( get_search_query() ); ?>"
</div>

<div class="container-fluid wasteguide-results">

	<div class="">
		<?php echo do_shortcode( '[sorteringsguide]' ); ?>
	</div>

	<div class="row search-modules-row">

		<?php get_template_part('lib/sk-wasteguide/partials/results'); ?>

	</div> <?php //.row ?>
</div> <?php //.container-fluid ?>

<?php get_footer(); ?>
