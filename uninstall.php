<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

delete_option('simplesmtp_settings');
delete_option('simplesmtp_version');
delete_option('simplesmtp_update_data');
