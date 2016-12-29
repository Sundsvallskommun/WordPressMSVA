<?php
/**
 * Template Name: Startsida MSVA
 *
 */
global $post;
$start_page_id = SK_Municipality_Adaptation::get_start_page_id();
$region = SK_Municipality_Adaptation_Cookie::value();

if( $start_page_id ) {
	$post_object = get_post( $start_page_id );
	$post        = $post_object;
	setup_postdata( $post_object );
}
$sections = SK_Blocks::get_sections();

get_header();
?>
<?php get_template_part( 'partials/front-page/operation-messages'); ?>
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

<?php
/*
	if( $region === 'nordanstig' ){
		require_once( get_stylesheet_directory() . '/partials/front-page/partials/nordanstig.php');
	}elseif($region === 'timra'){
		require_once( get_stylesheet_directory() . '/partials/front-page/partials/timra.php');
	}else{
		require_once( get_stylesheet_directory() . '/partials/front-page/partials/sundsvall.php');
	}
*/
?>


