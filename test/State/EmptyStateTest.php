<?php

require_once 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;
use UnityCrash\Utils\Singleton;

class EmptyStateTest extends PHPUnit_Framework_TestCase
{
	private $context;

	public function testInstance()
	{
		$this->assertTrue(class_exists('UnityCrash\State\EmptyState'), 'UnityCrash\State\EmptyState is not found');
		$state = UnityCrash\State\EmptyState::getInstance();
		$this->assertInstanceOf('UnityCrash\Utils\Singleton', $state);
		$this->assertInstanceOf('UnityCrash\State\EmptyState', $state);
	}

	public function testSetup()
	{
		// ホワイトボックステストのみ
		UnityCrash\State\EmptyState::getInstance()->setup($this->context);
	}

	public function testLoop()
	{
		// ホワイトボックステストのみ
		UnityCrash\State\EmptyState::getInstance()->loop($this->context);
	}

	public function testTeardown()
	{
		// ホワイトボックステストのみ
		UnityCrash\State\EmptyState::getInstance()->teardown($this->context);
	}

	protected function setUp()
	{
		$this->context = new Context();
	}
}
