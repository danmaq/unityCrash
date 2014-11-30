<?php

require_once 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class ContextTest extends PHPUnit_Framework_TestCase
{

	private $context;

	public function testInstance()
	{
		$this->assertInstanceOf('UnityCrash\State\IContext', new Context());
		// ホワイトボックステストのみ
		new Context(EmptyState::getInstance());
	}
	
	// TODO: ここから
	
	protected function setUp()
	{
		$this->context = new Context();
	}
}
