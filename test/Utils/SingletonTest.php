<?php

require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Utils\Singleton;

class ConcretedSingleton extends Singleton
{
}

class SingletonTest extends PHPUnit_Extensions_Story_TestCase
{
	/** @scenario インスタンスを取得できる */
	public function getInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'ConcretedSingleton')
			->and('同一性が取れる');
	}

	/** @scenario インスタンスを複製することはできない */
	public function cannotCopy()
	{
		$this
			->given('インスタンスを取得する')
			->then('複製することはできない');
	}

	public function runGiven(&$world, $action, $arguments)
	{
		switch ($action)
		{
			case 'インスタンスを取得する';
				$world['instance'] = ConcretedSingleton::getInstance();
				break;
			default:
				$this->notImplemented($action);
				break;
		}
	}

	public function runWhen(&$world, $action, $arguments)
	{
		switch ($action)
		{
			default:
				$this->notImplemented($action);
				break;
		}
	}

	public function runThen(&$world, $action, $arguments)
	{
		switch ($action)
		{
			case '指定したインスタンスが取得できる';
				$this->assertInstanceOf($arguments[0], $world['instance']);
				break;
			case '同一性が取れる';
				$this->assertEquals($world['instance'], ConcretedSingleton::getInstance());
				break;
			case '複製することはできない':
				$this->assertException(
					function () use ($world) { clone $world['instance']; },
					'LogicException',
					'Singleton object must not clone.');
				break;
			default:
				$this->notImplemented($action);
				break;
		}
	}

	/**
	 * 例外をテストします。
	 *
	 * @param callable $func 実行する関数。引数は渡しません。
	 * @param string $type 想定する例外の型。省略時は判定しません。
	 * @param string $desc 想定する例外のメッセージ。省略時は判定しません。
	 */
	private function assertException($func, $type = null, $desc = null)
	{
		try
		{
			$func();
			$this->fail($action);
		}
		catch (Exception $e)
		{
			if ($type != null)
			{
				$this->assertInstanceOf($type, $e);
			}
			if ($desc != null)
			{
				$this->assertEquals($desc, $e->getMessage());
			}
		}
	}
}
