<?php
/**
 * Plugin Name:       FirehawkCRM Tributes - SEOPress (pro) Integration
 * Plugin URI:        https://github.com/weavedigitalstudio/fcrm-seopress/
 * Description:       Adds SEOPress (& SEOPress Pro) integration and correct meta/social tags and social images to the FireHawk CRM Tributes plugin.
 * Version:           1.4.0
 * Author:            Weave Digital Studio, Gareth Bissland
 * Author URI:        https://weave.co.nz
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * GitHub Plugin URI: weavedigitalstudio/fcrm-seopress/
 * Primary Branch:    main
 * Requires at least: 6.0
 * Requires PHP:      7.2
 */

namespace FirehawkCRM\SEOPress;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Plugin {
    private static $instance = null;
    
    // Constants for default values and settings
    const DEFAULT_TITLE_SUFFIX = ' - Funeral Notice';
    const DEFAULT_SOCIAL_IMAGE = 'funeral-notice-social-share.jpg';
    private const MENU_ICON = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDEwMDAgMTAwMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiNGRkZGRkY7fQoJLnN0MXtvcGFjaXR5OjAuODU7ZmlsbDp1cmwoI1NWR0lEXzFfKTt9Cgkuc3Qye29wYWNpdHk6MC41NTtmaWxsOiNGRkZGRkY7fQoJLnN0M3tvcGFjaXR5OjAuNjU7ZmlsbDojRkZGRkZGO30KCS5zdDR7ZmlsbDp1cmwoI1NWR0lEXzJfKTt9Cgkuc3Q1e29wYWNpdHk6MC41OTtmaWxsOiNGRkZGRkY7fQoJLnN0NntvcGFjaXR5OjAuNzY7ZmlsbDojRkZGRkZGO30KCS5zdDd7ZmlsbDp1cmwoI1NWR0lEXzNfKTt9Cgkuc3Q4e2ZpbGw6dXJsKCNTVkdJRF80Xyk7fQoJLnN0OXtmaWxsOnVybCgjU1ZHSURfNV8pO30KCS5zdDEwe2ZpbGw6dXJsKCNTVkdJRF82Xyk7fQoJLnN0MTF7ZmlsbDp1cmwoI1NWR0lEXzdfKTt9Cgkuc3QxMntmaWxsOnVybCgjU1ZHSURfOF8pO30KCS5zdDEze2ZpbGw6dXJsKCNTVkdJRF85Xyk7fQoJLnN0MTR7ZmlsbDp1cmwoI1NWR0lEXzEwXyk7fQoJLnN0MTV7ZmlsbDp1cmwoI1NWR0lEXzExXyk7fQoJLnN0MTZ7ZmlsbDp1cmwoI1NWR0lEXzEyXyk7fQoJLnN0MTd7ZmlsbDp1cmwoI1NWR0lEXzEzXyk7fQoJLnN0MTh7ZmlsbDp1cmwoI1NWR0lEXzE0Xyk7fQoJLnN0MTl7ZmlsbDp1cmwoI1NWR0lEXzE1Xyk7fQoJLnN0MjB7b3BhY2l0eTowLjM1O2ZpbGw6I0ZGRkZGRjt9Cgkuc3QyMXtvcGFjaXR5OjAuODU7ZmlsbDp1cmwoI1NWR0lEXzE2Xyk7fQoJLnN0MjJ7b3BhY2l0eTowLjg1O2ZpbGw6dXJsKCNTVkdJRF8xN18pO30KCS5zdDIze29wYWNpdHk6MC41MTtmaWxsOiNGRkZGRkY7fQoJLnN0MjR7ZmlsbDp1cmwoI1NWR0lEXzE4Xyk7fQoJLnN0MjV7ZmlsbDp1cmwoI1NWR0lEXzE5Xyk7fQoJLnN0MjZ7ZmlsbDp1cmwoI1NWR0lEXzIwXyk7fQoJLnN0Mjd7ZmlsbDp1cmwoI1NWR0lEXzIxXyk7fQoJLnN0Mjh7ZmlsbDp1cmwoI1NWR0lEXzIyXyk7fQoJLnN0Mjl7ZmlsbDp1cmwoI1NWR0lEXzIzXyk7fQoJLnN0MzB7ZmlsbDp1cmwoI1NWR0lEXzI0Xyk7fQoJLnN0MzF7ZmlsbDp1cmwoI1NWR0lEXzI1Xyk7fQoJLnN0MzJ7ZmlsbDp1cmwoI1NWR0lEXzI2Xyk7fQoJLnN0MzN7ZmlsbDp1cmwoI1NWR0lEXzI3Xyk7fQoJLnN0MzR7ZmlsbDp1cmwoI1NWR0lEXzI4Xyk7fQoJLnN0MzV7ZmlsbDp1cmwoI1NWR0lEXzI5Xyk7fQoJLnN0MzZ7ZmlsbDp1cmwoI1NWR0lEXzMwXyk7fQoJLnN0Mzd7ZmlsbDp1cmwoI1NWR0lEXzMxXyk7fQoJLnN0Mzh7b3BhY2l0eTowLjg1O2ZpbGw6dXJsKCNTVkdJRF8zMl8pO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTQ2Ni4yLDU2Mi43Yy00NC44LTguNC03Ny4xLTQzLjEtMTA5LjktNzJjLTgzLjUtNzMuNy0xNTcuNS0xNTIuOS0yMjYuOC0yNDBsLTEuMiw5MC45bC0yNy44LTIyLjRsMjIsNzcuNgoJbC0zNS4zLTE3LjhsMzksNjkuNGwtNDIuOC0xMi45bDU2LjgsNjIuNGwtNDguMi00LjlsNjkuMiw1MC42bC01NS44LDMuOWw3NSwzMy41TDEzMSw1OTIuM2w4MC41LDIwLjhsLTQyLjcsMTcuNmw4MC42LDkuMQoJbC0yNy4xLDE2LjFsNzQuMS0xLjhsLTI3LjUsMjEuOWw3MC4xLTE0LjFMMzA1LDY5NS4ybDc2LjItMjUuOUwzNTUuNiw3MDVsNTYuMS0yOC4zbC0xNSwzNC4xbDYxLjktMzguNWw4LjMsMzguMWwxNi44LDE4LjMKCWwtNDIuNSw0My43bDQuNSwzNS44bDMzLTE0bDIxLjYsMjguOWwyMi4zLTI4LjNsMzIuNiwxNC45bDUuNy0zNS42bC00MS41LTQ0LjhsMTguNC0xOC45bDkuNS0zNy42bDYxLjQsMzguNWwtMTQuNC0zMy45bDU2LjEsMjguNwoJTDYyNC4xLDY3MGw3Ni4zLDI2LjFsLTM0LTMzLjVsNzAuMywxNGwtMjcuNi0yMmw3NC40LDJsLTI3LjUtMTYuMWw4MC45LTguOWwtNDMuMi0xNy43bDgwLjgtMjAuOGwtNTAtMTEuMWw3NS41LTMzLjdsLTU1LjktMy43CglsNjkuMi01MC44bC00OC44LDUuM2w1Ny45LTYyLjhsLTQzLjMsMTIuOGwzOS4xLTY5LjRsLTM1LjYsMThsMjIuNi03OGwtMjgsMjIuMmMwLDAtMS4xLTkwLjgtMS4xLTkwLjljMCwzLjctMTEsMTMuOC0xMy4yLDE2LjYKCWMtOC45LDExLjMtMTguMiwyMi4yLTI3LjEsMzMuNWMtNjgsODYuNi0xNTIuMiwxNzMuMS0yNDQuNSwyMzRjLTEwLjcsNy00NiwzNC01NS4yLDI0LjNjLTYuNC02LjctOC41LTI2LjgtMTEuMy0zNS45CgljMCwwLDEwLjctMy4zLDIxLDIuN2MwLDAsMC02LTUuOS0xMi4yYzAsMC0xNi45LTQtNjAtMS45Yy0yLjcsMC4xLTExLjgsNDUuNS0xMy4xLDUwLjhDNDY2LjgsNTYyLjgsNDY2LjUsNTYyLjcsNDY2LjIsNTYyLjd6Ii8+Cjwvc3ZnPgo=";

