<?php
/**
 * Plugin Name: Custom Drag & Drop
 * Description: Adds a drag & drop section or columns to Custom Field Suite.
 * Version: 1.0
 * Author: DevRm
 */

add_action('admin_init', function () {
    if (is_admin() && current_user_can('activate_plugins')) {
        if (!is_plugin_active('custom-field-suite/cfs.php')) {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error"><p><strong>Custom Drag & Drop</strong> requires <strong>Custom Field Suite</strong> plugin to be active.</p></div>';
            });
        }
    }
});

add_action('pre_current_active_plugins', function () {
    global $pagenow;
    if ($pagenow === 'plugins.php') {
        add_filter('plugin_action_links', function ($actions, $plugin_file) {
            if ($plugin_file === 'custom-field-suite/cfs.php') {
                $actions['deactivate'] = preg_replace(
                    '/href="([^"]+)"/',
                    'href="#" onclick="alert(\'Please deactivate Custom Drag & Drop plugin before deactivating Custom Field Suite.\'); return false;"',
                    $actions['deactivate']
                );
            }
            return $actions;
        }, 10, 2);
    }
});

if ( ! defined( 'ABSPATH' ) ) exit;


include_once plugin_dir_path(__FILE__) . 'functions.php';
