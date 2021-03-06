<?php

namespace ItalyStrap\Settings;

/**
 * Class Page
 * @package ItalyStrap\Settings
 *
 * add_dashboard_page() – index.php
 * add_posts_page() – edit.php
 * add_media_page() – upload.php
 * add_pages_page() – edit.php?post_type=page
 * add_comments_page() – edit-comments.php
 * add_theme_page() – themes.php
 * add_plugins_page() – plugins.php
 * add_users_page() – users.php
 * add_management_page() – tools.php
 * add_options_page() – options-general.php
 * add_options_page() – settings.php
 * add_links_page() – link-manager.php – requires a plugin since WP 3.5+
 * Custom Post Type – edit.php?post_type=wporg_post_type
 * Network Admin – settings.php
 */
interface PageInterface {

	/**
	 * @return string
	 */
	public function getPageName();

	/**
	 * Must be loaded at 'admin_menu' hook
	 * Add plugin primary page in admin panel
	 * @return bool|false|string
	 */
	public function register();
}
