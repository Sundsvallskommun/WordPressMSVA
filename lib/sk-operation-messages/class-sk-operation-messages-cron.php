<?php

/**
 * This class wraps all logic handling the cron jobs
 * for operation messages.
 *
 * @package    SK_Operation_Messages
 * @author     Andreas Färnstrand <andreas.farnstrand@cybercom.com>
 */
class SK_Operation_Messages_Cron {

	/**
	 * Setup new schedule for cron jobs.
	 * Adds two new cronjobs for archiving messages
	 * and unpublishing messages.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct() {

		// Modify cron schedules
		add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );

		// Register hooks
		add_action( 'unpublish_old_operation_messages', array( $this, 'unpublish_archived_messages_callback' ) );
		add_action( 'archive_operation_messages', array( $this, 'archive_messages_callback' ) );

		// Schedule unpublishing of archived oerpation messages
		if ( ! wp_next_scheduled( 'unpublish_old_operation_messages' ) ) {
			wp_schedule_event( time(), 'hourly', 'unpublish_old_operation_messages' );
		}

		// Archive operation messages
		if ( ! wp_next_scheduled( 'archive_operation_messages' ) ) {
			wp_schedule_event( time(), 'fifteenminutes', 'archive_operation_messages' );
		}

	}


	/**
	 * Callback for cron job. Archives messages when scheduled
	 * for archiving.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function archive_messages_callback() {

		$messages = SK_Operation_Messages::messages( array( 'publish' ), true );

		if ( isset( $messages ) && is_array( $messages ) ) {

			foreach ( $messages as $message ) {

				if ( ! empty( $message->meta['om_archive_at_date'][0] ) && ! isset( $message->meta['om_archived_at'] ) ) {

					$current_date_and_time = current_time( 'Y-m-d H:i' );
					$archive_date_and_time = strtotime( $message->meta['om_archive_at_date'][0] . ' ' . $message->meta['om_archive_at_hour'][0] . ':' . $message->meta['om_archive_at_minute'][0] );

					if ( strtotime( $current_date_and_time ) >= $archive_date_and_time ) {

						update_post_meta( $message->ID, 'om_archived_at', date( 'Y-m-d H:i', strtotime( $current_date_and_time ) ) );

					}

				}

			}

		}

	}


	/**
	 * Callback for cron job. Unpublishes messages that has been
	 * in the archive for 30 days.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function unpublish_archived_messages_callback() {

		// Get all archived messages
		$messages = SK_Operation_Messages::messages( array( 'publish' ), false, true );

		if ( isset( $messages ) && is_array( $messages ) ) {

			foreach ( $messages as $message ) {

				$post_id = wp_update_post( array(
					'ID'          => $message->ID,
					'post_status' => 'draft'
				) );

			}

		}


	}


	/**
	 * Creates a new cron schedule that runs every
	 * 15 minutes.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function add_cron_interval( $schedules ) {

		if ( ! isset( $schedules['fifteenminutes'] ) ) {

			$schedules['fifteenminutes'] = array(
				'interval' => 60 * 15,
				'display'  => esc_html__( 'Every quarter' ),
			);

		}

		return $schedules;
	}

}