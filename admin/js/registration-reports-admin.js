(function( $ ) {
	'use strict';

	/**
	 * Registration Reports Admin JavaScript
	 * Handles AJAX toggle functionality for user frozen status
	 */

	$(document).ready(function() {
		
		// Handle frozen status toggle clicks
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
						// Update the display
						var newStatus = response.data.is_frozen;
						var newText = newStatus ? 'Yes' : 'No';
						var newColor = newStatus ? '#d63638' : '#00a32a';
						
						$toggle.text(newText);
						$toggle.css('color', newColor);
						$toggle.data('current-status', newStatus ? '1' : '0');
						
						// Show success message
						if (typeof response.data.message !== 'undefined') {
							showNotice(response.data.message, 'success');
						}
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
		
		// Function to show notices
		function showNotice(message, type) {
			// Remove existing notices
			$('.registration-reports-notice').remove();
			
			// Create new notice
			var noticeClass = type === 'error' ? 'notice-error' : 'notice-success';
			var notice = $('<div class="notice ' + noticeClass + ' registration-reports-notice"><p>' + message + '</p></div>');
			
			// Insert after the page title
			$('.wrap h1').after(notice);
			
			// Auto-hide after 5 seconds
			setTimeout(function() {
				notice.fadeOut(function() {
					$(this).remove();
				});
			}, 5000);
		}
		
	});

})( jQuery );
