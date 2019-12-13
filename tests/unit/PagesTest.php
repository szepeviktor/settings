<?php
class PagesTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $sections = [];
    private $plugin = [];

    protected function _before()
    {
		\tad\FunctionMockerLe\define( '__', function ( $text, $domain = 'default' ) { return $text; });

		$this->sections = require codecept_data_dir( 'fixtures/config/' ) . 'sections.php';
		$this->plugin = require codecept_data_dir( 'fixtures/config/' ) . 'plugin.php';
    }

    protected function _after()
    {
    }

	private function getInstance() {
    	$config = $this->make( \ItalyStrap\Config\Config::class );
    	$view = $this->make( \ItalyStrap\View\View::class );
		$sut = new \ItalyStrap\Settings\Pages( $config, $view, $this->sections, $this->plugin['options_group'] );
		$this->assertInstanceOf( \ItalyStrap\Settings\Pages::class, $sut, '' );
		return $sut;
    }

	/**
	 * @test
	 */
    public function ItShouldBeInstantiable()
    {
    	$this->getInstance();

    }
}
