<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adventureclub.io/
 * @since      1.0.0
 *
 * @package    Registration_Reports
 * @subpackage Registration_Reports/admin/partials
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Variables are set by the admin class render_admin_page method
$form_action = $form_action ?? admin_url( 'users.php?page=cdmc-registration-reports' );
$params = $params ?? array();
$has_query = $has_query ?? false;
$errors = $errors ?? array();
$results = $results ?? array();
$mode = $mode ?? 'new';
?>

<div class="wrap">
	<h1><?php echo esc_html( $page_title ); ?></h1>

	<form method="GET" action="<?php echo esc_url( $form_action ); ?>">
		<input type="hidden" name="page" value="<?php echo esc_attr( 'cdmc-registration-reports' ); ?>" />
		<?php wp_nonce_field( 'cdmc_rr_nonce' ); ?>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="start_date"><?php esc_html_e( 'Start Date', 'registration-reports' ); ?></label>
				</th>
				<td>
					<input type="date" id="start_date" name="start_date" value="<?php echo esc_attr( $params['start_date'] ?? '' ); ?>" required />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="end_date"><?php esc_html_e( 'End Date', 'registration-reports' ); ?></label>
				</th>
				<td>
					<input type="date" id="end_date" name="end_date" value="<?php echo esc_attr( $params['end_date'] ?? '' ); ?>" required />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="mode"><?php esc_html_e( 'Report Type', 'registration-reports' ); ?></label>
				</th>
				<td>
					<select id="mode" name="mode">
						<option value="new" <?php selected( $params['mode'] ?? 'new', 'new' ); ?>>
							<?php esc_html_e( 'New Registrations', 'registration-reports' ); ?>
						</option>
						<option value="anniversary" <?php selected( $params['mode'] ?? 'new', 'anniversary' ); ?>>
							<?php esc_html_e( 'Anniversaries', 'registration-reports' ); ?>
						</option>
					</select>
					<p class="description">
						<?php esc_html_e( 'New Registrations: users whose registration date falls within the range.', 'registration-reports' ); ?><br>
						<?php esc_html_e( 'Anniversaries: users whose registration month-day falls within the range (any year).', 'registration-reports' ); ?>
					</p>
				</td>
			</tr>
		</table>

		<?php submit_button( __( 'Search', 'registration-reports' ) ); ?>
	</form>

	<?php
	// Generate nonce for AJAX requests
	$toggle_nonce = wp_create_nonce( 'toggle_user_frozen_nonce' );
	?>

	<?php if ( ! empty( $errors ) ) : ?>
		<div class="notice notice-error">
			<?php foreach ( $errors as $error ) : ?>
				<p><?php echo esc_html( $error ); ?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $has_query ) : ?>
		<div class="results-section">
			<?php if ( empty( $results ) ) : ?>
				<div class="notice notice-info">
					<p><?php esc_html_e( 'No matching users found.', 'registration-reports' ); ?></p>
				</div>
			<?php else : ?>
				<h2><?php 
					if ( $mode === 'new' ) {
						esc_html_e( 'New Registrations', 'registration-reports' );
					} else {
						esc_html_e( 'Registration Anniversaries', 'registration-reports' );
					}
				?> (<?php echo esc_html( count( $results ) ); ?>)</h2>

				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Username', 'registration-reports' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Email', 'registration-reports' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Membership Number', 'registration-reports' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Registered', 'registration-reports' ); ?></th>
							<?php if ( $mode === 'anniversary' ) : ?>
								<th scope="col"><?php esc_html_e( 'Years Since', 'registration-reports' ); ?></th>
							<?php endif; ?>
							<th scope="col"><?php esc_html_e( 'Is Frozen', 'registration-reports' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $results as $row ) : ?>
							<tr>
								<td><?php echo esc_html( $row['user_login'] ); ?></td>
								<td>
									<a href="mailto:<?php echo esc_attr( $row['user_email'] ); ?>">
										<?php echo esc_html( $row['user_email'] ); ?>
									</a>
								</td>
								<td>
									<?php 
									$membership_number = $row['membership_number'] ?? '';
									echo esc_html( $membership_number );
									?>
								</td>
								<td>
									<?php
									// Convert UTC user_registered to site timezone for display
									$reg_utc = new DateTimeImmutable( $row['user_registered'], new DateTimeZone( 'UTC' ) );
									$reg_site = $reg_utc->setTimezone( $site_tz );
									echo esc_html( $reg_site->format( 'Y-m-d H:i' ) );
									?>
								</td>
								<?php if ( $mode === 'anniversary' ) : ?>
									<td><?php echo esc_html( $row['years_since'] ); ?></td>
								<?php endif; ?>
								<td>
									<?php
									$is_frozen = $row['is_frozen'] ?? false;
									$user_id = $row['user_id'] ?? 0;
									$status_class = $is_frozen ? 'frozen' : 'unfrozen';
									$status_text = $is_frozen ? __( 'Yes', 'registration-reports' ) : __( 'No', 'registration-reports' );
									$status_color = $is_frozen ? '#d63638' : '#00a32a';
									?>
									<span class="frozen-toggle" 
										  data-user-id="<?php echo esc_attr( $user_id ); ?>"
										  data-current-status="<?php echo esc_attr( $is_frozen ? '1' : '0' ); ?>"
										  data-nonce="<?php echo esc_attr( $toggle_nonce ); ?>"
										  style="color: <?php echo esc_attr( $status_color ); ?>; cursor: pointer; text-decoration: underline;"
										  title="<?php esc_attr_e( 'Click to toggle frozen status', 'registration-reports' ); ?>">
										<?php echo esc_html( $status_text ); ?>
									</span>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
