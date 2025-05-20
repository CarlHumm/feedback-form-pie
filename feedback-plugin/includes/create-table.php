<?php
function feedback_form_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'feedback_submissions';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function feedback_form_remove_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'feedback_submissions';
    $wpdb->query("DROP TABLE IF EXISTS $table");
}