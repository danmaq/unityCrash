<?php

require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Utils\Singleton;

class ConcretedSingleton extends Singleton
{
	
}

class SingletonTest extends PHPUnit_Framework_TestCase
{
	public function testGetInstance()
	{
		$this->assertTrue(class_exists('UnityCrash\Utils\Singleton'), 'UnityCrash\Utils\Singleton is not found.');
		$this->assertInstanceOf('ConcretedSingleton', ConcretedSingleton::getInstance());
		$this->assertEquals(ConcretedSingleton::getInstance(), ConcretedSingleton::getInstance());
	}
	
	/**
	 * @expectedException LogicException
	 * @expectedExceptionMessage Singleton object must not clone.
	 */
	public function testClone()
	{
		$obj = ConcretedSingleton::getInstance();
		$this->assertNotNull($obj);
		$a = clone $obj;
	}
}
