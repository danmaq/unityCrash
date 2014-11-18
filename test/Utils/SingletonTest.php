<?php

require_once 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\Utils\Singleton;

class ConcretedSingleton extends Singleton
{
	
}

class SingletonTest extends PHPUnit_Framework_TestCase
{
	public function testGetInstance()
	{
		$this->assertTrue(class_exists('Singleton'), 'クラスがないっぽい');
		$this->assertClassHasStaticAttribute('getInstance', 'Singleton');
		$this->assertInstanceOf('ConcretedSingleton', ConcretedSingleton::getInstance());
	}
}
