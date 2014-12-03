<?php

require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class ContextTest extends PHPUnit_Framework_TestCase
{

	/** テスト対象オブジェクト。 */
	private $context;

	public function testInstance()
	{
		$this->assertInstanceOf('UnityCrash\State\IContext', $this->context);
		// ホワイトボックステストのみ
		new Context(null);
		new Context(EmptyState::getInstance());
	}

	public function testGetStorage()
	{
		$this->fail('Test is not implemented.');
	}

	public function testGetPreviousState()
	{
		$this->fail('Test is not implemented.');
	}

	public function testGetCurrentState()
	{
		$this->fail('Test is not implemented.');
	}

	public function testGetNextState()
	{
		$this->fail('Test is not implemented.');
	}

	public function testSetNextState()
	{
		$this->fail('Test is not implemented.');
	}

	public function testIsTerminate()
	{
		$this->fail('Test is not implemented.');
	}

	public function testLoop()
	{
		$this->fail('Test is not implemented.');
	}

	public function testCommitState()
	{
		$this->fail('Test is not implemented.');
	}

	public function testTerminate()
	{
		$this->fail('Test is not implemented.');
	}

	protected function setUp()
	{
		$this->context = new Context();
	}
}
