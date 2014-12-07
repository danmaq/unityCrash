<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;
use UnityCrash\Utils\Singleton;

class EmptyStateTest extends TestCaseExtension
{
	
	private $context;
	
	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->context = new Context();
	}

	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'UnityCrash\State\EmptyState')
			->and('Singletonである');
	}

	/** @scenario setupが呼べる */
	public function shouldCallSetup()
	{
		$this
			->given('インスタンスを取得する')
			->then('setupが呼べる');
	}

	/** @scenario loopが呼べる */
	public function shouldCallLoop()
	{
		$this
			->given('インスタンスを取得する')
			->then('loopが呼べる');
	}

	/** @scenario teardownが呼べる */
	public function shouldCallTeardown()
	{
		$this
			->given('インスタンスを取得する')
			->then('teardownが呼べる');
	}

/*

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
	*/
}
