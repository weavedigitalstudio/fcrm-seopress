<?php
/**
 * Plugin Name:       FirehawkCRM Tributes - SEOPress (pro) Integration
 * Plugin URI:        https://github.com/weavedigitalstudio/fcrm-seopress/
 * Description:       Adds SEOPress & SEOPress Pro integration and meta/social tags to the FireHawk CRM Tributes plugin.
 * Version:           1.5.0
 * Author:            Weave Digital Studio, Gareth Bissland
 * License:           GPL-3.0+
 * Copyright (C) 2024 Weave Digital Studio Ltd
 * GitHub Plugin URI: https://github.com/weavedigitalstudio/fcrm-seopress/
 * Primary Branch:    main
 * Requires at least: 6.0
 * Requires PHP:      7.2
 */

namespace WDS\FirehawkCRM\SEOPress;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if FirehawkCRM Tributes plugin is active
if (in_array('fcrm-tributes/fcrm-tributes.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    class SEOPress_Tributes_Integration {
        /**
         * Default settings and constants
         */
        private const DEFAULT_TITLE_SUFFIX = ' - Funeral Notice';
        private const DEFAULT_SOCIAL_IMAGE = 'funeral-notice-social-share.jpg';

        public function __construct() {
            // Initialize hooks when the template is being loaded
            add_action('template_redirect', array($this, 'initialize_hooks'));
            // Add admin menu and settings
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
            // Add media uploader scripts
            add_action('admin_enqueue_scripts', array($this, 'enqueue_media_scripts'));
        }

        public function enqueue_media_scripts($hook) {
            if ($hook != 'toplevel_page_firehawkcrm-seopress-integration') {
                return;
            }
            wp_enqueue_media();
            wp_enqueue_style('firehawkcrm-seopress-admin', plugins_url('css/admin.css', __FILE__));
        }

        public function initialize_hooks() {
            // Check if the current page is a single tribute page
            if (is_page(\Single_Tribute::getSingleTributePageId())) {
                // Add custom meta tags to the head section
                add_action('wp_head', array($this, 'add_seopress_meta_tags'));
                // Customize SEOPress title and description
                add_filter('seopress_titles_title', array($this, 'custom_seopress_titles_title'));
                add_filter('seopress_titles_desc', array($this, 'custom_seopress_titles_desc'));
                // Customize SEOPress Open Graph image
                add_filter('seopress_social_og_thumb', array($this, 'custom_seopress_og_image'));
            }
        }

        public function add_admin_menu() {
            // Add the settings page
            add_menu_page(
                'FirehawkCRM SEOPress Integration', 
                'FH SEOPress Integration', 
                'manage_options', 
                'firehawkcrm-seopress-integration', 
                array($this, 'settings_page'), 
                'data:image/svg+xml;base64,' . 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDEwMDAgMTAwMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiNGRkZGRkY7fQoJLnN0MXtvcGFjaXR5OjAuODU7ZmlsbDp1cmwoI1NWR0lEXzFfKTt9Cgkuc3Qye29wYWNpdHk6MC41NTtmaWxsOiNGRkZGRkY7fQoJLnN0M3tvcGFjaXR5OjAuNjU7ZmlsbDojRkZGRkZGO30KCS5zdDR7ZmlsbDp1cmwoI1NWR0lEXzJfKTt9Cgkuc3Q1e29wYWNpdHk6MC41OTtmaWxsOiNGRkZGRkY7fQoJLnN0NntvcGFjaXR5OjAuNzY7ZmlsbDojRkZGRkZGO30KCS5zdDd7ZmlsbDp1cmwoI1NWR0lEXzNfKTt9Cgkuc3Q4e2ZpbGw6dXJsKCNTVkdJRF80Xyk7fQoJLnN0OXtmaWxsOnVybCgjU1ZHSURfNV8pO30KCS5zdDEwe2ZpbGw6dXJsKCNTVkdJRF82Xyk7fQoJLnN0MTF7ZmlsbDp1cmwoI1NWR0lEXzdfKTt9Cgkuc3QxMntmaWxsOnVybCgjU1ZHSURfOF8pO30KCS5zdDEze2ZpbGw6dXJsKCNTVkdJRF85Xyk7fQoJLnN0MTR7ZmlsbDp1cmwoI1NWR0lEXzEwXyk7fQoJLnN0MTV7ZmlsbDp1cmwoI1NWR0lEXzExXyk7fQoJLnN0MTZ7ZmlsbDp1cmwoI1NWR0lEXzEyXyk7fQoJLnN0MTd7ZmlsbDp1cmwoI1NWR0lEXzEzXyk7fQoJLnN0MTh7ZmlsbDp1cmwoI1NWR0lEXzE0Xyk7fQoJLnN0MTl7ZmlsbDp1cmwoI1NWR0lEXzE1Xyk7fQoJLnN0MjB7b3BhY2l0eTowLjM1O2ZpbGw6I0ZGRkZGRjt9Cgkuc3QyMXtvcGFjaXR5OjAuODU7ZmlsbDp1cmwoI1NWR0lEXzE2Xyk7fQoJLnN0MjJ7b3BhY2l0eTowLjg1O2ZpbGw6dXJsKCNTVkdJRF8xN18pO30KCS5zdDIze29wYWNpdHk6MC41MTtmaWxsOiNGRkZGRkY7fQoJLnN0MjR7ZmlsbDp1cmwoI1NWR0lEXzE4Xyk7fQoJLnN0MjV7ZmlsbDp1cmwoI1NWR0lEXzE5Xyk7fQoJLnN0MjZ7ZmlsbDp1cmwoI1NWR0lEXzIwXyk7fQoJLnN0Mjd7ZmlsbDp1cmwoI1NWR0lEXzIxXyk7fQoJLnN0Mjh7ZmlsbDp1cmwoI1NWR0lEXzIyXyk7fQoJLnN0Mjl7ZmlsbDp1cmwoI1NWR0lEXzIzXyk7fQoJLnN0MzB7ZmlsbDp1cmwoI1NWR0lEXzI0Xyk7fQoJLnN0MzF7ZmlsbDp1cmwoI1NWR0lEXzI1Xyk7fQoJLnN0MzJ7ZmlsbDp1cmwoI1NWR0lEXzI2Xyk7fQoJLnN0MzN7ZmlsbDp1cmwoI1NWR0lEXzI3Xyk7fQoJLnN0MzR7ZmlsbDp1cmwoI1NWR0lEXzI4Xyk7fQoJLnN0MzV7ZmlsbDp1cmwoI1NWR0lEXzI5Xyk7fQoJLnN0MzZ7ZmlsbDp1cmwoI1NWR0lEXzMwXyk7fQoJLnN0Mzd7ZmlsbDp1cmwoI1NWR0lEXzMxXyk7fQoJLnN0Mzh7b3BhY2l0eTowLjg1O2ZpbGw6dXJsKCNTVkdJRF8zMl8pO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTQ2Ni4yLDU2Mi43Yy00NC44LTguNC03Ny4xLTQzLjEtMTA5LjktNzJjLTgzLjUtNzMuNy0xNTcuNS0xNTIuOS0yMjYuOC0yNDBsLTEuMiw5MC45bC0yNy44LTIyLjRsMjIsNzcuNgoJbC0zNS4zLTE3LjhsMzksNjkuNGwtNDIuOC0xMi45bDU2LjgsNjIuNGwtNDguMi00LjlsNjkuMiw1MC42bC01NS44LDMuOWw3NSwzMy41TDEzMSw1OTIuM2w4MC41LDIwLjhsLTQyLjcsMTcuNmw4MC42LDkuMQoJbC0yNy4xLDE2LjFsNzQuMS0xLjhsLTI3LjUsMjEuOWw3MC4xLTE0LjFMMzA1LDY5NS4ybDc2LjItMjUuOUwzNTUuNiw3MDVsNTYuMS0yOC4zbC0xNSwzNC4xbDYxLjktMzguNWw4LjMsMzguMWwxNi44LDE4LjMKCWwtNDIuNSw0My43bDQuNSwzNS44bDMzLTE0bDIxLjYsMjguOWwyMi4zLTI4LjNsMzIuNiwxNC45bDUuNy0zNS42bC00MS41LTQ0LjhsMTguNC0xOC45bDkuNS0zNy42bDYxLjQsMzguNWwtMTQuNC0zMy45bDU2LjEsMjguNwoJTDYyNC4xLDY3MGw3Ni4zLDI2LjFsLTM0LTMzLjVsNzAuMywxNGwtMjcuNi0yMmw3NC40LDJsLTI3LjUtMTYuMWw4MC45LTguOWwtNDMuMi0xNy43bDgwLjgtMjAuOGwtNTAtMTEuMWw3NS41LTMzLjdsLTU1LjktMy43CglsNjkuMi01MC44bC00OC44LDUuM2w1Ny45LTYyLjhsLTQzLjMsMTIuOGwzOS4xLTY5LjRsLTM1LjYsMThsMjIuNi03OGwtMjgsMjIuMmMwLDAtMS4xLTkwLjgtMS4xLTkwLjljMCwzLjctMTEsMTMuOC0xMy4yLDE2LjYKCWMtOC45LDExLjMtMTguMiwyMi4yLTI3LjEsMzMuNWMtNjgsODYuNi0xNTIuMiwxNzMuMS0yNDQuNSwyMzRjLTEwLjcsNy00NiwzNC01NS4yLDI0LjNjLTYuNC02LjctOC41LTI2LjgtMTEuMy0zNS45CgljMCwwLDEwLjctMy4zLDIxLDIuN2MwLDAsMC02LTUuOS0xMi4yYzAsMC0xNi45LTQtNjAtMS45Yy0yLjcsMC4xLTExLjgsNDUuNS0xMy4xLDUwLjhDNDY2LjgsNTYyLjgsNDY2LjUsNTYyLjcsNDY2LjIsNTYyLjd6Ii8+Cjwvc3ZnPgo='
            );
        }

        public function settings_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            ?>
            <div class="wrap">
                <h2>FirehawkCRM Tributes SEOPress Integration</h2>
                
                <div class="about-text" style="margin: 1em 0;">
                    <p><?php _e('If you use SEOPress on your WordPress funeral website, this replaces the current bundled Yoast SEO integration of the FireHawkCRM Tributes plugin with added support for SEOPress and SEOPress Pro.', 'firehawkcrm-seopress'); ?></p>
                </div>

                <form method="post" action="options.php">
                    <?php settings_fields('firehawkcrm_seopress_settings'); ?>
                    <?php do_settings_sections('firehawkcrm_seopress_settings'); ?>
                    <?php submit_button('Save Changes'); ?>
                </form>
            </div>
            <?php
        }

        public function register_settings() {
            // Add settings section
            add_settings_section(
                'firehawkcrm_seopress_section',
                __('SEOPress Integration Settings', 'firehawkcrm-seopress'),
                array($this, 'settings_section_callback'),
                'firehawkcrm_seopress_settings'
            );

            // Add fields
            add_settings_field(
                'firehawkcrm_seopress_social_share_image',
                __('Custom Social Share Image', 'firehawkcrm-seopress'),
                array($this, 'social_share_image_callback'),
                'firehawkcrm_seopress_settings',
                'firehawkcrm_seopress_section'
            );

            add_settings_field(
                'firehawkcrm_seopress_title_suffix',
                __('Custom Title Suffix', 'firehawkcrm-seopress'),
                array($this, 'title_suffix_callback'),
                'firehawkcrm_seopress_settings',
                'firehawkcrm_seopress_section'
            );

            // Register settings
            register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_social_share_image', 
                ['sanitize_callback' => 'esc_url_raw']
            );
            register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_title_suffix',
                ['sanitize_callback' => 'sanitize_text_field']
            );
        }

        public function settings_section_callback() {
            echo '<p>' . __('Configure the settings below to customize how your tributes appear in search results and social media.', 'firehawkcrm-seopress') . '</p>';
        }

        public function social_share_image_callback() {
            $option = get_option('firehawkcrm_seopress_social_share_image');
            $default_image = plugin_dir_url(__FILE__) . self::DEFAULT_SOCIAL_IMAGE;
            $current_image = $option ? $option : $default_image;
            ?>
            <div class="image-preview-wrapper">
                <img id="image-preview" src="<?php echo esc_url($current_image); ?>" 
                     style="max-width: 300px; display: block; margin-bottom: 10px;">
                <p class="description" <?php echo $option ? 'style="display: none;"' : ''; ?>>
                    <?php _e('Currently using default image. Upload a custom image to override.', 'firehawkcrm-seopress'); ?>
                </p>
            </div>
            <input type="text" 
                   id="firehawkcrm_seopress_social_share_image" 
                   name="firehawkcrm_seopress_social_share_image" 
                   value="<?php echo esc_attr($option); ?>" 
                   class="regular-text">
            <input type="button" 
                   id="upload-image-button" 
                   class="button" 
                   value="<?php _e('Upload Image', 'firehawkcrm-seopress'); ?>">
            <input type="button" 
                   id="remove-image-button" 
                   class="button" 
                   value="<?php _e('Reset to Default', 'firehawkcrm-seopress'); ?>" 
                   <?php echo !$option ? 'style="display: none;"' : ''; ?>>

            <script>
            jQuery(document).ready(function($) {
                var mediaUploader;
                var defaultImage = '<?php echo esc_url($default_image); ?>';
                
                $('#upload-image-button').on('click', function(e) {
                    e.preventDefault();
                    
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    
                    mediaUploader = wp.media({
                        title: '<?php _e('Choose Social Share Image', 'firehawkcrm-seopress'); ?>',
                        button: {
                            text: '<?php _e('Use this image', 'firehawkcrm-seopress'); ?>'
                        },
                        multiple: false
                    });
                    
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#firehawkcrm_seopress_social_share_image').val(attachment.url);
                        $('#image-preview').attr('src', attachment.url);
                        $('#remove-image-button').show();
                        $('.description').hide();
                    });
                    
                    mediaUploader.open();
                });
                
                $('#remove-image-button').on('click', function() {
                    $('#firehawkcrm_seopress_social_share_image').val('');
                    $('#image-preview').attr('src', defaultImage);
                    $(this).hide();
                    $('.description').show();
                });
            });
            </script>
            <?php
        }

        public function title_suffix_callback() {
            $option = get_option('firehawkcrm_seopress_title_suffix', self::DEFAULT_TITLE_SUFFIX);
            echo '<input type="text" id="firehawkcrm_seopress_title_suffix" name="firehawkcrm_seopress_title_suffix" value="' . esc_attr($option) . '" class="regular-text">';
        }

        public function add_seopress_meta_tags() {
            // Check if SEOPress is active and available
            if (function_exists('seopress_get_service')) {
                $seopress_service = seopress_get_service('MetaTags');
                if ($seopress_service) {
                    $single_tribute = new \Single_Tribute();
                    $single_tribute->detectClient();
                    $client = $single_tribute->getClient();

                    if ($client) {
                        $meta_title = $this->get_custom_meta_title($single_tribute);
                        $meta_description = isset($client->content) ? strip_tags($client->content) : "Tribute for " . (isset($client->firstName) ? $client->firstName : '') . " " . (isset($client->lastName) ? $client->lastName : '');
                        // Clean the description
                        $meta_description = str_replace('"', "'", $meta_description); // Replace double quotes with single quotes
                        $meta_description = str_replace(array("\r", "\n"), ' ', $meta_description); // Remove newlines
                        $meta_description = preg_replace('/\s+/', ' ', trim($meta_description)); // Remove extra spaces
                        
                        $meta_image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . self::DEFAULT_SOCIAL_IMAGE);
                        $current_url = $single_tribute->getPageUrl();

                        // Set the SEOPress meta tags
                        $seopress_service->setTitle($meta_title);
                        $seopress_service->setDescription($meta_description);
                        $seopress_service->setOgTitle($meta_title);
                        $seopress_service->setOgDescription($meta_description);
                        $seopress_service->setOgImage($meta_image);
                        $seopress_service->setOgUrl($current_url);
                    } else {
                        // Handle the case where client data is not available
                        $meta_title = get_bloginfo('name') . ' - Tribute';
                        $meta_description = 'This tribute page is currently unavailable.';
                        $meta_image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . self::DEFAULT_SOCIAL_IMAGE);
                        $current_url = home_url();

                        // Set default SEOPress meta tags
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
            if (is_page(\Single_Tribute::getSingleTributePageId())) {
                $single_tribute = new \Single_Tribute();
                $single_tribute->detectClient();
                $title = $this->get_custom_meta_title($single_tribute);
            }
            return $title;
        }

        public function custom_seopress_titles_desc($desc) {
            // Customize the SEOPress description
            if (is_page(\Single_Tribute::getSingleTributePageId())) {
                $single_tribute = new \Single_Tribute();
                $single_tribute->detectClient();
                $client = $single_tribute->getClient();
                if ($client) {
                    $desc = isset($client->content) ? strip_tags($client->content) : "Tribute for " . (isset($client->firstName) ? $client->firstName : '') . " " . (isset($client->lastName) ? $client->lastName : '');
                    // Clean the description
                    $desc = str_replace('"', "'", $desc); // Replace double quotes with single quotes
                    $desc = str_replace(array("\r", "\n"), ' ', $desc); // Remove newlines
                    $desc = preg_replace('/\s+/', ' ', trim($desc)); // Remove extra spaces
                } else {
                    $desc = 'This tribute page is currently unavailable.';
                }
            }
            return $desc;
        }

        public function custom_seopress_og_image($image) {
            // Customize the SEOPress Open Graph image
            $single_tribute = new \Single_Tribute();
            if (is_page($single_tribute->getSingleTributePageId())) {
                $image = get_option('firehawkcrm_seopress_social_share_image', plugin_dir_url(__FILE__) . self::DEFAULT_SOCIAL_IMAGE);
                return '<meta property="og:image" content="' . esc_url($image) . '" />';
            }
            return $image;
        }

        private function get_custom_meta_title($single_tribute) {
            $client = $single_tribute->getClient();
                if ($client) {
                    $clientName = isset($client->fullName) ? $client->fullName : (isset($client->firstName) ? $client->firstName : '') . ' ' . (isset($client->lastName) ? $client->lastName : '');
                    // Clean the client name
                    $clientName = str_replace('"', "'", $clientName); // Replace double quotes with single quotes
                    $clientName = trim($clientName); // Remove any leading/trailing spaces
                } else {
                    $clientName = 'Tribute';
                }
                $customSuffix = get_option('firehawkcrm_seopress_title_suffix', self::DEFAULT_TITLE_SUFFIX);
                $siteTitle = get_bloginfo('name');
                $customMetaTitle = $clientName . $customSuffix . ' | ' . $siteTitle;
                return $customMetaTitle;
            }
        }

    new SEOPress_Tributes_Integration();
}