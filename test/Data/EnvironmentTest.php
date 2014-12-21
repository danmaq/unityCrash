<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Data\Environment;

class EnvironmentTest extends TestCaseExtension
{

	/** テスト データ。 */
	private $env = array(
		'QUERY_STRING' => '_url=/hoge/%e3%81%bb%e3%81%92/fu,ga&foo=bar&baz=qux',
		'REQUEST_METHOD' => 'POST',
		'HTTP_ACCEPT_LANGUAGE' => 'ja;q=0.1,en-US;q=0.2,zh;q=0.6',
		'HTTP_ACCEPT' => 'text/html, application/xhtml+xml, */*',
		'PHP_SELF' => '/hoge/fuga.php');

	/** テスト データ。 */
	private $envAlternative = array(
		'QUERY_STRING' => '_url=/foo/bar',
		'REQUEST_METHOD' => 'PUT',
		'HTTP_ACCEPT_LANGUAGE' => 'ja;q=1,en-US;q=0.6,zh;q=0.1',
		'HTTP_ACCEPT' => 'text/html, */*',
		'PHP_SELF' => '/index.php');

	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable[''] = array($this, 'noop');
		$this->whenTable['環境を設定する'] = array($this, 'setValues');
		$this->whenTable['カレントディレクトリ値を改変する'] = array($this, 'setCurrentDirectory');
		$this->thenTable['現在のクエリが取得できている'] = array($this, 'validateQuery');
		$this->thenTable['現在のHTTPメソッドが取得できている'] = array($this, 'validateMethod');
		$this->thenTable['現在のRESTパラメータが取得できている'] = array($this, 'validateRestParams');
		$this->thenTable['現在の基底パスが取得できている'] = array($this, 'validateBasePath');
		$this->thenTable['カレントディレクトリ値が正しい'] = array($this, 'validateCurrentDirectory');
		$this->thenTable['存在しないカレントディレクトリ値は設定できない'] = array($this, 'errorIfSetInvalidDirectory');
	}

	///////////////////////////////////////////////////////
	
	/** @scenario クエリを取得でき、置き換えられる */
	public function shouldGetQuery()
	{
		$this
			->given('')
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
			->given('')
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
			->given('')
			->when('環境を設定する', null)
			->then('現在のHTTPメソッドが取得できている', $q)
			->when('環境を設定する', $this->env)
			->then('現在のHTTPメソッドが取得できている', 'POST')
			->when('環境を設定する', null)
			->then('現在のHTTPメソッドが取得できている', $q);
	}

	/** @scenario 基底パスを取得でき、置き換えられる */
	public function shouldGetBasePath()
	{
		$phpunitPath = pathinfo(`which phpunit`);
		$this
			->given('')
			->when('環境を設定する', null)
			->then('現在の基底パスが取得できている', $phpunitPath['dirname'])
			->when('環境を設定する', $this->env)
			->then('現在の基底パスが取得できている', '/hoge')
			->when('環境を設定する', null)
			->then('現在の基底パスが取得できている', $phpunitPath['dirname']);
	}

	/** @scenario カレントディレクトリを取得でき、置き換えられる */
	public function shouldCurrentPath()
	{
		$newPath = getcwd() . "/application";
		$this
			->given('')
			->then('カレントディレクトリ値が正しい', getcwd())
			->when('カレントディレクトリ値を改変する', $newPath)
			->then('カレントディレクトリ値が正しい', $newPath)
			->then('存在しないカレントディレクトリ値は設定できない', $newPath . 'hoge')
			->when('カレントディレクトリ値を改変する', null)
			->then('カレントディレクトリ値が正しい', getcwd());
	}

	///////////////////////////////////////////////////////

	/** 何もしない */
	protected function noop(array &$world, array $arguments)
	{
	}

	/** 環境を設定する */
	protected function setValues(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		Environment::setValues($arguments[0]);
	}

	/** カレントディレクトリ値を改変する */
	protected function setCurrentDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		Environment::setCurrentDirectory($arguments[0]);
	}

	/** 現在のクエリが取得できている */
	protected function validateQuery(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], Environment::getQuery(), '現在のクエリが取得できている');
	}

	/** 現在のHTTPメソッドが取得できている */
	protected function validateMethod(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], Environment::getMethod(), '現在のHTTPメソッドが取得できている');
	}

	/** 現在のRESTパラメータが取得できている */
	protected function validateRestParams(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], Environment::getRestParams(), '現在のRESTパラメータが取得できている');
	}

	/** 現在の基底パスが取得できている */
	protected function validateBasePath(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], Environment::getBasePath(), '現在の基底パスが取得できている');
	}

	/** カレントディレクトリ値が正しい */
	protected function validateCurrentDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], Environment::getCurrentDirectory(), 'カレントディレクトリ値が正しい');
	}

	/** 存在しないカレントディレクトリ値は設定できない */
	protected function errorIfSetInvalidDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$callback =
			function () use ($world, $arguments)
			{
				Environment::setCurrentDirectory($arguments[0]);
			};
		$this->assertException(
			$callback, 'InvalidArgumentException', "The path does not exist. :{$arguments[0]}");
	}
}
