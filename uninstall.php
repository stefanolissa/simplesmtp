<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

delete_option('satollo_smtp_settings');
delete_option('satollo_smtp_version');
delete_option('satollo_smtp_update_data');
