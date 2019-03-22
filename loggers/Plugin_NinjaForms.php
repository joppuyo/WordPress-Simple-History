<?php

defined( 'ABSPATH' ) || die();

/**
 * Log Ninja Forms submissions
 * https://ninjaforms.com/
 *
 * @package SimpleHistory
 * @since 2.21
 */
if ( ! class_exists( 'Plugin_NinjaForms' ) ) {

	/**
	 * Class for Ninja Forms logging.
	 */
	class Plugin_NinjaForms extends SimpleLogger {

		/**
		 * The slug for this logger.
		 *
		 * @var string $slug
		 */
		public $slug = __CLASS__;

		/**
		 * Get info for this logger.
		 *
		 * @return array Array with info about the logger.
		 */
		public function getInfo() {

			$arr_info = array(
				'name'        => 'Plugin Ninja Forms',
				'description' => _x( 'Log Ninja Forms submissions', 'Logger: Plugin Ninja Forms', 'simple-history' ),
				'name_via'    => _x( 'Using plugin Ninja Forms', 'Logger: Plugin Ninja Forms', 'simple-history' ),
				'capability'  => 'manage_options',
				'messages'    => array(
					'new_submission' => _x( 'New submission to form "{form_title}"', 'Logger: Plugin Ninja Forms', 'simple-history' ),
				)
			);

			return $arr_info;
		}

		private function isNinjaFormsInstalled() {
			return class_exists( 'Ninja_Forms' );
		}

		/**
		 * Method called when logger is loaded.
		 */
		public function loaded() {

			// Bail if no Ninja Forms found.
			if ( ! $this->isNinjaFormsInstalled() ) {
				return;
			}

			// Remove default empty Ninja Forms submissions from the log
			add_filter( 'simple_history/post_logger/skip_posttypes', array( $this, 'remove_nf_from_postlogger' ) );

			// Fired when Ninja Form is submitted. Adds Ninja Forms context to logged row.
			add_filter( 'ninja_forms_after_submission', array( $this, 'on_ninja_forms_submission ' ), 50 );
		}


		/**
		 * Create new log entry when Ninja Forms submission is created.
		 * Called when new Ninja Forms submissions is created.
		 *
		 * @param array $form_metadata .
		 */
		public function on_ninja_forms_submission ( $form_metadata ) {

			error_log( print_r( $form_metadata, true ) );

			$submission_id = null;

			if ( ! empty( $form_metadata['actions'] ) &&
			     ! empty( $form_metadata['actions']['save'] ) &&
			     ! empty( $form_metadata['actions']['save']['sub_id'] ) ) {
				$submission_id = $form_metadata['actions']['save']['sub_id'];
			}

			$this->infoMessage(
				'new_submission',
				[
					'form_title'    => $form_metadata['settings']['title'],
					'submission_id' => $submission_id,
				]
			);
		}

		/**
		 * Don't add default row to the log when Ninja Form is submitted
		 */
		public function remove_nf_from_postlogger( $skip_posttypes ) {
			array_push(
				$skip_posttypes,
				'nf_sub'
			);

			return $skip_posttypes;
		}

		/**
		 * Get output for detailed log section
		 */
		function getLogRowDetailsOutput( $data ) {
			// Null values get serialized to 'null' for some reason?
			if ( property_exists( $data, 'context' ) &&
			     ! empty( $data->context['submission_id'] ) &&
			     $data->context['submission_id'] !== 'null'
			) {
				return '<table class="SimpleHistoryLogitem__keyValueTable">' .
				       '<a href="' . get_edit_post_link( $data->context['submission_id'] ) . '">' .
				       _x( 'View submission', 'Logger: Plugin Ninja Forms', 'simple-history' ) .
				       '</a>' .
				       '</table>';
			}

			return '';
		}

	} // Class.
} // End if().
