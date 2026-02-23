<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

delete_option('smtp_settings');
delete_option('smtp_version');
delete_option('smtp_update_data');
