<php;
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('firehawkcrm_seopress_social_share_image');
delete_option('firehawkcrm_seopress_title_suffix');
