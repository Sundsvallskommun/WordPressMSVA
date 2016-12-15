<?php
/**
 * Template Name: Startsida MSVA
 *
 */

get_header();

$sections = SK_Blocks::get_sections();
?>

<?php // Mobile only ?>
<?php //get_template_part('partials/boxes'); ?>
<style>
	.card{
		text-align: center;
		padding: 70px 0;
	}

</style>




<div class="container-fluid sections">

	<?php foreach ($sections as $section ) : ?>
	<div class="row blocks<?php SK_Blocks::is_grid_border( $section ); ?>">
		<?php foreach ( $section['sk-row'] as $col ) : ?>
			<div class="col-md-<?php echo $col['sk-grid']; ?><?php echo intval( $col['sk-grid-border'] ) === 1 ? ' block-grid-border' : NULL; ?>">
				<?php SK_Blocks::get_block( $col );?>
			</div>
		<?php endforeach; ?>

	</div>
	<?php endforeach; ?>

	<div class="row blocks has-grid">
		<div class="col-md-6 block-grid-border">
			<div class="sk-grid-border-inner">

				<div class="mobile-news" id="news">
					<?php get_template_part('partials/latest-news'); ?>
				</div>

				<div class="clearfix"></div>
			</div>
			</div>

		<div class="col-md-6 block-grid-border">[klistrad nyhet]</div>
	</div>

</div>
<!--
<div style="overflow: hidden;"> <?php //Fix for IE11, service-messages caused horizontal scrollbar ?>

	<div class="container-fluid">

		<div class="row content-news-row">

			<div class="content-col">
				<section class="front-page-section front-page-section__content">
					<?php the_content(); ?>
				</section>
			</div>

			<div class="news-col mobile-news" id="news">
				<?php //get_template_part('partials/latest-news'); ?>
			</div>

			<div class="clearfix"></div>

			<?php get_template_part('partials/front-page/service-messages'); ?>

		</div>

	</div>

</div>
-->
<?php //get_template_part('partials/front-page/calendar'); ?>

<?php get_footer(); ?>
