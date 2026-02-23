<?php

defined('ABSPATH') || exit;

//$smtp_version = get_option('monitor_version');
//if (SMTP_VERSION !== $smtp_version) {
//    if (WP_DEBUG) {
//        error_log('SMTP > Version change');
//    }
//    include_once __DIR__ . '/activate.php';
//    update_option('smtp_version', SMTP_VERSION, false);
//}


add_action('admin_menu', function () {

    add_options_page(
            'SMTP', 'SMTP', 'administrator', 'satollo-smtp',
            function () {
                include __DIR__ . '/settings.php';
            }
    );
});

add_filter('plugin_action_links_satollo-smtp/plugin.php', function ($links) {
    $links[] = '<a href="admin.php?page=satollo-smtp">' . __('Settings') . '</a>';
    return $links;
});

