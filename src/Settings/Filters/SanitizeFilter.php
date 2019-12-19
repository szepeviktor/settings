<?php
declare(strict_types=1);

namespace ItalyStrap\Settings\Filters;

use ItalyStrap\Cleaner\Sanitization;
use ItalyStrap\Settings\FilterableInterface;

/**
 * Class SanitizeFilter
 * @package ItalyStrap\Settings\Filters
 */
class SanitizeFilter implements FilterableInterface
{
	/**
	 * @var Sanitization
	 */
	private $sanitization;

	public function __construct( Sanitization $sanitization ) {
		$this->sanitization = $sanitization;
	}

	/**
	 * @inheritDoc
	 */
	public function filter( array $schema, array $data ) {

		foreach ( (array) $data[ $schema['id'] ] as $value  ) {
			$this->sanitization->addRules( $schema['sanitize'] );
			$data = $this->sanitization->sanitize( $value );
		}

		return $data;
	}
}
