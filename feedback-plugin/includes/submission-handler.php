<?php
add_action('wp_ajax_submit_feedback', 'feedback_form_handle_submission');
add_action('wp_ajax_nopriv_submit_feedback', 'feedback_form_handle_submission');

function feedback_form_handle_submission() {
    check_ajax_referer('submit_feedback', 'nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($email) || empty($message)) {
        wp_send_json_error(['message' => 'Missing required fields']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'feedback_submissions';

    $existing = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE email = %s", $email));
    if ($existing > 0) {
        wp_send_json_success(['message' => 'Feedback already submitted']);
    }
    
    $wpdb->insert($table, ['email' => $email, 'message' => $message]);
    wp_send_json_success(['message' => 'Thank you for your feedback!']);
}