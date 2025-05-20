<?php
add_action('admin_menu', function () {
    add_menu_page('Feedback Submissions', 'Feedback', 'manage_options', 'feedback-submissions', 'feedback_form_admin_page');
});

function feedback_form_admin_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'feedback_submissions';
    $entries = $wpdb->get_results("SELECT * FROM $table ORDER BY submitted_at DESC");

    echo '<div class="wrap"><h1>Feedback Submissions</h1>';
    if (!$entries) {
        echo '<p>No feedback yet.</p>';
        return;
    }

    echo '<table class="widefat"><thead><tr><th>Email</th><th>Message</th><th>Date</th></tr></thead><tbody>';
    
    foreach ($entries as $entry) {
        echo '<tr>';
        echo '<td>' . esc_html($entry->email) . '</td>';
        echo '<td>' . esc_html($entry->message) . '</td>';
        echo '<td>' . esc_html($entry->submitted_at) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
}