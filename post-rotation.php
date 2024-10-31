<?php
/*
Plugin Name: Post Rotation
Plugin URI: https://digitalemphasis.com/wordpress-plugins/post-rotation/
Description: Set the rotation interval or the allowed time without new posts... and automatically an older post becomes the latest one!
Version: 1.9
Author: digitalemphasis
Author URI: https://digitalemphasis.com/
License: GPLv2 or later
*/

defined( 'ABSPATH' ) || die( 'Cannot access pages directly.' );
defined( 'PR_PLUGIN_VER' ) || define( 'PR_PLUGIN_VER', '1.9' );

function pr_register_the_settings() {
	register_setting( 'pr-settings-group', 'pr_enabled' );
	register_setting( 'pr-settings-group', 'pr_fixed' );
	register_setting( 'pr-settings-group', 'pr_interval', 'interval_validate' );
	register_setting( 'pr-settings-group', 'pr_enforce_punctuality' );
	register_setting( 'pr-settings-group', 'pr_also_alter_last_modified' );
	register_setting( 'pr-settings-group', 'pr_exclude_if_no_featured_image' );
	register_setting( 'pr-settings-group', 'pr_included_post_types' );
	register_setting( 'pr-settings-group', 'pr_filter_by_category' );
	register_setting( 'pr-settings-group', 'pr_included_categories' );
	register_setting( 'pr-settings-group', 'pr_clean_uninstall' );
}
add_action( 'admin_init', 'pr_register_the_settings' );

function interval_validate( $value ) {
	$minimal       = ( $value['minutes'] > 0 ) ? 0 : 1;
	$error_message = 'You have entered an invalid value. The default value of 24 hours will be used instead.';

	if ( ! ctype_digit( $value['hours'] ) || ! ctype_digit( $value['minutes'] ) || $value['hours'] < $minimal ) {
		$value = array(
			'hours'   => '24',
			'minutes' => '0',
		);

		add_settings_error( 'pr_interval', 'invalid-interval', $error_message );
	}

	return $value;
}

function pr_enqueue_assets() {
	wp_enqueue_style( 'pr-admin-css', plugins_url( 'admin/post-rotation-admin.css', __FILE__ ), array(), PR_PLUGIN_VER );
	wp_enqueue_script( 'pr-admin-js', plugins_url( 'admin/post-rotation-admin.js', __FILE__ ), array( 'jquery' ), PR_PLUGIN_VER, true );
}
add_action( 'admin_enqueue_scripts', 'pr_enqueue_assets' );

function pr_settings_page() {
	require 'admin/post-rotation-admin.php';
}

function pr_submenu() {
	add_submenu_page( 'edit.php', 'Post Rotation', 'Post Rotation', 'manage_options', 'post-rotation', 'pr_settings_page' );
}
add_action( 'admin_menu', 'pr_submenu' );

function pr_add_settings_link( $links ) {
	$settings_link = '<a href="edit.php?page=post-rotation">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pr_add_settings_link' );

function pr_update_db_check() {
	if ( ! is_array( get_option( 'pr_interval' ) ) ) {
		update_option(
			'pr_interval',
			array(
				'hours'   => get_option( 'pr_interval' ),
				'minutes' => '0',
			)
		);
	}
}
add_action( 'plugins_loaded', 'pr_update_db_check' );

function pr_activation() {
	add_option( 'pr_latest_rotation_time', '' );
	add_option( 'pr_enabled', '' );
	add_option( 'pr_fixed', '' );
	add_option(
		'pr_interval',
		array(
			'hours'   => '24',
			'minutes' => '0',
		)
	);
	add_option( 'pr_enforce_punctuality', '' );
	add_option( 'pr_also_alter_last_modified', '1' );
	add_option( 'pr_exclude_if_no_featured_image', '' );
	add_option( 'pr_included_post_types', array( 'post' ) );
	add_option( 'pr_filter_by_category', '1' );
	add_option( 'pr_included_categories', '' );
	add_option( 'pr_clean_uninstall', '1' );
}

function pr_deactivation() {
	unregister_setting( 'pr-settings-group', 'pr_enabled' );
	unregister_setting( 'pr-settings-group', 'pr_fixed' );
	unregister_setting( 'pr-settings-group', 'pr_interval' );
	unregister_setting( 'pr-settings-group', 'pr_enforce_punctuality' );
	unregister_setting( 'pr-settings-group', 'pr_also_alter_last_modified' );
	unregister_setting( 'pr-settings-group', 'pr_exclude_if_no_featured_image' );
	unregister_setting( 'pr-settings-group', 'pr_included_post_types' );
	unregister_setting( 'pr-settings-group', 'pr_filter_by_category' );
	unregister_setting( 'pr-settings-group', 'pr_included_categories' );
	unregister_setting( 'pr-settings-group', 'pr_clean_uninstall' );
}

function pr_uninstall() {
	if ( get_option( 'pr_clean_uninstall' ) ) {
		delete_option( 'pr_latest_rotation_time' );
		delete_option( 'pr_enabled' );
		delete_option( 'pr_fixed' );
		delete_option( 'pr_interval' );
		delete_option( 'pr_enforce_punctuality' );
		delete_option( 'pr_also_alter_last_modified' );
		delete_option( 'pr_exclude_if_no_featured_image' );
		delete_option( 'pr_included_post_types' );
		delete_option( 'pr_filter_by_category' );
		delete_option( 'pr_included_categories' );
		delete_option( 'pr_clean_uninstall' );
	}
}

register_activation_hook( __FILE__, 'pr_activation' );
register_deactivation_hook( __FILE__, 'pr_deactivation' );
register_uninstall_hook( __FILE__, 'pr_uninstall' );

require 'post-rotation-core.php';
