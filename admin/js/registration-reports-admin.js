(function( $ ) {
	'use strict';

	/**
	 * Registration Reports Admin JavaScript
	 * Handles AJAX toggle functionality for user frozen status
	 */

	$(document).ready(function() {
		
		// Handle frozen status toggle clicks (for registrations tab)
		$(document).on('click', '.frozen-toggle', function(e) {
			e.preventDefault();
			
			var $toggle = $(this);
			var userId = $toggle.data('user-id');
			var currentStatus = $toggle.data('current-status');
			var nonce = $toggle.data('nonce');
			
			// Prevent multiple clicks while processing
			if ($toggle.hasClass('processing')) {
				return;
			}
			
			$toggle.addClass('processing');
			$toggle.text('...');
			
			// Make AJAX request
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'toggle_user_frozen',
					user_id: userId,
					nonce: nonce
				},
				success: function(response) {
					if (response.success) {
						// Show success message and reload page to show updated frozen_at timestamp
						if (typeof response.data.message !== 'undefined') {
							showNotice(response.data.message, 'success');
						}
						
						// Reload the page after a short delay to show the notice
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						// Show error message
						var errorMsg = 'Failed to update user status.';
						if (typeof response.data !== 'undefined' && typeof response.data.message !== 'undefined') {
							errorMsg = response.data.message;
						}
						showNotice(errorMsg, 'error');
						
						// Restore original text
						var originalText = currentStatus === '1' ? 'Yes' : 'No';
						$toggle.text(originalText);
					}
				},
				error: function() {
					// Show error message
					showNotice('An error occurred while updating the user status.', 'error');
					
					// Restore original text
					var originalText = currentStatus === '1' ? 'Yes' : 'No';
					$toggle.text(originalText);
				},
				complete: function() {
					$toggle.removeClass('processing');
				}
			});
		});
		
		// Handle unfreeze button clicks (for inquiries tab)
		$(document).on('click', '.unfreeze-user', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var userId = $button.data('user-id');
			var nonce = $button.data('nonce');
			
			// Prevent multiple clicks while processing
			if ($button.prop('disabled')) {
				return;
			}
			
			$button.prop('disabled', true);
			$button.text('Processing...');
			
			// Make AJAX request to unfreeze the user
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'toggle_user_frozen',
					user_id: userId,
					nonce: nonce
				},
				success: function(response) {
					if (response.success) {
						// Show success message and reload page
						if (typeof response.data.message !== 'undefined') {
							showNotice(response.data.message, 'success');
						}
						
						// Reload the page after a short delay to show the notice
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						// Show error message
						var errorMsg = 'Failed to unfreeze user.';
						if (typeof response.data !== 'undefined' && typeof response.data.message !== 'undefined') {
							errorMsg = response.data.message;
						}
						showNotice(errorMsg, 'error');
						
						// Re-enable button
						$button.prop('disabled', false);
						$button.text('Unfreeze');
					}
				},
				error: function() {
					// Show error message
					showNotice('An error occurred while unfreezing the user.', 'error');
					
					// Re-enable button
					$button.prop('disabled', false);
					$button.text('Unfreeze');
				}
			});
		});
		
	// Function to show notices
	function showNotice(message, type) {
		// Clear the notice container
		$('#rr-notice-container').empty();
		
		// Create new notice with custom class to prevent WordPress from moving it
		var styleClass = type === 'error' ? 'rr-notice-error' : 'rr-notice-success';
		var notice = $('<div class="rr-notice ' + styleClass + '"><p>' + message + '</p></div>');
		
		// Insert into the dedicated notice container
		$('#rr-notice-container').html(notice);
		
		// Auto-hide after 5 seconds
		setTimeout(function() {
			notice.fadeOut(function() {
				$(this).remove();
			});
		}, 5000);
	}
		
	});

})( jQuery );
