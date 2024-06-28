<?php
/**
 * Plugin Name:       FirehawkCRM SEOPress Tributes Integration
 * Plugin URI:        https://github.com/weavedigitalstudio/fcrm-seopress/
 * Description:       Integrates SEOPress & SEOPress Pro with the FireHawk CRM Tributes plugin.
 * Version:           1.2.6
 * Author:            Weave Digital Studio
 * License:           GPL-3.0
 * GitHub Plugin URI: https://github.com/weavedigitalstudio/fcrm-seopress/
 * Primary Branch:    main
 * Requires at least: 6.0
 * Requires PHP:      7.2
 */

/*
Changelog:
Version 1.2.6
- Fixed: Ensure the custom SEOPress description and title are only applied to tribute pages.
- Added: New default social share image

Version 1.2.5
- Added: Setting page icon

Version 1.2.4
- Added: Support for dynamic site title in custom meta titles.
- Updated: Title format to include the site title after the custom suffix.
- Fixed: Ensured client’s name is correctly included in the title.

Version 1.2.3
- Added settings page for custom social share image URL.
- Updated logic to use the custom social share image URL or fallback to 'default-social.jpg' if not set.
- Improved code comments for better readability.

Version 1.0
- Initial release of the plugin.
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SEOPress_Tributes_Integration {

    public function __construct() {
        // Initialize hooks when the template is being loaded
        add_action('template_redirect', array($this, 'initialize_hooks'));
        // Add admin menu and settings
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function initialize_hooks() {
        // Check if the current page is a single tribute page
        if (is_page(Single_Tribute::getSingleTributePageId())) {
            // Add custom meta tags to the head section
            add_action('wp_head', array($this, 'add_seopress_meta_tags'));
            // Customize SEOPress title and description
            add_filter('seopress_titles_title', array($this, 'custom_seopress_titles_title'));
            add_filter('seopress_titles_desc', array($this, 'custom_seopress_titles_desc'));
            // Customize SEOPress Open Graph image
            add_filter('seopress_og_image', array($this, 'custom_seopress_og_image'));
        }
    }

    public function add_admin_menu() {
        // Placeholder for the icon code
        $icon_base64 = ""; // Add your icon code here

        // Add the settings page
        add_menu_page(
            'FirehawkCRM SEOPress Integration', 
            'FH SEOPress Integration', 
            'manage_options', 
            'firehawkcrm-seopress-integration', 
            array($this, 'settings_page'), 
            'data:image/svg+xml;base64,' . $icon_base64
        );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h2>FirehawkCRM SEOPress Integration</h2>
            <form method="post" action="options.php">
                <?php settings_fields('firehawkcrm_seopress_settings'); ?>
                <?php do_settings_sections('firehawkcrm_seopress_settings'); ?>
                <?php submit_button('Save Changes'); ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        // Add a section for the SEOPress integration settings
        add_settings_section(
            'firehawkcrm_seopress_section',
            __('SEOPress Integration Settings', 'firehawkcrm-seopress'),
            array($this, 'settings_section_callback'),
            'firehawkcrm_seopress_settings'
        );

        // Add a field for the custom social share image URL
        add_settings_field(
            'firehawkcrm_seopress_social_share_image',
            __('Custom Social Share Image URL', 'firehawkcrm-seopress'),
            array($this, 'social_share_image_callback'),
            'firehawkcrm_seopress_settings',
            'firehawkcrm_seopress_section'
        );

        // Add a field for the custom title suffix
        add_settings_field(
            'firehawkcrm_seopress_title_suffix',
            __('Custom Title Suffix', 'firehawkcrm-seopress'),
            array($this, 'title_suffix_callback'),
            'firehawkcrm_seopress_settings',
            'firehawkcrm_seopress_section'
        );

        // Register the settings
        register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_social_share_image');
        register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_title_suffix');
    }

    public function settings_section_callback() {
        echo '<p>' . __('Configure the custom social share image URL and title suffix for the SEOPress integration.', 'firehawkcrm-seopress') . '</p>';
    }

    public function social_share_image_callback() {
        $option = get_option('firehawkcrm_seopress_social_share_image');
        echo '<input type="text" id="firehawkcrm_seopress_social_share_image" name="firehawkcrm_seopress_social_share_image" value="' . esc_attr($option) . '" />';
    }

    public function title_suffix_callback() {
        $option = get_option('firehawkcrm_seopress_title_suffix', ' - Funeral Notice');
        echo '<input type="text" id="firehawkcrm_seopress_title_suffix" name="firehawkcrm_seopress_title_suffix" value="' . esc_attr($option) . '" />';
    }

    public function add_seopress_meta_tags() {
        // Check if SEOPress is active and available
        if (function_exists('seopress_get_service')) {
            $seopress_service = seopress_get_service('MetaTags');
            if ($seopress_service) {
                $single_tribute = new Single_Tribute();
                $single_tribute->detectClient();
                $client = $single_tribute->getClient();

                if ($client) {
                    $meta_title = $this->get_custom_meta_title($single_tribute);
                    $meta_description = isset($client->content) ? strip_tags($client->content) : "Tribute for " . $client->firstName . " " . $client->lastName;
                    $meta_image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . 'funeral-notice-social-share.jpg');
                    $current_url = $single_tribute->getPageUrl();

                    // Set the SEOPress meta tags
                    $seopress_service->setTitle($meta_title);
                    $seopress_service->setDescription($meta_description);
                    $seopress_service->setOgTitle($meta_title);
                    $seopress_service->setOgDescription($meta_description);
                    $seopress_service->setOgImage($meta_image);
                    $seopress_service->setOgUrl($current_url);
                }
            }
        }
    }

    public function custom_seopress_titles_title($title) {
        // Customize the SEOPress title
        if (is_page(Single_Tribute::getSingleTributePageId())) {
            $single_tribute = new Single_Tribute();
            $single_tribute->detectClient();
            $title = $this->get_custom_meta_title($single_tribute);
            return $title;
        }
        return $title;
    }

    public function custom_seopress_titles_desc($desc) {
        // Customize the SEOPress description
        if (is_page(Single_Tribute::getSingleTributePageId())) {
            $single_tribute = new Single_Tribute();
            $single_tribute->detectClient();
            $client = $single_tribute->getClient();
            $desc = isset($client->content) ? strip_tags($client->content) : "Tribute for " . $client->firstName . " " . $client->lastName;
            return $desc;
        }
        return $desc;
    }

    public function custom_seopress_og_image($image) {
        // Customize the SEOPress Open Graph image
        if (is_page(Single_Tribute::getSingleTributePageId())) {
            $image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . 'funeral-notice-social-share.jpg');
            return $image;
        }
        return $image;
    }

    private function get_custom_meta_title($single_tribute) {
        $client = $single_tribute->getClient();
        $clientName = isset($client->fullName) ? $client->fullName : $client->firstName . ' ' . $client->lastName;
        $customSuffix = get_option('firehawkcrm_seopress_title_suffix', ' - Funeral Notice');
        $siteTitle = get_bloginfo('name');
        $customMetaTitle = $clientName . $customSuffix . ' | ' . $siteTitle;
        return $customMetaTitle;
    }

}

new SEOPress_Tributes_Integration();
