<?php
/*
Plugin Name: FirehawkCRM SEOPress Tributes Integration
Description: Integrates SEOPress Pro with the FCRM Tributes plugin.
Version: 1.1
Author: Weave Digital

Changelog:
Version 1.1
- Added settings page for custom social share image URL.
- Updated logic to use the custom social share image URL or fallback to 'default-social.jpg' if not set.
- Improved code comments for better readability.

Version 1.0
- Initial release of the plugin.
*/

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
        $icon_base64 = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDEwMDAgMTAwMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiNGRkZGRkY7fQoJLnN0MXtvcGFjaXR5OjAuODU7ZmlsbDp1cmwoI1NWR0lEXzFfKTt9Cgkuc3Qye29wYWNpdHk6MC41NTtmaWxsOiNGRkZGRkY7fQoJLnN0M3tvcGFjaXR5OjAuNjU7ZmlsbDojRkZGRkZGO30KCS5zdDR7ZmlsbDp1cmwoI1NWR0lEXzJfKTt9Cgkuc3Q1e29wYWNpdHk6MC41OTtmaWxsOiNGRkZGRkY7fQoJLnN0NntvcGFjaXR5OjAuNzY7ZmlsbDojRkZGRkZGO30KCS5zdDd7ZmlsbDp1cmwoI1NWR0lEXzNfKTt9Cgkuc3Q4e2ZpbGw6dXJsKCNTVkdJRF80Xyk7fQoJLnN0OXtmaWxsOnVybCgjU1ZHSURfNV8pO30KCS5zdDEwe2ZpbGw6dXJsKCNTVkdJRF82Xyk7fQoJLnN0MTF7ZmlsbDp1cmwoI1NWR0lEXzdfKTt9Cgkuc3QxMntmaWxsOnVybCgjU1ZHSURfOF8pO30KCS5zdDEze2ZpbGw6dXJsKCNTVkdJRF85Xyk7fQoJLnN0MTR7ZmlsbDp1cmwoI1NWR0lEXzEwXyk7fQoJLnN0MTV7ZmlsbDp1cmwoI1NWR0lEXzExXyk7fQoJLnN0MTZ7ZmlsbDp1cmwoI1NWR0lEXzEyXyk7fQoJLnN0MTd7ZmlsbDp1cmwoI1NWR0lEXzEzXyk7fQoJLnN0MTh7ZmlsbDp1cmwoI1NWR0lEXzE0Xyk7fQoJLnN0MTl7ZmlsbDp1cmwoI1NWR0lEXzE1Xyk7fQoJLnN0MjB7b3BhY2l0eTowLjM1O2ZpbGw6I0ZGRkZGRjt9Cgkuc3QyMXtvcGFjaXR5OjAuODU7ZmlsbDp1cmwoI1NWR0lEXzE2Xyk7fQoJLnN0MjJ7b3BhY2l0eTowLjg1O2ZpbGw6dXJsKCNTVkdJRF8xN18pO30KCS5zdDIze29wYWNpdHk6MC41MTtmaWxsOiNGRkZGRkY7fQoJLnN0MjR7ZmlsbDp1cmwoI1NWR0lEXzE4Xyk7fQoJLnN0MjV7ZmlsbDp1cmwoI1NWR0lEXzE5Xyk7fQoJLnN0MjZ7ZmlsbDp1cmwoI1NWR0lEXzIwXyk7fQoJLnN0Mjd7ZmlsbDp1cmwoI1NWR0lEXzIxXyk7fQoJLnN0Mjh7ZmlsbDp1cmwoI1NWR0lEXzIyXyk7fQoJLnN0Mjl7ZmlsbDp1cmwoI1NWR0lEXzIzXyk7fQoJLnN0MzB7ZmlsbDp1cmwoI1NWR0lEXzI0Xyk7fQoJLnN0MzF7ZmlsbDp1cmwoI1NWR0lEXzI1Xyk7fQoJLnN0MzJ7ZmlsbDp1cmwoI1NWR0lEXzI2Xyk7fQoJLnN0MzN7ZmlsbDp1cmwoI1NWR0lEXzI3Xyk7fQoJLnN0MzR7ZmlsbDp1cmwoI1NWR0lEXzI4Xyk7fQoJLnN0MzV7ZmlsbDp1cmwoI1NWR0lEXzI5Xyk7fQoJLnN0MzZ7ZmlsbDp1cmwoI1NWR0lEXzMwXyk7fQoJLnN0Mzd7ZmlsbDp1cmwoI1NWR0lEXzMxXyk7fQoJLnN0Mzh7b3BhY2l0eTowLjg1O2ZpbGw6dXJsKCNTVkdJRF8zMl8pO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTQ2Ni4yLDU2Mi43Yy00NC44LTguNC03Ny4xLTQzLjEtMTA5LjktNzJjLTgzLjUtNzMuNy0xNTcuNS0xNTIuOS0yMjYuOC0yNDBsLTEuMiw5MC45bC0yNy44LTIyLjRsMjIsNzcuNgoJbC0zNS4zLTE3LjhsMzksNjkuNGwtNDIuOC0xMi45bDU2LjgsNjIuNGwtNDguMi00LjlsNjkuMiw1MC42bC01NS44LDMuOWw3NSwzMy41TDEzMSw1OTIuM2w4MC41LDIwLjhsLTQyLjcsMTcuNmw4MC42LDkuMQoJbC0yNy4xLDE2LjFsNzQuMS0xLjhsLTI3LjUsMjEuOWw3MC4xLTE0LjFMMzA1LDY5NS4ybDc2LjItMjUuOUwzNTUuNiw3MDVsNTYuMS0yOC4zbC0xNSwzNC4xbDYxLjktMzguNWw4LjMsMzguMWwxNi44LDE4LjMKCWwtNDIuNSw0My43bDQuNSwzNS44bDMzLTE0bDIxLjYsMjguOWwyMi4zLTI4LjNsMzIuNiwxNC45bDUuNy0zNS42bC00MS41LTQ0LjhsMTguNC0xOC45bDkuNS0zNy42bDYxLjQsMzguNWwtMTQuNC0zMy45bDU2LjEsMjguNwoJTDYyNC4xLDY3MGw3Ni4zLDI2LjFsLTM0LTMzLjVsNzAuMywxNGwtMjcuNi0yMmw3NC40LDJsLTI3LjUtMTYuMWw4MC45LTguOWwtNDMuMi0xNy43bDgwLjgtMjAuOGwtNTAtMTEuMWw3NS41LTMzLjdsLTU1LjktMy43CglsNjkuMi01MC44bC00OC44LDUuM2w1Ny45LTYyLjhsLTQzLjMsMTIuOGwzOS4xLTY5LjRsLTM1LjYsMThsMjIuNi03OGwtMjgsMjIuMmMwLDAtMS4xLTkwLjgtMS4xLTkwLjljMCwzLjctMTEsMTMuOC0xMy4yLDE2LjYKCWMtOC45LDExLjMtMTguMiwyMi4yLTI3LjEsMzMuNWMtNjgsODYuNi0xNTIuMiwxNzMuMS0yNDQuNSwyMzRjLTEwLjcsNy00NiwzNC01NS4yLDI0LjNjLTYuNC02LjctOC41LTI2LjgtMTEuMy0zNS45CgljMCwwLDEwLjctMy4zLDIxLDIuN2MwLDAsMC02LTUuOS0xMi4yYzAsMC0xNi45LTQtNjAtMS45Yy0yLjcsMC4xLTExLjgsNDUuNS0xMy4xLDUwLjhDNDY2LjgsNTYyLjgsNDY2LjUsNTYyLjcsNDY2LjIsNTYyLjd6Ii8+Cjwvc3ZnPgo=";

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

        // Register the settings
        register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_social_share_image');
    }

    public function settings_section_callback() {
        echo '<p>' . __('Configure the custom social share image URL for the SEOPress integration.', 'firehawkcrm-seopress') . '</p>';
    }

    public function social_share_image_callback() {
        $option = get_option('firehawkcrm_seopress_social_share_image');
        echo '<p>' . __('This image will be used as the social share image for tributes. If not set, the default image will be used.', 'firehawkcrm-seopress') . '</p>';
        echo '<input type="text" id="firehawkcrm_seopress_social_share_image" name="firehawkcrm_seopress_social_share_image" value="' . esc_attr($option) . '" />';
    }

    public function add_seopress_meta_tags() {
        // Check if SEOPress is active and available
        if (function_exists('seopress_get_service')) {
            $seopress_service = seopress_get_service('MetaTags');
            $single_tribute = new Single_Tribute();
            $single_tribute->detectClient();
            $client = $single_tribute->getClient();

            if ($client) {
                $meta_title = $single_tribute->getPageTitle();
                $meta_description = isset($client->content) ? strip_tags($client->content) : "Tribute for " . $client->firstName . " " . $client->lastName;
                $meta_image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . 'default-social.jpg');
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

    public function custom_seopress_titles_title($title) {
        // Customize the SEOPress title
        if (is_page(Single_Tribute::getSingleTributePageId())) {
            $single_tribute = new Single_Tribute();
            $single_tribute->detectClient();
            $title = $single_tribute->getPageTitle();
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
        }
        return $desc;
    }

    public function custom_seopress_og_image($image) {
        // Customize the SEOPress Open Graph image
        if (is_page(Single_Tribute::getSingleTributePageId())) {
            $image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . 'default-social.jpg');
        }
        return $image;
    }

}

new SEOPress_Tributes_Integration();