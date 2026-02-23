<?php

/**
 * Plugin Name: SMTP
 * Description: The lighter plugin to connect WP to an SMTP
 * Version: 0.0.3
 * Author: Stefano Lissa
 * Author URI: https://www.satollo.net
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: smtp
 * Requires at least: 6.9
 * Requires PHP: 8.1
 * Plugin URI: https://www.satollo.net/plugins/smtp
 * Update URI: satollo-smtp
 */
defined('ABSPATH') || exit;

define('SMTP_VERSION', '0.0.3');

add_filter('phpmailer_init', function ($mailer) {

    static $settings = null;

    if (!$settings) {
        $settings = get_option('smtp_settings', []);
    }

    if (isset($settings['enabled'])) {
        $mailer->IsSMTP();
        $mailer->Host = $settings['host'];
        $mailer->Port = $settings['port'];
        $mailer->SMTPSecure = $settings['secure'];
        $mailer->SMTPAutoTLS = true;
        $mailer->SMTPAuth = true;
        $mailer->Username = $settings['username'];
        $mailer->Password = $settings['password'];
    }
    return $mailer;
}, 5);

if (is_admin()) {
    require_once __DIR__ . '/admin/admin.php';
}

if (is_admin() || defined('DOING_CRON') && DOING_CRON) {
    require_once __DIR__ . '/includes/repo.php';
}

//register_deactivation_hook(__FILE__, function () {
//    delete_option('smtp_update_data');
//});

