# Registration Reports

A powerful WordPress admin tool that provides detailed insights into subscriber user registrations with advanced filtering and user management capabilities.

## 🚀 Features

### Registrations Tab
- **New Registration Reports**: Find users whose registration date falls within a specified date range
- **Anniversary Reports**: Discover users whose registration anniversary (month-day) falls within a selected window, supporting year-end crossing ranges
- **Interactive User Management**: Toggle user frozen status directly from the results table with AJAX functionality
- **Frozen Timestamp Tracking**: Automatically tracks when users are frozen with a timestamped `frozen_at` field

### Review Inquiries Tab
- **Inquiry Management**: View and manage users who have submitted review inquiries while frozen
- **Date Range Filtering**: Filter inquiries by submission date range
- **Show All Inquiries**: View all users with pending review inquiries with a single click
- **Quick Unfreeze**: Unfreeze users directly from the inquiries table with one-click action buttons

### General Features
- **Tabbed Interface**: Easy navigation between Registrations and Review Inquiries
- **Comprehensive User Data**: View username, email, membership number, registration date, frozen status, and timestamps
- **Timezone Support**: All dates are handled according to your site's timezone settings
- **Secure Access**: Restricted to users with `list_users` capability
- **ACF Integration**: Displays Advanced Custom Fields data (is_frozen, frozen_at, review_inquiry, review_inquiry_at)
- **Meta Field Support**: Shows custom user meta fields like membership numbers

## 📋 Perfect For

- Membership sites tracking member registrations and managing review inquiries
- Community platforms analyzing user growth and handling frozen account appeals
- Organizations monitoring registration patterns and managing user access
- Sites needing to identify registration anniversaries for special promotions
- Administrators managing large user bases with custom fields and review workflows
- Support teams processing account review requests efficiently

## 🔒 Security Features

- Nonce verification on all form submissions and AJAX requests
- Proper input sanitization and output escaping
- Capability-based access control
- SQL injection protection through WordPress APIs
- XSS prevention with comprehensive escaping

## 📦 Installation

1. Upload the plugin files to the `/wp-content/plugins/registration-reports` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to **Users → Registration Reports** in your WordPress admin area
4. Ensure you have the `list_users` capability to access the reports

## ❓ Frequently Asked Questions

### What permissions are required to use this plugin?

You need the `list_users` capability to access the Registration Reports page. By default, this is available to Administrators and Editors.

### What's the difference between "New Registrations" and "Anniversaries" modes?

- **New Registrations**: Shows users whose actual registration date (year-month-day) falls within your selected date range
- **Anniversaries**: Shows users whose registration month-day falls within the selected range, regardless of year (e.g., all users who registered on December 15th, any year)

### Can I modify user data from the reports?

Yes! In the **Registrations** tab, you can toggle the "Is Frozen" status for any user by clicking on the Yes/No value. In the **Review Inquiries** tab, you can quickly unfreeze users with pending inquiries using the "Unfreeze" button. Both actions use AJAX for instant updates. When a user is frozen, the system automatically records the timestamp. When unfrozen, this timestamp is cleared.

### What timezone are the dates displayed in?

All dates are displayed in your WordPress site's configured timezone. The plugin automatically handles timezone conversion from the stored UTC dates.

### Does this plugin work with Advanced Custom Fields (ACF)?

Yes! The plugin requires and integrates with ACF. It uses the following ACF fields:
- `is_frozen`: Boolean field indicating if user is frozen
- `frozen_at`: Timestamp field for when user was frozen
- `review_inquiry`: Boolean field indicating if user has submitted a review inquiry
- `review_inquiry_at`: Timestamp field for when the inquiry was submitted

You'll need to create these fields in ACF for the plugin to function properly.

### What if a user doesn't have a membership number?

The membership number column will simply be empty for users who don't have that meta field set. This won't cause any errors.

### How do Review Inquiries work?

When a frozen user submits a review inquiry, the `review_inquiry` field is set to true and the `review_inquiry_at` timestamp is recorded. These users appear in the **Review Inquiries** tab where administrators can:
- View all users with pending inquiries using the "Show All Inquiries" button
- Filter inquiries by submission date range
- Quickly unfreeze users with the "Unfreeze" button

When a user is unfrozen, you may want to manually clear the `review_inquiry` field to remove them from the inquiries list.

### Can I export the results?

Currently, the plugin displays results in a table format. For export functionality, you would need to use browser-based export or copy the data manually.

## 🖼️ Screenshots

1. Tabbed interface with Registrations and Review Inquiries tabs
2. New registrations results showing comprehensive user details and interactive frozen status
3. Anniversary reports with years since registration calculation and year-end crossing support
4. Review Inquiries tab displaying users with pending review requests
5. Interactive AJAX-powered frozen status toggle and unfreeze functionality with real-time updates

## 📝 Changelog

### 1.0.0
- Initial release
- Tabbed interface for Registrations and Review Inquiries
- New registration reports with date range filtering
- Anniversary reports with year-end crossing support
- Review inquiries management tab
- Interactive user frozen status toggle with AJAX
- Quick unfreeze functionality for review inquiries
- Automatic frozen timestamp tracking (frozen_at field)
- Review inquiry tracking (review_inquiry, review_inquiry_at fields)
- ACF integration for custom user fields
- Meta field support for membership numbers
- Comprehensive security implementation with nonce verification
- Timezone-aware date handling and display
- Responsive admin interface with proper escaping
- Capability-based access control
- Error handling and user feedback

## ⚙️ Technical Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **Advanced Custom Fields**: Required for full functionality (is_frozen, frozen_at, review_inquiry, review_inquiry_at fields)

## 🔐 Security

This plugin follows WordPress security best practices:

- All user input is sanitized and validated
- All output is properly escaped to prevent XSS
- CSRF protection via nonce verification
- Capability-based access control
- No direct SQL queries (uses WordPress APIs)
- File inclusion protection
- AJAX request validation and authorization

## 🆘 Support

For support, feature requests, or bug reports, please visit our [support page](https://codemic.fi/) or create an issue in our repository.

## 👨‍💻 Credits

Developed by **Mikko Heikklä** [Codemic](https://codemic.fi/). Built with security and performance in mind, following WordPress coding standards and best practices.

## 📄 License

This project is licensed under the GPL-2.0+ License - see the [LICENSE.txt](LICENSE.txt) file for details.

---

**Tags**: `wordpress` `plugin` `users` `reports` `registration` `admin` `subscriber` `membership` `acf` `security`
