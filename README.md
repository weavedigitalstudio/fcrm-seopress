# FireHawk CRM Tributes - SEOPress Integration

## Description
This plugin provides SEOPress (free and Pro) integration for the FireHawk CRM Tributes WordPress plugin. While the FireHawk CRM Tributes plugin traditionally uses YoastSEO for managing title and meta tags on tribute & funeral pages, this add-on provides equivalent functionality using SEOPress instead.

FireHawk CRM and their WordPress tributes plugin are from [FireHawk Funerals](https://firehawkfunerals.com)

## Features
- Adds SEOPress compatibility to FireHawk CRM Tributes
- Controls tribute meta page tags through SEOPress
- Provides settings page for configuring:
  - Default social share image (sitewide setting)
  - Custom title suffix for tribute pages

## Requirements
- WordPress 6.0+
- PHP 7.2+
- [FireHawk CRM Tributes plugin](https://firehawkfunerals.com)
- [SEOPress](https://wordpress.org/plugins/wp-seopress/) or [SEOPress Pro](https://www.seopress.org/) plugins

## About
FireHawk CRM and their WordPress tributes plugin are products of FireHawk Funerals. This integration plugin is maintained by Weave Digital Studio.

## Changelog
### 1.4.2
- Added validation for plugin settings
- Added missing SEO customization callbacks
- Added proper uninstall cleanup
- Improved code organisation and structure
- Added Yoast SEO conflict handling

### 1.4.0
- Code structure improvements
- Better dependency checking
- Enhanced error handling
- Improved settings page UI

### 1.3.0
- Spelling fixes

### 1.2.8
- Added: Check for the FirehawkCRM Tributes plugin being active before running integration logic.
- Fixed: Prevent PHP warnings when client data is not available.
- Updated: Remove newlines from meta description fields.

### 1.2.7
- Fixed: Ensure OG image meta tag is added with fallback image.

### 1.2.6
- Fixed: Ensure the custom SEOPress description and title are only applied to tribute pages.
- Added: New default social share image

### 1.2.5
- Added: Setting page icon.

### 1.2.4
- Added: Support for dynamic site title in custom meta titles.
- Updated: Title format to include the site title after the custom suffix.
- Fixed: Ensured client’s name is correctly included in the title.

### 1.2.3
- Added settings page for custom social share image URL.
- Updated logic to use the custom social share image URL or fallback to 'default-social.jpg' if not set.
- Improved code comments for better readability.

### 1.0
- Initial release of the plugin.

## License
MIT License
