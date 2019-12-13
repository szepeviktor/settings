<?php
declare(strict_types=1);

namespace ItalyStrap\Settings;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\View\View;
use ItalyStrap\View\ViewInterface;

class Pages {

	use ShowableTrait;

	const DS = DIRECTORY_SEPARATOR;
	const PAGE_TITLE = 'page_title';
	const MENU_TITLE = 'menu_title';
	const CAPABILITY = 'capability';
	const SLUG = 'menu_slug';
	const CALLBACK = 'callback';
	const ICON = 'icon_url';
	const POSITION = 'position';

	/**
	 * @var ConfigInterface
	 */
	private $config;
	private $capability;
	private $pagenow;
	private $sections;

	/**
	 * @var ViewInterface
	 */
	private $view;

	/**
	 * @var string
	 */
	private $options_group;

	/**
	 * Pages constructor.
	 * @param ConfigInterface $config
	 * @param ViewInterface $view
	 * @param $sections
	 * @param $options_group
	 */
	public function __construct( ConfigInterface $config, ViewInterface $view, $sections, $options_group ) {

		if ( isset( $_GET['page'] ) ) { // Input var okay.
			$this->pagenow = \stripslashes( $_GET['page'] ); // Input var okay.
		}

		$this->config = $config;
		$this->view = $view;
		$this->sections = $sections;
		$this->options_group = $options_group;
	}

	/**
	 * Add plugin primary page in admin panel
	 */
	public function load() {

		if ( ! $this->config['page'] ) {
			return;
		}

		$this->capability = $this->config['page']['capability'];

		\add_menu_page(
			$this->config['page']['page_title'],
			$this->config['page']['menu_title'],
			$this->capability,
			$this->config['page']['menu_slug'],
			[ $this, 'getView' ],
			$this->config['page']['icon_url'],
			$this->config['page']['position']
		);

		$this->addSubMenuPage( $this->config->get( 'page.pages', [] ), $this->config['page']['menu_slug'] );
	}

	/**
	 * Add sub menù pages for plugin's admin page
	 * @param array $submenu_pages
	 * @param $parent_slug
	 */
	private function addSubMenuPage( array $submenu_pages, $parent_slug ) {

		foreach ( $submenu_pages as $submenu ) {
			if ( isset( $submenu['show_on'] ) && ! $this->showOn( $submenu[ 'show_on' ] ) ) {
				continue;
			}

			\add_submenu_page(
				$parent_slug,
				$submenu['page_title'],
				$submenu['menu_title'],
				$this->capability, // $submenu['capability'],
				$submenu['menu_slug'],
				[ $this, 'getView'] // $submenu['function_cb']
			);
		}
	}

	/**
	 * The add_submenu_page callback
	 */
	public function getView() {

		if ( ! \current_user_can( $this->capability ) ) {
			\wp_die( \esc_attr__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$file_path = \file_exists( $this->config['admin_view_path'] . $this->pagenow . '.php' )
			? $this->config['admin_view_path'] . $this->pagenow . '.php'
			: __DIR__ . self::DS . 'view' . self::DS . 'form.php';

//		try {
//			echo $this->view->render( $this->pagenow );
//			echo $this->view->render('form', [
//				'createNavTab' 			=> [ $this, 'createNavTab' ],
//				'doSettingsSections'	=> [ $this, 'doSettingsSections' ]
//			] );
//		} catch (\Exception $e) {
//			require( $file_path );
//		}

		require $file_path;
	}

	/**
	 * Get Aside for settings page
	 */
	public function getAside() {

		$file_path = \file_exists( $this->config['admin_view_path'] . 'aside.php' )
			? $this->config['admin_view_path'] . 'aside.php'
			: __DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'aside.php';

		require $file_path;
	}

	/**
	 * Prints out all settings sections added to a particular settings page
	 *
	 * Part of the Settings API. Use this in a settings page callback function
	 * to output all the sections and fields that were added to that $page with
	 * add_settings_section() and add_settings_field()
	 *
	 * @global $wp_settings_sections Storage array of all settings sections added to admin pages
	 * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections
	 * @since 2.7.0
	 *
	 * @param string $page The slug name of the page whose settings sections you want to output.
	 */
	public function doSettingsSections( $page ) {

		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}

		$count = 1;

		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
			echo '<div id="tabs-' . $count . '" class="wrap">'; // XSS ok.
			if ( $section['title'] ) {
				echo "<h2>{$section['title']}</h2>\n"; // XSS ok.
			}

			if ( $section['callback'] ) {
				\call_user_func( $section['callback'], $section );
			}

			if (
				! isset( $wp_settings_fields )
				|| ! isset( $wp_settings_fields[ $page ] )
				|| ! isset( $wp_settings_fields[ $page ][ $section['id'] ] )
			) {
				continue;
			}
			echo '<table class="form-table">';
			\do_settings_fields( $page, $section['id'] );
			echo '</table>';
			echo '</div>';
			$count++;
		}
	}

	/**
	 * Create the nav tabs for section in admin plugin area
	 */
	public function createNavTab() {

		$count = 1;

		$out = '<ul>';

		foreach ($this->sections as $key => $setting ) {
			if ( isset( $setting['show_on'] ) && false === $setting['show_on'] ) {
				continue;
			}

			$out .= '<li><a href="#tabs-' . $count . '">' . $setting['tab_title'] . '</a></li>';
			$count++;
		}

		$out .= '</ul>';

		if ( $count <= 2 ) {
			return '';
		}

		echo $out; // XSS ok.
		return '';
	}
}
