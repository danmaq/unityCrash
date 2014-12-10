<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class EmptyStateTest extends TestCaseExtension
{

	/** コンテキスト オブジェクトに紐づけられたキー。 */
	const CONTEXT = 'context';

	/** インスタンスと紐づけられるキー。 */
	const INSTANCE = 'instance';
	
	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable['インスタンスを取得する'] = array($this, 'getInstance');
		$this->thenTable['指定したインスタンスが取得できる'] = array($this, 'isInstance');
		$this->thenTable['Singletonである'] = array($this, 'isSingleton');
		$this->thenTable['setupが呼べる'] = array($this, 'callSetup');
		$this->thenTable['phaseが呼べる'] = array($this, 'callPhase');
		$this->thenTable['teardownが呼べる'] = array($this, 'callTeardown');
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

	/** @scenario phaseが呼べる */
	public function shouldCallPhase()
	{
		$this
			->given('インスタンスを取得する')
			->then('phaseが呼べる');
	}

	/** @scenario teardownが呼べる */
	public function shouldCallTeardown()
	{
		$this
			->given('インスタンスを取得する')
			->then('teardownが呼べる');
	}
	
	/** インスタンスを取得する */
	protected function getInstance(array &$world, array $arguments)
	{
		$this->assertTrue(class_exists('UnityCrash\State\EmptyState'), 'クラスが存在する');
		$world[self::INSTANCE] = EmptyState::getInstance();
		$world[self::CONTEXT] = new Context();
	}

	/** 指定したインスタンスが取得できる */
	protected function isInstance(array &$world, array $arguments)
	{
		$this->assertInstanceOf($arguments[0], $world[self::INSTANCE]);
	}
	
	/** Singletonである */
	protected function isSingleton(array &$world, array $arguments)
	{
		$this->assertInstanceOf(
			'UnityCrash\Utils\Singleton', $world[self::INSTANCE], '状態はシングルトンである');
	}
	
	/** setupが呼べる */
	protected function callSetup(array &$world, array $arguments)
	{
		// ホワイトボックステストのみ
		$world[self::INSTANCE]->setup($world[self::CONTEXT]);
	}
	
	/** phaseが呼べる */
	protected function callPhase(array &$world, array $arguments)
	{
		// ホワイトボックステストのみ
		$world[self::INSTANCE]->phase($world[self::CONTEXT]);
	}
	
	/** teardownが呼べる */
	protected function callTeardown(array &$world, array $arguments)
	{
		// ホワイトボックステストのみ
		$world[self::INSTANCE]->teardown($world[self::CONTEXT]);
	}
}
