<?php /* Template Name: Skapa driftmeddelande */ ?>
<?php acf_form_head(); ?>
<?php sk_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php if ( SK_Municipality_Adaptation::page_access( get_the_ID() ) ) : ?>

        <div class="container-fluid">

            <div class="single-post__row">

                <aside class="sk-sidebar single-post__sidebar">

                    <a href="#post-content" class="focus-only"><?php _e( 'Hoppa över sidomeny', 'sk_tivoli' ); ?></a>

					<?php do_action( 'sk_page_helpmenu' ); ?>

                </aside>

                <div class="single-post__content" id="post-content">

					<?php do_action( 'sk_before_page_title' ); ?>

                    <h1 class="single-post__title"><?php the_title(); ?></h1>

					<?php do_action( 'sk_after_page_title' ); ?>

					<?php do_action( 'sk_before_page_content' ); ?>

					<?php the_content(); ?>

                    <div class="clearfix"></div>

                    <div class="row">

                        <form id="operation-message-form" method="post" action="">

                            <input type="hidden" value="<?php echo wp_create_nonce( 'sk-operation-messages' ); ?>" name="nonce" />

                            <div class="form-group">

                                <h3><?php _e( 'Händelse', 'msva' ); ?></h3>

                                <select class="form-control" name="operation_message[event]">
                                    <option value="0"><?php _e( 'Välj händelse...', 'msva' ); ?></option>
                                    <option value="Vattenläcka"><?php _e( 'Vattenläcka', 'msva' ); ?></option>
                                    <option value="Vattenavstängning"><?php _e( 'Vattenavstängning', 'msva' ); ?></option>
                                    <option value="Spolning av avloppsledningar"><?php _e( 'Spolning av avloppsledningar', 'msva' ); ?></option>
                                    <option value="Spolning av vattenledningar"><?php _e( 'Spolning av vattenledningar', 'msva' ); ?></option>
                                    <option value="Vattenläcka åtgärdad"><?php _e( 'Vattenläcka åtgärdad' ); ?></option>
                                    <option value="1"><?php _e( 'Egen händelse', 'msva' ); ?></option>
                                </select>


                            </div>

                            <button type="button" id="operation-message-form-submit"
                                    class="btn btn-secondary float-xs-right"><?php _e( 'Spara driftmeddelande', 'msva' ); ?></button>

                        </form>

                    </div>

                    <div class="clearfix"></div>

					<?php do_action( 'sk_after_page_content' ); ?>


                </div>

            </div> <?php //.row ?>

        </div> <?php //.container-fluid ?>

	<?php else : ?>

		<?php echo SK_Municipality_Adaptation::municipality_no_access_markup(); ?>

	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>

