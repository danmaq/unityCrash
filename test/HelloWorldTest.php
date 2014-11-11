<?php

require_once 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

class HelloWorldTest extends PHPUnit_Framework_TestCase
{
	public function testGetHello()
	{
		$this->assertTrue(class_exists("UnityCrash\HelloWorld"), 'クラスがないっぽい');
		$hello = new UnityCrash\HelloWorld();
		$this->assertEquals($hello->getHello(),'Hello, World!');
	}
	
	protected function setUp()
	{
	}
}
