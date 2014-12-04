<?php

require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\IContext;
use UnityCrash\State\EmptyState;
use UnityCrash\State\IState;
use UnityCrash\Utils\Singleton;

class TestState extends Singleton implements IState
{
	public $_setup;
	public $_loop;
	public $_teardown;

	public function reset()
	{
		$this->_setup = false;
		$this->_loop = false;
		$this->_teardown = false;
	}

	public function setup(IContext $context)
	{
		$this->_setup = true;
	}

	public function loop(IContext $context)
	{
		$this->_loop = true;
	}

	public function teardown(IContext $context)
	{
		$this->_teardown = true;
	}
}

class StateA extends TestState
{
}

class StateB extends Singleton
{
}

class ContextTest extends PHPUnit_Framework_TestCase
{

	/** テスト対象オブジェクト。 */
	private $context;

	/** インスタンス生成時のテスト。 */
	public function testCreateInstance()
	{
		$this->assertInstanceOf('UnityCrash\State\IContext', $this->context);
		$this->assertEquals($this->context->getPreviousState(), EmptyState::getInstance());
		$this->assertEquals($this->context->getCurrentState(), EmptyState::getInstance());
		$this->assertNull($this->context->getNextState());
		$got1 = new Context(null);
		$this->assertEquals($got1->getPreviousState(), EmptyState::getInstance());
		$this->assertEquals($got1->getCurrentState(), EmptyState::getInstance());
		$this->assertNull($got1->getNextState());
		$got2 = new Context(EmptyState::getInstance());
		$this->assertEquals($got2->getPreviousState(), EmptyState::getInstance());
		$this->assertEquals($got2->getCurrentState(), EmptyState::getInstance());
		$this->assertNull($got2->getNextState());
		$got3 = new Context(StateB::getInstance());
		$this->assertEquals($got3->getPreviousState(), EmptyState::getInstance());
		$this->assertEquals($got3->getCurrentState(), StateB::getInstance());
		$this->assertNull($got3->getNextState());
	}

	public function testGetStorage()
	{
		$got1 = $this->context->getStorage();
		$this->assertNotNull($got1);
		$this->assertInternalType('array', $got1);
		$got1['foo'] = 255;
		$got2 = $this->context->getStorage();
		$this->assertNotNull($got2);
		$this->assertInternalType('array', $got2);
		$this->assertArrayHasKey('foo', $got2);
		$this->assertEquals($got2['foo'], 255);
		$this->assertEquals($got1, $got2);
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
