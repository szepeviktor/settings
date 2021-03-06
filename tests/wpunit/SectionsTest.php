<?php

class SectionsTest extends \Codeception\TestCase\WPTestCase
{
	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	private function getFields() {
		return $this->prophesize( \ItalyStrap\Fields\Fields::class );
	}

	private function getInstance(): \ItalyStrap\Settings\Sections {
		$config = \ItalyStrap\Config\ConfigFactory::make();
//		$fields = new \ItalyStrap\Fields\Fields();
		$fields = $this->getFields()->reveal();
		$parser = new \ItalyStrap\DataParser\Parser();
		$options = new \ItalyStrap\Settings\Options( 'italystrap' );
		$sut = new \ItalyStrap\Settings\Sections( $config, $fields, $parser, $options );
		$this->assertInstanceOf( \ItalyStrap\Settings\SectionsInterface::class, $sut, '' );
		$this->assertInstanceOf( \ItalyStrap\Settings\Sections::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
	public function ItShouldBeInstantiable() {
		$sut = $this->getInstance();
	}
}
