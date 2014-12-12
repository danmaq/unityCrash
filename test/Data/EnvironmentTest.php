<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Data\Environment;

class EnvironmentTest extends TestCaseExtension
{

	/** インスタンスと紐づけられるキー。 */
	const INSTANCE = 'instance';

	/** テスト データ。 */
	private $env = array(
		'QUERY_STRING' => '_url=/hoge/%e3%81%bb%e3%81%92/fu,ga&foo=bar&baz=qux',
		'REQUEST_METHOD' => 'POST');

	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable['インスタンスを取得する'] = array($this, 'getInstance');
		$this->whenTable['環境を設定する'] = array($this, 'setValues');
		$this->whenTable['カレントディレクトリ値を改変する'] = array($this, 'setCurrentDirectory');
		$this->thenTable['インスタンスが指定したクラスかそのサブクラスである'] = array($this, 'isInstance');
		$this->thenTable['現在のクエリが取得できている'] = array($this, 'validateQuery');
		$this->thenTable['現在のHTTPメソッドが取得できている'] = array($this, 'validateMethod');
		$this->thenTable['現在のRESTパラメータが取得できている'] = array($this, 'validateRestParams');
		$this->thenTable['カレントディレクトリ値が正しい'] = array($this, 'validateCurrentDirectory');
		$this->thenTable['存在しないカレントディレクトリ値は設定できない'] = array($this, 'errorIfSetInvalidDirectory');
	}

	///////////////////////////////////////////////////////

	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('インスタンスが指定したクラスかそのサブクラスである', 'UnityCrash\Data\Environment')
			->and('インスタンスが指定したクラスかそのサブクラスである', 'UnityCrash\Utils\Singleton');
	}

	/** @scenario クエリを取得でき、置き換えられる */
	public function shouldGetQuery()
	{
		$this
			->given('インスタンスを取得する')
			->when('環境を設定する', null)
			->then('現在のクエリが取得できている', array())
			->when('環境を設定する', $this->env)
			->then('現在のクエリが取得できている',
				array('_url' => '/hoge/ほげ/fu,ga', 'foo' => 'bar', 'baz' => 'qux'))
			->when('環境を設定する', null)
			->then('現在のクエリが取得できている', array());
	}

	/** @scenario REST URLを取得でき、置き換えられる */
	public function shouldRestParams()
	{
		$this
			->given('インスタンスを取得する')
			->when('環境を設定する', null)
			->then('現在のRESTパラメータが取得できている', array())
			->when('環境を設定する', $this->env)
			->then('現在のRESTパラメータが取得できている', array('hoge' ,'ほげ', array('fu' ,'ga')))
			->when('環境を設定する', null)
			->then('現在のRESTパラメータが取得できている', array());
	}

	/** @scenario メソッド種別を取得でき、置き換えられる */
	public function shouldGetMethod()
	{
		$q = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$this
			->given('インスタンスを取得する')
			->when('環境を設定する', null)
			->then('現在のHTTPメソッドが取得できている', $q)
			->when('環境を設定する', $this->env)
			->then('現在のHTTPメソッドが取得できている', 'POST')
			->when('環境を設定する', null)
			->then('現在のHTTPメソッドが取得できている', $q);
	}

	/** @scenario カレントディレクトリを取得でき、置き換えられる */
	public function shouldCurrentPath()
	{
		$newPath = getcwd() . "/application";
		$this
			->given('インスタンスを取得する')
			->then('カレントディレクトリ値が正しい', getcwd())
			->when('カレントディレクトリ値を改変する', $newPath)
			->then('カレントディレクトリ値が正しい', $newPath)
			->then('存在しないカレントディレクトリ値は設定できない', $newPath . 'hoge')
			->when('カレントディレクトリ値を改変する', null)
			->then('カレントディレクトリ値が正しい', getcwd());
	}

	///////////////////////////////////////////////////////

	/** インスタンスを取得する */
	protected function getInstance(array &$world, array $arguments)
	{
		$world[self::INSTANCE] = Environment::getInstance();
	}

	/** インスタンスが指定したクラスかそのサブクラスである */
	protected function isInstance(array &$world, array $arguments)
	{
		$this->assertInstanceOf(
			$arguments[0], $world[self::INSTANCE], 'インスタンスが指定したクラスかそのサブクラスである');
	}

	/** 環境を設定する */
	protected function setValues(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$world[self::INSTANCE]->setValues($arguments[0]);
	}

	/** カレントディレクトリ値を改変する */
	protected function setCurrentDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$world[self::INSTANCE]->setCurrentDirectory($arguments[0]);
	}

	/** 現在のクエリが取得できている */
	protected function validateQuery(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::INSTANCE]->getQuery(), '現在のクエリが取得できている');
	}

	/** 現在のHTTPメソッドが取得できている */
	protected function validateMethod(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::INSTANCE]->getMethod(), '現在のHTTPメソッドが取得できている');
	}

	/** 現在のRESTパラメータが取得できている */
	protected function validateRestParams(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::INSTANCE]->getRestParams(), '現在のRESTパラメータが取得できている');
	}

	/** カレントディレクトリ値が正しい */
	protected function validateCurrentDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::INSTANCE]->getCurrentDirectory(), 'カレントディレクトリ値が正しい');
	}

	/** 存在しないカレントディレクトリ値は設定できない */
	protected function errorIfSetInvalidDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$key = self::INSTANCE;
		$callback =
			function () use ($world, $arguments, $key)
			{
				$world[$key]->setCurrentDirectory($arguments[0]);
			};
		$this->assertException(
			$callback, 'InvalidArgumentException', "The path does not exist. :{$arguments[0]}");
	}
}