<?php
/**
 * Admin settings template for FirehawkCRM SEOPress Integration
 *
 * @package FirehawkCRM\SEOPress
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Ensure user has correct permissions
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'firehawkcrm-seopress'));
}

// Get current values
$social_image = get_option('firehawkcrm_seopress_social_share_image', '');
$title_suffix = get_option('firehawkcrm_seopress_title_suffix', ' - Funeral Notice');

// Get preview data if available
$preview_title = 'John Smith' . $title_suffix . ' | ' . get_bloginfo('name');
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php settings_errors(); ?>

    <div class="notice notice-info">
        <p>
            <?php _e('Configure how your tribute pages appear on social media and search engines.', 'firehawkcrm-seopress'); ?>
        </p>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields('firehawkcrm_seopress_settings');
        do_settings_sections('firehawkcrm_seopress_settings');
        ?>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Social Media Image', 'firehawkcrm-seopress'); ?></h2>
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="firehawkcrm_seopress_social_share_image">
                            <?php _e('Default Share Image', 'firehawkcrm-seopress'); ?>
                        </label>
                    </th>
                    <td>
                        <div class="social-image-preview" style="margin-bottom: 10px;">
                            <?php if (!empty($social_image)): ?>
                                <img src="<?php echo esc_url($social_image); ?>" 
                                     alt="<?php _e('Social Share Preview', 'firehawkcrm-seopress'); ?>"
                                     style="max-width: 300px; height: auto;" />
                            <?php endif; ?>
                        </div>
                        
                        <input type="text" 
                               id="firehawkcrm_seopress_social_share_image"
                               name="firehawkcrm_seopress_social_share_image"
                               value="<?php echo esc_attr($social_image); ?>"
                               class="regular-text"
                               placeholder="https://" />
                        
                        <button type="button" 
                                class="button-secondary"
                                id="upload_image_button">
                            <?php _e('Choose Image', 'firehawkcrm-seopress'); ?>
                        </button>
                        
                        <p class="description">
                            <?php _e('This image will be used when tribute pages are shared on social media. Recommended size: 1200x630 pixels.', 'firehawkcrm-seopress'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Title Settings', 'firehawkcrm-seopress'); ?></h2>
            
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="firehawkcrm_seopress_title_suffix">
                            <?php _e('Title Suffix', 'firehawkcrm-seopress'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text"
                               id="firehawkcrm_seopress_title_suffix"
                               name="firehawkcrm_seopress_title_suffix"
                               value="<?php echo esc_attr($title_suffix); ?>"
                               class="regular-text" />
                        
                        <p class="description">
                            <?php _e('This text will be added after the person\'s name in page titles.', 'firehawkcrm-seopress'); ?>
                        </p>
                        
                        <div class="title-preview" style="margin-top: 10px; padding: 10px; background: #f0f0f1; border-radius: 4px;">
                            <strong><?php _e('Preview:', 'firehawkcrm-seopress'); ?></strong><br>
                            <span id="title-preview-text">
                                <?php echo esc_html($preview_title); ?>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php submit_button(); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Media uploader
    $('#upload_image_button').click(function(e) {
        e.preventDefault();
        
        var image = wp.media({ 
            title: '<?php _e('Select or Upload Social Share Image', 'firehawkcrm-seopress'); ?>',
            multiple: false
        }).open()
        .on('select', function(e) {
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.get('url');
            $('#firehawkcrm_seopress_social_share_image').val(image_url);
            
            // Update preview
            $('.social-image-preview').html(
                '<img src="' + image_url + '" style="max-width: 300px; height: auto;" />'
            );
        });
    });

    // Live title preview
    $('#firehawkcrm_seopress_title_suffix').on('input', function() {
        var suffix = $(this).val();
        var preview = 'John Smith' + suffix + ' | <?php echo esc_js(get_bloginfo('name')); ?>';
        $('#title-preview-text').text(preview);
    });
});
</script>

<style>
.card {
    padding: 20px;
    background: white;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    border: 1px solid #c3c4c7;
}

.title-preview {
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
}

.social-image-preview {
    border: 1px solid #dcdcde;
    padding: 10px;
    display: inline-block;
    background: #f0f0f1;
    border-radius: 4px;
}
</style>
