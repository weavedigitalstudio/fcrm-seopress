<?php
/**
 * Fired when the plugin is uninstalled.
 * 
 * @package FirehawkCRM\SEOPress
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('firehawkcrm_seopress_social_share_image');
delete_option('firehawkcrm_seopress_title_suffix');

// Clean up any additional plugin data if needed
// For example, if you add more options in the future
