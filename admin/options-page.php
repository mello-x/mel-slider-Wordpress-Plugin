<?php
// admin/settings-options.php

function mel_slider_options_page() {
    ?>
    <div class="wrap">
        <h1>Mel Slider Options</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('mel_slider_options');
            do_settings_sections('mel-slider-options');
            wp_nonce_field('mel_slider_options_nonce', 'mel_slider_options_nonce');
            submit_button('Save Changes', 'primary', 'submit', false);
            ?>
        </form>
    </div>
    <?php
}

function mel_slider_save_options() {
    if (isset($_POST['submit'])) {
        check_admin_referer('mel_slider_options_nonce', 'mel_slider_options_nonce');

        global $wpdb;
        $table_name = $wpdb->prefix . 'mel_slider_options';

        $post_types = get_post_types(['public' => true], 'names');

        // Clear existing entries
        $wpdb->query("TRUNCATE TABLE $table_name");

        // Insert selected post types
        foreach ($post_types as $post_type) {
            $checked = isset($_POST['mel_slider_options']['post_types'][$post_type]) ? 1 : 0;
            $wpdb->insert($table_name, array('post_type' => $post_type, 'checked' => $checked), array('%s', '%d'));
        }
    }
}

function mel_slider_section_callback() {
    echo '<p>Select the post types where the slider options should be available.</p>';
}

function mel_slider_post_types_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mel_slider_options';

    $checked_post_types = $wpdb->get_col("SELECT post_type FROM $table_name WHERE checked = 1");

    $post_types = get_post_types(['public' => true], 'objects');

    foreach ($post_types as $post_type) {
        $checked = in_array($post_type->name, $checked_post_types) ? 'checked' : '';
        echo "<label><input type='checkbox' name='mel_slider_options[post_types][{$post_type->name}]' {$checked}> {$post_type->labels->name}</label><br>";
    }
}

function mel_slider_register_settings() {
    register_setting('mel_slider_options', 'mel_slider_options');
    add_settings_section('mel_slider_main_section', 'Mel Slider Settings', 'mel_slider_section_callback', 'mel-slider-options');
    add_settings_field('mel_slider_post_types', 'Select Post Types', 'mel_slider_post_types_callback', 'mel-slider-options', 'mel_slider_main_section');
}

add_action('admin_init', 'mel_slider_register_settings');
add_action('admin_init', 'mel_slider_save_options');