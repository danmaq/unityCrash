<?php

require_once 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\State\EmptyState;

class ContextTest extends PHPUnit_Framework_TestCase
{
	public function testInstance()
	{
		$this->assertTrue(class_exists('Context'), 'クラスがないっぽい');
	}
	
	protected function setUp()
	{
	}
}
