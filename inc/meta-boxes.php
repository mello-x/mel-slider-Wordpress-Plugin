<?php

function mel_slider_meta_box() {
    $enabled_post_types = get_enabled_mel_slider_post_types();
    
    foreach ($enabled_post_types as $post_type) {
        add_meta_box('mel_slider_meta_box', 'Mel Slider Options', 'mel_slider_meta_box_callback', $post, 'normal', 'high');
    }
}
add_action('add_meta_boxes', 'mel_slider_meta_box');


function get_enabled_mel_slider_post_types() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mel_slider_options';

    return $wpdb->get_col("SELECT post_type FROM $table_name WHERE checked = 1");
}

function mel_slider_meta_box_callback($post) {
    $image_urls = get_post_meta($post->ID, '_mel_slider_image_urls', true);
    $text_urls = get_post_meta($post->ID, '_mel_slider_text_urls', true);

    wp_nonce_field('mel_slider_save_meta', 'mel_slider_nonce');
    ?>
    <div class="mel-slider-repeater">
        <?php if ($image_urls && $text_urls) : ?>
            <?php for ($i = 0; $i < count($image_urls); $i++) : ?>
                <div class="mel-slider-row">
                    <p>
                        <label for="mel_slider_image_url_<?php echo $i; ?>">Choose Image:</label>
                        <input type="text" name="mel_slider_image_urls[]" value="<?php echo esc_attr($image_urls[$i]); ?>" style="width: 70%;">
                        <button class="button mel-slider-media-button" data-field="mel_slider_image_urls[]">Choose Media</button>
                    </p>
                    <p>
                        <label for="mel_slider_text_url_<?php echo $i; ?>">Video URL:</label>
                        <input type="text" name="mel_slider_text_urls[]" value="<?php echo esc_attr($text_urls[$i]); ?>" style="width: 70%;">
                        <button class="button mel-slider-remove-row">Remove Row</button>
                    </p>
                </div>
            <?php endfor; ?>
        <?php else : ?>
            <div class="mel-slider-row">
                <p>
                    <label for="mel_slider_image_url_0">Choose Image:</label>
                    <input type="text" name="mel_slider_image_urls[]" style="width: 70%;">
                    <button class="button mel-slider-media-button" data-field="mel_slider_image_urls[]">Choose Media</button>
                </p>
                <p>
                    <label for="mel_slider_text_url_0">Video URL:</label>
                    <input type="text" name="mel_slider_text_urls[]" style="width: 70%;">
                    <button class="button mel-slider-remove-row" style="display: none;">Remove Row</button>
                </p>
            </div>
        <?php endif; ?>
        <button class="button mel-slider-add-row">Add Row</button>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            var mediaUploader;

            // Add Row
            $('.mel-slider-add-row').click(function (e) {
                e.preventDefault();
                var newRow = $('.mel-slider-row:first').clone();
                newRow.find('input').val('');
                newRow.find('.mel-slider-remove-row').show();
                $('.mel-slider-repeater').append(newRow);
            });

            // Remove Row
            $('.mel-slider-repeater').on('click', '.mel-slider-remove-row', function (e) {
                e.preventDefault();
                $(this).closest('.mel-slider-row').remove();
            });

            // Media Uploader
            $('.mel-slider-repeater').on('click', '.mel-slider-media-button', function (e) {
                e.preventDefault();
                var button = $(this);
                var field = button.data('field');

                // If the media frame already exists, reopen it.
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                // Create the media frame.
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Media',
                    button: {
                        text: 'Choose Media'
                    },
                    multiple: false
                });

                // When a file is selected, run a callback.
                mediaUploader.on('select', function () {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    button.siblings('input').val(attachment.url);
                });

                // Open the media uploader.
                mediaUploader.open();
            });
        });
    </script>
    <?php
}

function mel_slider_save_meta($post_id) {
    if (isset($_POST['mel_slider_nonce']) && wp_verify_nonce($_POST['mel_slider_nonce'], 'mel_slider_save_meta')) {
        $image_urls = isset($_POST['mel_slider_image_urls']) ? array_map('esc_url', $_POST['mel_slider_image_urls']) : array();
        $text_urls = isset($_POST['mel_slider_text_urls']) ? array_map('esc_url', $_POST['mel_slider_text_urls']) : array();

        update_post_meta($post_id, '_mel_slider_image_urls', $image_urls);
        update_post_meta($post_id, '_mel_slider_text_urls', $text_urls);
    }
}
add_action('save_post', 'mel_slider_save_meta');


