<?php

function mel_slider_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'mel_slider_options';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_type varchar(255) NOT NULL,
        checked tinyint(1) NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Set the default options on activation
    mel_slider_set_default_options();
}

function mel_slider_deactivate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'mel_slider_options';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

function mel_slider_set_default_options() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'mel_slider_options';

    // Set default options for the "page" post type
    $wpdb->insert($table_name, array('post_type' => 'page', 'checked' => 0));
}

register_activation_hook(__FILE__, 'mel_slider_activate');
register_deactivation_hook(__FILE__, 'mel_slider_deactivate');
