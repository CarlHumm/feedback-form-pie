<?php
/**
 * Plugin Name: feedback-plugin
 * Description: A plugin for a feedback form - featuring a custom DB table, shortcode, and ACF block support. Compatible with traditional themes. 
 * Version: 1.0
 * Author: Carl Humm
 */


if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/create-table.php';
require_once plugin_dir_path(__FILE__) . 'includes/submission-handler.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';

register_activation_hook(__FILE__, 'feedback_form_create_table');
register_deactivation_hook(__FILE__, 'feedback_form_remove_table');

add_action('wp_enqueue_scripts', 'enqueue_feedback_form_assets');

function enqueue_feedback_form_assets() {
    if (is_singular()) {
        global $post;
        if (!$post) {
            $post = get_post(); 
        }

        if (
            has_shortcode($post->post_content, 'feedback_form') ||
            has_block('acf/feedback-form')
        ) {
            wp_enqueue_style(
                'feedback-form-style',
                plugin_dir_url(__FILE__) . 'assets/style.css',
                [],
                filemtime(__DIR__ . '/assets/style.css')
            );

            wp_enqueue_script(
                'feedback-form-script',
                plugin_dir_url(__FILE__) . 'assets/main.js',
                [],
                filemtime(__DIR__ . '/assets/main.js'),
                true
            );

            wp_localize_script('feedback-form-script', 'FeedbackForm', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('submit_feedback')
            ]);
        }
    }
}

add_shortcode('feedback_form', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'template-parts/feedback-form.php';
    return ob_get_clean();
});

add_action('acf/init', function () {
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type([
            'name' => 'feedback-form',
            'title' => 'Feedback Form',
            'render_callback' => function () {
                include plugin_dir_path(__FILE__) . 'template-parts/feedback-form.php';
            },
            'category' => 'widgets',
            'icon' => 'feedback',
            'keywords' => ['form', 'feedback'],
            'mode' => 'auto',
        ]);
    }
});

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('feedback-form-style-editor', plugin_dir_url(__FILE__) . 'assets/style.css', [], filemtime(__DIR__ . '/assets/style.css'));
    wp_enqueue_script('feedback-form-script-editor', plugin_dir_url(__FILE__) . 'assets/main.js', [], filemtime(__DIR__ . '/assets/main.js'), true);
});