<?php

require_once 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\State\EmptyState;
use UnityCrash\Utils\Singleton;

class EmptyStateTest extends PHPUnit_Framework_TestCase
{
	public function testInstance()
	{
		$this->assertTrue(class_exists('EmptyState'), 'クラスがないっぽい');
		$state = UnityCrash\State\EmptyState::getInstance();
		$this->assertInstanceOf('Singleton', $state);
		$this->assertInstanceOf('EmptyState', $state);
	}
	
	protected function setUp()
	{
	}
}
