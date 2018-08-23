<?php
/**
 * Template Name: Custom RSS Template - News
 */
?>
<?php
if ( isset( $_GET['parallel_type'] ) && $_GET['parallel_type'] === 'operation-messages' ) :
	$items = SK_Operation_Messages::messages(array('publish'), true, false, false);
	header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
	echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
	?>
	<rss version="2.0"
	     xmlns:content="http://purl.org/rss/1.0/modules/content/"
	     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	     xmlns:dc="http://purl.org/dc/elements/1.1/"
	     xmlns:atom="http://www.w3.org/2005/Atom"
	     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		<?php do_action( 'rss2_ns' ); ?>>
		<channel>
			<title><?php bloginfo_rss( 'name' ); ?> - Parallellpublicering driftmeddelanden</title>
			<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml"/>
			<link><?php bloginfo_rss( 'url' ) ?></link>
			<description></description>
			<lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
			<language><?php echo get_option( 'rss_language' ); ?></language>
			<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
			<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
			<?php //do_action('rss2_head'); ?>
			<?php foreach ($items as $post ) : setup_postdata( $post );

				$municipality = get_post_meta( get_the_ID(), 'municipality_adaptation', true );
				$municipality = ! empty( $municipality ) ? $municipality : '';
				?>
				<item>
					<title><?php the_title_rss(); ?> <?php echo $post->ID; ?></title>
					<link><?php the_permalink_rss(); ?></link>
					<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
					<dc:creator><?php the_author(); ?></dc:creator>
					<guid isPermaLink="false"><?php the_guid(); ?></guid>
					<event>
						<?php
						$type_of_action = isset( $post->meta['om_event'][0] ) ? $post->meta['om_event'][0] : null;
						if( isset( $post->meta['om_event'][0] ) && $post->meta['om_event'][0] === '1' ){
							$type_of_action = $post->meta['om_custom_event'][0];
						}
						?>
						<action><?php echo !empty( $type_of_action ) ? $type_of_action : null; ?></action>
						<municipality><?php echo !empty( $post->meta['om_municipality'][0] ) ? $post->meta['om_municipality'][0] : null; ?></municipality>
						<title><?php echo !empty( $post->meta['om_title'][0] ) ? htmlspecialchars( $post->meta['om_title'][0] ) : null; ?></title>
						<address><?php echo !empty( $post->meta['om_area_street'][0] ) ? htmlspecialchars( $post->meta['om_area_street'][0] ) : null; ?></address>
						<info><?php echo !empty( $post->meta['om_information_part_1'][0] ) ? htmlspecialchars( $post->meta['om_information_part_1'][0] ): null; ?></info>
						<more><?php echo !empty( $post->meta['om_information_part_2'][0] ) ? htmlspecialchars( $post->meta['om_information_part_2'][0] ): null; ?></more>
					</event>

					<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
					<?php if ( ! empty( $municipality ) ) : ?>
						<municipality><![CDATA[<?php echo $municipality; ?>]]></municipality>
					<?php else : ?>
						<municipality></municipality>
					<?php endif; ?>
					<content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
					<?php rss_enclosure(); ?>
					<?php do_action( 'rss2_item' ); ?>
				</item>
			<?php wp_reset_postdata( $post ); endforeach; ?>
		</channel>
	</rss>
<?php else :
	$posts = query_posts(
		array(
			'showposts'   => - 1,
			'post_status' => 'publish',
			'meta_query'  => array(
				array(
					'key'     => 'publicera_pa_intranatet',
					'value'   => true,
					'type'    => 'BOOLEAN',
					'compare' => '='
				)
			)
		)
	);
	header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
	echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
	?>
	<rss version="2.0"
	     xmlns:content="http://purl.org/rss/1.0/modules/content/"
	     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	     xmlns:dc="http://purl.org/dc/elements/1.1/"
	     xmlns:atom="http://www.w3.org/2005/Atom"
	     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		<?php do_action( 'rss2_ns' ); ?>>
		<channel>
			<title><?php bloginfo_rss( 'name' ); ?> - Parallellpublicering</title>
			<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml"/>
			<link><?php bloginfo_rss( 'url' ) ?></link>
			<description></description>
			<lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
			<language><?php echo get_option( 'rss_language' ); ?></language>
			<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
			<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
			<?php //do_action('rss2_head'); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				$postimages   = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
				$postimage    = ( $postimages ) ? $postimages[0] : '';
				$municipality = get_post_meta( get_the_ID(), 'municipality_adaptation', true );
				$municipality = ! empty( $municipality ) ? $municipality : '';
				?>

				<item>
					<title><?php the_title_rss(); ?></title>
					<link><?php the_permalink_rss(); ?></link>
					<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
					<dc:creator><?php the_author(); ?></dc:creator>
					<guid isPermaLink="false"><?php the_guid(); ?></guid>
					<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
					<image>
						<url><?php echo esc_url( $postimage ); ?></url>
					</image>
					<?php if ( ! empty( $municipality ) ) : ?>
						<municipality><![CDATA[<?php echo $municipality; ?>]]></municipality>
					<?php else : ?>
						<municipality></municipality>
					<?php endif; ?>
					<content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
					<?php rss_enclosure(); ?>
					<?php do_action( 'rss2_item' ); ?>
				</item>
			<?php endwhile; ?>
		</channel>
	</rss>
<?php endif; ?>