    private function __construct() {
        // Check dependencies
        if (!$this->check_dependencies()) {
            return;
        }
        
        $this->init_hooks();
    }

    private function check_dependencies(): bool {
        if (!class_exists('Single_Tribute')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>FirehawkCRM Tributes plugin is required for the SEOPress Integration to work.</p></div>';
            });
            return false;
        }
        
        if (!function_exists('seopress_get_service')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>SEOPress plugin is required for this integration to work.</p></div>';
            });
            return false;
        }
        
        // Check if Yoast SEO is active and warn user
        if (defined('WPSEO_VERSION')) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>Both Yoast SEO and SEOPress are active. To avoid conflicts, please disable Yoast SEO\'s integration in the FirehawkCRM Tributes settings.</p></div>';
            });
            
            // Optional: Disable Yoast's tribute integration
            add_action('plugins_loaded', function() {
                remove_filter('wpseo_canonical', array($GLOBALS['fcrm_tributes']->get_public(), 'update_yoast_single_tribute_canonical_url'));
                remove_filter('wpseo_title', array($GLOBALS['fcrm_tributes']->get_public(), 'update_yoast_single_tribute_title'));
                remove_filter('wpseo_opengraph_url', array($GLOBALS['fcrm_tributes']->get_public(), 'update_yoast_single_tribute_opengraph_url'));
                remove_filter('wpseo_opengraph_title', array($GLOBALS['fcrm_tributes']->get_public(), 'update_yoast_single_tribute_opengraph_title'));
                remove_filter('wpseo_opengraph_image', array($GLOBALS['fcrm_tributes']->get_public(), 'update_yoast_single_tribute_opengraph_image'));
                remove_filter('wpseo_opengraph_desc', array($GLOBALS['fcrm_tributes']->get_public(), 'update_yoast_single_tribute_opengraph_desc'));
                remove_filter('wpseo_sitemap_index', array($GLOBALS['fcrm_tributes']->get_public(), 'yoast_add_to_sitemap_index'));
            }, 20); // Run after Yoast initializes
        }
        
        return true;
    }

    
    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    
    private function init_hooks(): void {
        add_action('init', [$this, 'load_textdomain']);
        add_action('template_redirect', [$this, 'initialize_seo_hooks']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }
    
    public function load_textdomain(): void {
        load_plugin_textdomain('firehawkcrm-seopress', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function initialize_seo_hooks(): void {
        if (!$this->is_tribute_page()) {
            return;
        }
        
        add_action('wp_head', [$this, 'add_seo_meta_tags']);
        add_filter('seopress_titles_title', [$this, 'customize_seo_title']);
        add_filter('seopress_titles_desc', [$this, 'customize_seo_description']);
        add_filter('seopress_social_og_thumb', [$this, 'customize_og_image']);
    }
    
    private function is_tribute_page(): bool {
        return is_page(\Single_Tribute::getSingleTributePageId());
    }
    
    public function add_seo_meta_tags(): void {
        $seo_service = seopress_get_service('MetaTags');
        if (!$seo_service) {
            return;
        }
        
        $tribute_data = $this->get_tribute_data();
        
        $seo_service->setTitle($tribute_data['title']);
        $seo_service->setDescription($tribute_data['description']);
        $seo_service->setOgTitle($tribute_data['title']);
        $seo_service->setOgDescription($tribute_data['description']);
        $seo_service->setOgImage($tribute_data['image']);
        $seo_service->setOgUrl($tribute_data['url']);
    }
    
    private function get_tribute_data(): array {
        $single_tribute = new \Single_Tribute();
        $single_tribute->detectClient();
        $client = $single_tribute->getClient();
        
        if (!$client) {
            return $this->get_default_tribute_data();
        }
        
        return [
            'title' => $this->generate_meta_title($client),
            'description' => $this->generate_meta_description($client),
            'image' => $this->get_social_image_url(),
            'url' => $single_tribute->getPageUrl()
        ];
    }
    
    private function get_default_tribute_data(): array {
        return [
            'title' => sprintf('%s - Tribute', get_bloginfo('name')),
            'description' => __('This tribute page is currently unavailable.', 'firehawkcrm-seopress'),
            'image' => $this->get_social_image_url(),
            'url' => home_url()
        ];
    }
    
    private function generate_meta_title($client): string {
        $client_name = $client->fullName ?? 
            trim(($client->firstName ?? '') . ' ' . ($client->lastName ?? ''));
            
        $suffix = get_option('firehawkcrm_seopress_title_suffix', self::DEFAULT_TITLE_SUFFIX);
        
        return sprintf(
            '%s%s | %s',
            esc_html($client_name),
            esc_html($suffix),
            get_bloginfo('name')
        );
    }
    
    private function generate_meta_description($client): string {
        if (isset($client->content)) {
            $description = strip_tags($client->content);
        } else {
            $description = sprintf(
                'Tribute for %s %s',
                $client->firstName ?? '',
                $client->lastName ?? ''
            );
        }
        
        return str_replace(["\r", "\n"], ' ', $description);
    }
    
    private function get_social_image_url(): string {
        return get_option(
            'firehawkcrm_seopress_social_share_image',
            plugin_dir_url(__FILE__) . self::DEFAULT_SOCIAL_IMAGE
        );
    }
    
    // Admin settings methods...
    public function add_admin_menu(): void {
        add_menu_page(
            __('FirehawkCRM SEOPress Integration', 'firehawkcrm-seopress'),
            __('FH SEOPress Integration', 'firehawkcrm-seopress'),
            'manage_options',
            'firehawkcrm-seopress-integration',
            [$this, 'render_settings_page'],
            $this->get_menu_icon()
        );
    }
    
   private function get_menu_icon(): string {
        return 'data:image/svg+xml;base64,' . self::MENU_ICON;
    }
    
    public function render_settings_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        include plugin_dir_path(__FILE__) . 'templates/admin-settings.php';
    }

    public function register_settings(): void {
    register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_social_share_image');
    register_setting('firehawkcrm_seopress_settings', 'firehawkcrm_seopress_title_suffix');
    
    add_settings_section(
        'firehawkcrm_seopress_section',
        __('SEOPress Integration Settings', 'firehawkcrm-seopress'),
        [$this, 'render_settings_section'],
        'firehawkcrm_seopress_settings'
    );
}

public function render_settings_section(): void {
    echo '<p>' . __('Configure the custom social share image URL and title suffix for the SEOPress integration.', 'firehawkcrm-seopress') . '</p>';
}

// Initialize the plugin
add_action('plugins_loaded', function() {
    Plugin::get_instance();
});
