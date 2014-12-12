<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Utils\Singleton;

class ConcretedSingleton extends Singleton
{
}

class SingletonTest extends TestCaseExtension
{

	/** インスタンスと紐づけられるキー。 */
	const INSTANCE = 'instance';

	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable['インスタンスを取得する'] = array($this, 'getInstance');
		$this->thenTable['指定したインスタンスが取得できる'] = array($this, 'isInstance');
		$this->thenTable['同一性が取れる'] = array($this, 'isEqualInstance');
		$this->thenTable['複製することはできない'] = array($this, 'cannotCopy');
	}
	
	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'ConcretedSingleton')
			->and('同一性が取れる');
	}

	/** @scenario インスタンスを複製することはできない */
	public function shouldCannotCopy()
	{
		$this
			->given('インスタンスを取得する')
			->then('複製することはできない');
	}
	
	/** インスタンスを取得する */
	protected function getInstance(array &$world, array $arguments)
	{
		$world[self::INSTANCE] = ConcretedSingleton::getInstance();
	}

	/** 指定したインスタンスが取得できる */
	protected function isInstance(array &$world, array $arguments)
	{
		$this->assertInstanceOf($arguments[0], $world[self::INSTANCE]);
	}

	/** 同一性が取れる */
	protected function isEqualInstance(array &$world, array $arguments)
	{
		$this->assertEquals($world[self::INSTANCE], ConcretedSingleton::getInstance());
	}

	/** 複製することはできない */
	protected function cannotCopy(array &$world, array $arguments)
	{
		$key = self::INSTANCE;
		$callback = function () use ($world, $key) { clone $world[$key]; };
		$this->assertException($callback, 'LogicException', 'Singleton object must not clone.');
	}
}
