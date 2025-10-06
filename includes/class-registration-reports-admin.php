<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.adventureclub.io/
 * @since      1.0.0
 *
 * @package    Registration_Reports
 * @subpackage Registration_Reports/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and admin functionality for registration reports.
 *
 * @package    Registration_Reports
 * @subpackage Registration_Reports/includes
 * @author     Mikko Heikkilä <mikko.heikkila@adventureclub.io>
 */
class Registration_Reports_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Nonce action key for form security.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $nonce_action    The nonce action key.
	 */
	private $nonce_action = 'cdmc_rr_nonce';

	/**
	 * Admin page slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $page_slug    The admin page slug.
	 */
	private $page_slug = 'cdmc-registration-reports';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/registration-reports-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Only enqueue on our admin page
		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->page_slug ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/registration-reports-admin.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * Add the admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_users_page(
			__( 'Registration Reports', 'registration-reports' ),
			__( 'Registration Reports', 'registration-reports' ),
			'list_users',
			$this->page_slug,
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @since    1.0.0
	 */
	public function render_admin_page() {
		// Permission gate
		if ( ! current_user_can( 'list_users' ) ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'You do not have sufficient permissions to access this page.', 'registration-reports' ) . '</p></div>';
			return;
		}

		$form_action = admin_url( 'users.php?page=' . $this->page_slug );
		$params = $this->read_params();
		$errors = array();
		$results = array();
		$has_query = false;

		// Process form submission if params are present
		if ( $params['has_query'] ) {
			// Verify nonce
			if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', $this->nonce_action ) ) {
				$errors[] = __( 'Security check failed. Please try again.', 'registration-reports' );
			} else {
				// Validate dates
				if ( empty( $params['start_date'] ) || empty( $params['end_date'] ) ) {
					$errors[] = __( 'Please provide both start and end dates.', 'registration-reports' );
				} elseif ( ! $params['start_dt'] || ! $params['end_dt'] ) {
					$errors[] = __( 'Please provide valid dates.', 'registration-reports' );
				} else {
					$has_query = true;
					
					if ( $params['mode'] === 'new' ) {
						$results = $this->query_new( $params['start_dt'], $params['end_dt'], wp_timezone() );
					} elseif ( $params['mode'] === 'anniversary' ) {
						$results = $this->query_anniversaries( $params['start_dt'], $params['end_dt'], wp_timezone() );
					}
				}
			}
		}

		// Include the admin partial with variables
		$page_title = __( 'Registration Reports', 'registration-reports' );
		$site_tz = wp_timezone();
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/registration-reports-admin-display.php';
	}

	/**
	 * Read and sanitize GET parameters.
	 *
	 * @since    1.0.0
	 * @return   array    Array of sanitized parameters
	 */
	private function read_params() {
		$start_date = sanitize_text_field( $_GET['start_date'] ?? '' );
		$end_date = sanitize_text_field( $_GET['end_date'] ?? '' );
		$mode = sanitize_text_field( $_GET['mode'] ?? 'new' );
		
		// Validate mode
		if ( ! in_array( $mode, array( 'new', 'anniversary' ), true ) ) {
			$mode = 'new';
		}

		$has_query = ! empty( $start_date ) && ! empty( $end_date ) && ! empty( $mode );

		$start_dt = null;
		$end_dt = null;

		if ( $has_query ) {
			$site_tz = wp_timezone();
			
			// Parse dates in site timezone
			$start_dt = $this->parse_date( $start_date . ' 00:00:00', $site_tz );
			$end_dt = $this->parse_date( $end_date . ' 23:59:59', $site_tz );

			// Swap dates if start > end
			if ( $start_dt && $end_dt && $start_dt > $end_dt ) {
				$temp_dt = $start_dt;
				$start_dt = $end_dt;
				$end_dt = $temp_dt;
				
				$temp_date = $start_date;
				$start_date = $end_date;
				$end_date = $temp_date;
			}
		}

		return array(
			'start_date' => $start_date,
			'end_date' => $end_date,
			'mode' => $mode,
			'has_query' => $has_query,
			'start_dt' => $start_dt,
			'end_dt' => $end_dt,
		);
	}

	/**
	 * Parse a date string to DateTimeImmutable.
	 *
	 * @since    1.0.0
	 * @param    string            $input_string    The date string to parse
	 * @param    DateTimeZone      $site_timezone   The site timezone
	 * @return   DateTimeImmutable|null            The parsed date or null if invalid
	 */
	private function parse_date( $input_string, $site_timezone ) {
		try {
			return new DateTimeImmutable( $input_string, $site_timezone );
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * Convert a site-timezone DateTimeImmutable to UTC string for DB comparison.
	 *
	 * @since    1.0.0
	 * @param    DateTimeImmutable    $date    The date to convert
	 * @return   string                        The UTC date string
	 */
	private function to_utc_string( $date ) {
		return $date->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Query new registrations within the date range.
	 *
	 * @since    1.0.0
	 * @param    DateTimeImmutable    $start_dt      Start date in site timezone
	 * @param    DateTimeImmutable    $end_dt        End date in site timezone
	 * @param    DateTimeZone         $site_tz       Site timezone
	 * @return   array                               Array of user rows
	 */
	private function query_new( $start_dt, $end_dt, $site_tz ) {
		$start_utc = $this->to_utc_string( $start_dt );
		$end_utc = $this->to_utc_string( $end_dt );

		$args = array(
			'role' => 'subscriber',
			'number' => -1, // Get all subscribers
			'fields' => array( 'ID', 'user_login', 'user_email', 'user_registered' ),
			'date_query' => array(
				array(
					'column' => 'user_registered',
					'after' => $start_utc,
					'before' => $end_utc,
					'inclusive' => true,
				),
			),
			'orderby' => 'user_registered',
			'order' => 'ASC',
		);

		$user_query = new WP_User_Query( $args );
		$users = $user_query->get_results();

		$results = array();
		foreach ( $users as $user ) {
			$results[] = array(
				'user_id' => $user->ID,
				'user_login' => $user->user_login,
				'user_email' => $user->user_email,
				'membership_number' => get_user_meta( $user->ID, 'membership_number', true ),
				'user_registered' => $user->user_registered,
				'years_since' => null,
				'is_frozen' => get_field( 'is_frozen', 'user_' . $user->ID ),
			);
		}

		return $results;
	}

	/**
	 * Query anniversary registrations within the month-day range.
	 *
	 * @since    1.0.0
	 * @param    DateTimeImmutable    $start_dt      Start date in site timezone
	 * @param    DateTimeImmutable    $end_dt        End date in site timezone
	 * @param    DateTimeZone         $site_tz       Site timezone
	 * @return   array                               Array of user rows
	 */
	private function query_anniversaries( $start_dt, $end_dt, $site_tz ) {
		// Get all subscribers
		$args = array(
			'role' => 'subscriber',
			'number' => -1,
			'fields' => array( 'ID', 'user_login', 'user_email', 'user_registered' ),
		);

		$user_query = new WP_User_Query( $args );
		$users = $user_query->get_results();

		$start_md = (int) $start_dt->format( 'md' );
		$end_md = (int) $end_dt->format( 'md' );
		$crosses_year_end = $start_md > $end_md;

		$results = array();
		foreach ( $users as $user ) {
			// Convert user_registered from UTC to site timezone
			$reg_utc = new DateTimeImmutable( $user->user_registered, new DateTimeZone( 'UTC' ) );
			$reg_site_dt = $reg_utc->setTimezone( $site_tz );
			$user_md = (int) $reg_site_dt->format( 'md' );

			// Check if user falls within the month-day range
			$in_range = false;
			if ( $crosses_year_end ) {
				$in_range = ( $user_md >= $start_md ) || ( $user_md <= $end_md );
			} else {
				$in_range = ( $user_md >= $start_md ) && ( $user_md <= $end_md );
			}

			if ( $in_range ) {
				$years_since = $this->years_since( $reg_site_dt, $end_dt );
				
				$results[] = array(
					'user_id' => $user->ID,
					'user_login' => $user->user_login,
					'user_email' => $user->user_email,
					'membership_number' => get_user_meta( $user->ID, 'membership_number', true ),
					'user_registered' => $user->user_registered,
					'years_since' => $years_since,
					'is_frozen' => get_field( 'is_frozen', 'user_' . $user->ID ),
				);
			}
		}

		// Sort by month-day ascending, then by user_login
		usort( $results, function( $a, $b ) use ( $site_tz ) {
			$reg_a = new DateTimeImmutable( $a['user_registered'], new DateTimeZone( 'UTC' ) );
			$reg_b = new DateTimeImmutable( $b['user_registered'], new DateTimeZone( 'UTC' ) );
			
			$reg_a_site = $reg_a->setTimezone( $site_tz );
			$reg_b_site = $reg_b->setTimezone( $site_tz );
			
			$md_a = (int) $reg_a_site->format( 'md' );
			$md_b = (int) $reg_b_site->format( 'md' );
			
			if ( $md_a === $md_b ) {
				return strcmp( $a['user_login'], $b['user_login'] );
			}
			
			return $md_a - $md_b;
		});

		return $results;
	}

	/**
	 * Calculate years since registration relative to the end date.
	 *
	 * @since    1.0.0
	 * @param    DateTimeImmutable    $reg_site_dt    Registration date in site timezone
	 * @param    DateTimeImmutable    $end_dt         End date in site timezone
	 * @return   int                                  Years since registration (minimum 1)
	 */
	private function years_since( $reg_site_dt, $end_dt ) {
		$reg_year = (int) $reg_site_dt->format( 'Y' );
		$end_year = (int) $end_dt->format( 'Y' );
		
		$years = $end_year - $reg_year;
		
		// Create the anniversary date in the end year
		$anniversary_in_end_year = $end_dt->setDate( 
			$end_year, 
			(int) $reg_site_dt->format( 'm' ), 
			(int) $reg_site_dt->format( 'd' ) 
		);
		
		// If the anniversary in the end year is after the end date, subtract 1
		if ( $anniversary_in_end_year > $end_dt ) {
			$years--;
		}
		
		// Clamp to minimum 1
		return max( 1, $years );
	}

	/**
	 * Handle AJAX request to toggle user frozen status.
	 *
	 * @since    1.0.0
	 */
	public function handle_toggle_user_frozen() {
		// Verify nonce for security
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'toggle_user_frozen_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'registration-reports' ) ) );
			return;
		}

		// Check user permissions
		if ( ! current_user_can( 'list_users' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'registration-reports' ) ) );
			return;
		}

		$user_id = intval( $_POST['user_id'] ?? 0 );
		if ( ! $user_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid user ID.', 'registration-reports' ) ) );
			return;
		}

		// Verify user exists and is a subscriber
		$user = get_user_by( 'ID', $user_id );
		if ( ! $user || ! in_array( 'subscriber', $user->roles, true ) ) {
			wp_send_json_error( array( 'message' => __( 'User not found or not a subscriber.', 'registration-reports' ) ) );
			return;
		}

		// Get current frozen status
		$current_frozen = get_field( 'is_frozen', 'user_' . $user_id );
		$new_frozen = ! $current_frozen;

		// Update the ACF field
		$updated = update_field( 'is_frozen', $new_frozen, 'user_' . $user_id );

		if ( $updated ) {
			wp_send_json_success( array(
				'is_frozen' => $new_frozen,
				'message' => sprintf(
					__( 'User %s is now %s.', 'registration-reports' ),
					$user->user_login,
					$new_frozen ? __( 'frozen', 'registration-reports' ) : __( 'unfrozen', 'registration-reports' )
				)
			) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to update user status.', 'registration-reports' ) ) );
		}
	}
}
