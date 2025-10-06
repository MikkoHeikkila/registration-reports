=== Registration Reports ===
Contributors: mikkoheikkila
Donate link: https://codemic.fi/
Tags: users, reports, registration, admin, subscriber, membership
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Generate comprehensive reports for subscriber registrations and anniversaries with advanced filtering and user management capabilities.

== Description ==

Registration Reports is a powerful WordPress admin tool that provides detailed insights into subscriber user registrations. Perfect for membership sites, community platforms, and any WordPress site that needs to track and manage user registrations effectively.

### Key Features

* **New Registration Reports**: Find users whose registration date falls within a specified date range
* **Anniversary Reports**: Discover users whose registration anniversary (month-day) falls within a selected window, supporting year-end crossing ranges
* **Interactive User Management**: Toggle user frozen status directly from the results table with AJAX functionality
* **Comprehensive User Data**: View username, email, membership number, registration date, and account status
* **Timezone Support**: All dates are handled according to your site's timezone settings
* **Secure Access**: Restricted to users with `list_users` capability
* **ACF Integration**: Displays Advanced Custom Fields data for enhanced user information
* **Meta Field Support**: Shows custom user meta fields like membership numbers

### Perfect For

* Membership sites tracking member registrations
* Community platforms analyzing user growth
* Organizations monitoring registration patterns
* Sites needing to identify registration anniversaries for special promotions
* Administrators managing large user bases with custom fields

### Security Features

* Nonce verification on all form submissions and AJAX requests
* Proper input sanitization and output escaping
* Capability-based access control
* SQL injection protection through WordPress APIs
* XSS prevention with comprehensive escaping

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/registration-reports` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to Users → Registration Reports in your WordPress admin area
4. Ensure you have the `list_users` capability to access the reports

== Frequently Asked Questions ==

= What permissions are required to use this plugin? =

You need the `list_users` capability to access the Registration Reports page. By default, this is available to Administrators and Editors.

= What's the difference between "New Registrations" and "Anniversaries" modes? =

* **New Registrations**: Shows users whose actual registration date (year-month-day) falls within your selected date range
* **Anniversaries**: Shows users whose registration month-day falls within the selected range, regardless of year (e.g., all users who registered on December 15th, any year)

= Can I modify user data from the reports? =

Yes! You can toggle the "Is Frozen" status for any user directly from the results table by clicking on the Yes/No value. This uses AJAX for instant updates without page refresh.

= What timezone are the dates displayed in? =

All dates are displayed in your WordPress site's configured timezone. The plugin automatically handles timezone conversion from the stored UTC dates.

= Does this plugin work with Advanced Custom Fields (ACF)? =

Yes! The plugin displays ACF fields (like the "is_frozen" field) and can work with any user meta fields you have configured.

= What if a user doesn't have a membership number? =

The membership number column will simply be empty for users who don't have that meta field set. This won't cause any errors.

= Can I export the results? =

Currently, the plugin displays results in a table format. For export functionality, you would need to use browser-based export or copy the data manually.

== Screenshots ==

1. Main registration reports interface with date range selection and mode options
2. New registrations results showing comprehensive user details and interactive frozen status
3. Anniversary reports with years since registration calculation and year-end crossing support
4. Interactive AJAX-powered frozen status toggle functionality with real-time updates

== Changelog ==

= 1.0.0 =
* Initial release
* New registration reports with date range filtering
* Anniversary reports with year-end crossing support
* Interactive user frozen status toggle with AJAX
* ACF integration for custom user fields (is_frozen)
* Meta field support for membership numbers
* Comprehensive security implementation with nonce verification
* Timezone-aware date handling and display
* Responsive admin interface with proper escaping
* Capability-based access control
* Error handling and user feedback

== Upgrade Notice ==

= 1.0.0 =
Initial release of Registration Reports plugin with full feature set including interactive user management and comprehensive security.

== Technical Requirements ==

* WordPress 5.0 or higher
* PHP 7.4 or higher
* MySQL 5.6 or higher
* Advanced Custom Fields plugin (optional, for ACF field support)

== Security ==

This plugin follows WordPress security best practices:

* All user input is sanitized and validated
* All output is properly escaped to prevent XSS
* CSRF protection via nonce verification
* Capability-based access control
* No direct SQL queries (uses WordPress APIs)
* File inclusion protection
* AJAX request validation and authorization

== Support ==

For support, feature requests, or bug reports, please visit our [support page](https://www.adventureclub.io/contact/) or create an issue in our repository.

== Credits ==

Developed by Mikko Heikkilä for Adventure Club. Built with security and performance in mind, following WordPress coding standards and best practices.