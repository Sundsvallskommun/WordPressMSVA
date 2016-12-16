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
<?php //$temp = SK_Operation_Messages::messages( array( 'publish' ), true );

//util::Debug( $temp );
?>
<div class="operation-messages">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">

				<div class="operation-messages-list">
					<ul>
						<li class="active"><?php material_icon( 'error', array( 'size' => '2em' ) ); ?> Driftstörning: Hela vårt vatten och avloppsnät och närliggande områden ...</li>
						<li class="done"><?php material_icon( 'error', array( 'size' => '2em' ) ); ?> Vi har för
							närvarande inga driftstörningar
						</li>
					</ul>
				</div><!-- .operation-messages-list -->
			</div>
			<div class="col-md-4">
				<div class="operation-messages__button">
					<a class="btn btn-secondary btn-rounded btn-rounded" href="#"><?php _e( 'Gå till alla driftmeddelanden', 'sk_tivoli' );?></a>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</div><!-- .operation-messages -->


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

		<div class="col-md-6 block-grid-border">klistrad nyhet ...
			</div>

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
