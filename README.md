# FireHawk CRM Tributes - SEOPress & SEOPress Pro Integration
 
If you use SEOPress on your WordPress funeral website, this replaces the current bundled Yoast SEO integration of the FireHawkCRM Tributes plugin with added support for SEOPress and SEOPress Pro.

Currently FireHawk CRM Tributes WordPress plugin relies on using the YoastSEO Plugin to add the required title and meta tags to the individual tribute & funeral pages.
This add-on adds compatibility for [SEOPress](https://wordpress.org/plugins/wp-seopress/) instead if you use the free [SEOPress](https://wordpress.org/plugins/wp-seopress/) or [SEOPress Pro](https://www.seopress.org/) instead instead of YoastSEO.

Tribute meta page tags are then controlled by SEOPress

It also adds a setting for a default social share image which can be set for tributes site-wide.

FireHawk CRM and their WordPress tributes plugin are from [FireHawk Funerals](https://firehawkfunerals.com)

---

## Our extensions for funeral websites and the FireHawk CRM Tributes Plugin.
This is part of a small family of complimentary plugins we've developed to extend FireHawkCRM Tributes functionality for our use building funeral websites in WordPress.

For a complete tribute management solution and CRM, consider trying FireHawkCRM and our other add-ons:

- [FCRM Enhancement Suite](https://github.com/weavedigitalstudio/fcrm-enhancement-suite):
A collection of enhancements and additional features for the FireHawkCRM Tributes plugin that improve the site performance, user experience, and functionality of tribute pages in WordPress.

- [FCRM SEOPress Integration](https://github.com/weavedigitalstudio/fcrm-seopress):
If you use SEOPress on your WordPress site, this replaces the current bundled YoastSEO integration of the FireHawkCRM Tributes plugin with added support for SEOPress and SEOPress Pro. Meta titles and tags are then controlled by SEOPress.

- [FCRM Plausible Analytics](https://github.com/weavedigitalstudio/fcrm-plausible-analytics):
Integration for FireHawkCRM Tributes plugin which adds Plausible Analytics tracking code to the individual funerals/tribute pages. Plausible is a privacy-focused analytics to track tribute engagement while respecting visitor privacy. We recommend Plausible instead of Google Analytics.

---

## Installation from GitHub
When installing this plugin from GitHub:
1. Go to the [Releases](https://github.com/weavedigitalstudio/fcrm-seopress/releases) page
2. Download the latest release ZIP file
3. Extract the ZIP file on your computer
4. Rename the extracted folder to remove the version number  
   (e.g., from `fcrm-seopress-0.1.1` to `fcrm-seopress`)
5. Create a new ZIP file from the renamed folder
6. In your WordPress admin panel, go to Plugins → Add New → Upload Plugin
7. Upload your new ZIP file and activate the plugin
8. Plugin should then auto-update moving forward if there are any changes.

**Note**: The folder renaming step is necessary for WordPress to properly handle plugin updates and functionality.

---

## Requirements

This plugin requires:
- [FireHawkCRM Tributes](https://firehawkfunerals.com) plugin
- Either [SEOPress](https://wordpress.org/plugins/wp-seopress/) (free) or [SEOPress Pro](https://www.seopress.org/)
- WordPress 6.0 or higher
- PHP 7.2 or higher

The plugin will automatically check for the required plugins and show an admin notice if they're not active.

---

## ⚠️ Important Notice

This plugin is primarily developed for internal use at Weave Digital Studio & Human Kind and with our  funeral websites we build. While we're making it available publicly, please note:

- Features and updates are driven by our specific needs and client requirements
- Testing is conducted only within our controlled environments
- We cannot guarantee compatibility with all WordPress setups or themes
- No official support is provided for external users
- Use in production environments outside our ecosystem is at your own risk

We encourage you to test thoroughly in a staging environment before any production use.

---

## Credits

- Developed by [Weave Digital Studio](https://weave.co.nz) in New Zealand.
- FireHawkCRM and their WordPress tributes plugin are from [FireHawk Funerals](https://firehawkfunerals.com).

![Plugin Icon](icon-256x256.png)

---

## Changelog

### v1.4.0
- Added media upload for social share image
- Added namesapce
- Updated README and added CHANGELOG
- Added Github install instructions

### Version 1.3.0
- **Fixed**: Spelling fixes ;-)

### Version 1.2.8
- **Added**: Check for the FirehawkCRM Tributes plugin being active before running integration logic.
- **Fixed**: Prevent PHP warnings when client data is not available.
- **Updated**: Remove newlines from meta description fields.

### Version 1.2.7
- **Fixed**: Ensure OG image meta tag is added with fallback image.

### Version 1.2.6
- **Fixed**: Ensure the custom SEOPress description and title are only applied to tribute pages.
- **Added**: New default social share image.

### Version 1.2.5
- **Added**: Setting page icon.

### Version 1.2.4
- **Added**: Support for dynamic site title in custom meta titles.
- **Updated**: Title format to include the site title after the custom suffix.
- **Fixed**: Ensured client’s name is correctly included in the title.

### Version 1.2.3
- **Added**: Settings page for custom social share image URL.
- **Updated**: Logic to use the custom social share image URL or fallback to `default-social.jpg` if not set.
- **Improved**: Code comments for better readability.

### Version 1.0
- **Initial Release**: The plugin.