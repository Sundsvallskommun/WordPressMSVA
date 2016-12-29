<?php sk_header(); ?>
<?php

require_once locate_template( 'lib/sk-operation-messages/class-sk-operation-messages-post.php' );
$message = SK_Operation_Messages_Post::fromId( get_the_ID() );

?>
<?php while ( have_posts() ) : the_post(); ?>

    <div class="container-fluid">

        <div class="single-post__row">

            <aside class="sk-sidebar single-post__sidebar">

                <a href="#post-content" class="focus-only"><?php _e( 'Hoppa över sidomeny', 'sk_tivoli' ); ?></a>

				<?php do_action( 'sk_page_helpmenu' ); ?>

            </aside>

            <div class="single-post__content" id="post-content">

				<?php do_action( 'sk_before_page_title' ); ?>

                <h1 class="single-post__title"><?php echo $message->event_title(); ?>, <?php echo $post->post_title; ?></h1>

				<?php do_action( 'sk_after_page_title' ); ?>

				<?php do_action( 'sk_before_page_content' ); ?>



                <div class="operation-message-wrapper">

                    <div class="om-event">
                        <?php _e( 'Typ av driftstörning: ', 'msva' ); ?>
						<span class="info-text"><?php echo $message->event_title(); ?></span>
                    </div>

                    <div id="om-street" class="om-info">
                        <?php _e( 'Berörda gator/område: ', 'msva' ); ?>
                        <span class="info-text"><?php echo $message->street(); ?></span>
                    </div>

                    <div class="om-info-wrapper">

                        <h3><?php _e( 'Information', 'msva' ); ?></h3>
                        <div id="om-info-1" class="om-info">
							<p><?php echo $message->info_1(); ?><br><b><?php echo $message->street(); ?></b></p>

                        </div>

                        <div id="om-info-2" class="om-info">
							<p><?php echo wpautop( $message->info_2() ); ?></p>
                        </div>

                        <div id="om-ending" class="om-info">
							<?php echo $message->ending(); ?>
                        </div>

                    </div>

                </div>

                <div class="clearfix"></div>

				<?php do_action( 'sk_after_page_content' ); ?>

            </div>

        </div> <?php //.row ?>

    </div> <?php //.container-fluid ?>

<?php endwhile; ?>

<?php get_footer(); ?>
