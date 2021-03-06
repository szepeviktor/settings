<?php
declare(strict_types=1);

namespace ItalyStrap\Settings;

use ItalyStrap\Config\ConfigInterface as Config;
use ItalyStrap\DataParser\ParserInterface;
use ItalyStrap\Fields\FieldsInterface;

class Sections implements \Countable, SectionsInterface {

	use ShowableTrait;

	const TAB_TITLE = 'tab_title';
	const ID = 'id';
	const TITLE = 'title';
	const DESC = 'desc';
	const FIELDS = 'fields';
	const LABEL_CLASS = 'class_for_label';

	const EVENT			= 'admin_init';

	/**
	 * Settings for plugin admin page
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * The plugin options
	 *
	 * @var array
	 */
	protected $options_values = [];

	/**
	 * The type of fields to create
	 *
	 * @var FieldsInterface
	 */
	protected $fields;

	/**
	 * @var ParserInterface
	 */
	private $parser;

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @var array
	 */
	private $field_class = [];
	private $section_key;

	/**
	 * @var Page
	 */
	private $page;

	/**
	 * Initialize Class
	 *
	 * @param FieldsInterface $fields The Fields object.
	 * @param ParserInterface $parser
	 * @param Options $options Get the plugin options.
	 * @param Config $config The configuration array plugin fields.
	 */
	public function __construct(
		Config $config,
		FieldsInterface $fields,
		ParserInterface $parser,
		Options $options
	) {
		$this->config = $config;

		$this->fields = $fields;
		$this->parser = $parser;

		$this->options = $options;
		$this->options_values = (array) $options->get();
	}

	/**
	 * @inheritDoc
	 */
	public function register() {
		$this->addSettingsSections();
		$this->registerSetting();
	}

	/**
	 *
	 */
	private function addSettingsSections(): void {
		foreach ( $this->config as $key => $section ) {
			$this->parseSectionWithDefault( $section );
			$this->section_key[ $section[ self::ID ] ] = $key;

			if ( ! $this->showOn( $section[ 'show_on' ] ) ) {
				continue;
			}

			\add_settings_section(
				$section[ self::ID ],
				$section[ self::TITLE ],
				[ $this, 'renderSection' ], //array( $this, $field['callback'] ),
				$this->getPageName() //$section['page']
			);

			$this->addSettingsFields( $section );
		}
	}

	private function parseSectionWithDefault( array &$section ) {
		$title = (array) \explode( ' ', $section[ self::TITLE ] );

		$section = \array_merge( [
			'show_on'	=> true,
			'tab_title'	=> \ucfirst( \strval( $title[0] ) ),
		], $section );
	}

	public function renderSection( array $args ) {

		$section = $this->config->get( $this->section_key[ $args[ self::ID ] ] . '.desc', '' );

		if ( \is_callable( $section ) ) {
			$section = \call_user_func( $section, $args );
		}

		echo $section; // XSS ok.
	}

	/**
	 * @param array $section
	 */
	private function addSettingsFields( $section ): void {
		foreach ( $section[ 'fields' ] as $field ) {
			$this->parseFieldWithDefault( $field );
			if ( ! $this->showOn( $field[ 'show_on' ] ) ) {
				continue;
			}

			$this->field_class[ $field[ self::ID ] ] = $field['class'];
			$field['class'] = $field[ self::LABEL_CLASS ];

			\add_settings_field(
				$field[ self::ID ],
				$field['label'],
				[ $this, 'renderField' ], //array( $this, $field['callback'] ),
				$this->getPageName(), //$field['page'],
				$section[ self::ID ],
				$field // $args
			);
		}
	}

	/**
	 * @todo Creare test per il `value` da usare in caso non sia ancora salvato nelle options, cazzarola
	 * @param array $field
	 */
	private function parseFieldWithDefault( array &$field ) {
		$field = \array_merge( [
			'show_on'			=> true,
			'label_for'			=> $this->getStringForLabel( $field ),
			'class'				=> '',
			self::LABEL_CLASS	=> '',
			'callback'			=> null,
			'value'				=> '',
		], $field );
	}

	/**
	 * @inheritDoc
	 */
	public function renderField( array $args ) {

		if ( \is_callable( $args['callback'] ) ) {
			return \call_user_func( $args['callback'], $args );
		}

		// Unset label because it is already rendered by settings_field API
		unset( $args['label'], $args['show_on'], $args['label_for'], $args[ self::LABEL_CLASS ], $args['callback'] );

		$args['class'] = $this->field_class[ $args['id'] ];

		$args['value'] = $this->options_values[ $args['id'] ] ?? $args['value'];
		$args['id'] = $args['name'] = $this->getStringForLabel( $args );
		echo $this->fields->render( $args ); // XSS ok.
		return '';
	}

	/**
	 * Register settings.
	 * This allow you to override this method.
	 */
	private function registerSetting(): void {
		\register_setting(
			$this->getPageName(),
			$this->options->getName(),
			[
				'sanitize_callback'	=> [ $this->parser->withSchema( $this->schemaForParsingData() ), 'parse' ],
				'show_in_rest'      => false,
				'description'       => '',
			]
		);
	}

	private function schemaForParsingData() {

		$fields = [];
		foreach ( (array) $this->config as $section ) {
			foreach ( $section['fields'] as $field ) {
				$fields[] = $field;
			}
		}

		return $fields;
	}

	/**
	 * @inheritDoc
	 */
	public function forPage( PageInterface $page ): Sections {
		$this->page = $page;
		return $this;
	}

	public function getPageName(): string {
		return $this->page->getPageName();
	}

	public function getSections(): array {
		return $this->config->toArray();
	}

	public function count(): int {
		return $this->config->count();
	}

	/**
	 * @param array $args
	 * @return string
	 */
	private function getStringForLabel( array $args ): string {
		return $this->options->getName() . '[' . $args[ 'id' ] . ']';
	}

	/**
	 * @return true|void
	 */
	public function boot() {
		return \add_action( self::EVENT, [ $this, 'register'] );
	}

	/**
	 * @return true|void
	 */
	public function unBoot() {
		return \remove_action( self::EVENT, [ $this, 'register'] );
	}
}
