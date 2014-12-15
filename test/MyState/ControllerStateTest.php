<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Data\Environment;
use UnityCrash\MyState\ControllerState;
use UnityCrash\MyState\MessageState;
use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class ControllerStateTest extends TestCaseExtension
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
		$this->givenTable['初期状態としてインスタンスを食わせたコンテキストを取得する'] = array($this, 'createContextWithState');
		$this->whenTable['コンテキストを実行する'] = array($this, 'runPhase');
		$this->whenTable['REST情報を設定する'] = array($this, 'emulateHTTPRequest');
		$this->whenTable['次の状態を指定する'] = array($this, 'setNextState');
		$this->whenTable['カレントディレクトリ値を改変する'] = array($this, 'setCurrentDirectory');
		$this->thenTable['指定したインスタンスが取得できる'] = array($this, 'validateInstance');
		$this->thenTable['ワークが書き換わらない'] = array($this, 'validateWorkIsEmpty');
		$this->thenTable['前回の状態が正しい'] = array($this, 'validatePreviousState');
		$this->thenTable['現在の状態が正しい'] = array($this, 'validateCurrentState');
		$this->thenTable['次回の状態が正しい'] = array($this, 'validateNextState');
	}

	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'UnityCrash\MyState\ControllerState')
			->and('指定したインスタンスが取得できる', 'UnityCrash\State\IState');
	}

	/** @scenario 状態初期化ができる(何もしない) */
	public function shouldEnterState()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->then('ワークが書き換わらない')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', ControllerState::getInstance())
			->and('次回の状態が正しい', null);
	}

	/** @scenario 既定の環境の場合、次の状態を選択できる */
	public function shouldSelectNextStateAtDefault()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('カレントディレクトリ値を改変する', '/application')
			->and('コンテキストを実行する')
			->and('カレントディレクトリ値を改変する', null)
			->then('ワークが書き換わらない')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', ControllerState::getInstance())
			->and('次回の状態が正しい', MessageState::getInstance());
	}

	/** @scenario 特定の環境の場合、次の状態を選択できる */
	public function shouldSelectNextStateAtDesignate()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('REST情報を設定する', 'get', '/error/404')
			->and('カレントディレクトリ値を改変する', '/application')
			->and('コンテキストを実行する')
			->and('カレントディレクトリ値を改変する', null)
			->then('ワークが書き換わらない')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', ControllerState::getInstance())
			->and('次回の状態が正しい', MessageState::getInstance());
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('REST情報を設定する', 'post', '/hoge')
			->and('カレントディレクトリ値を改変する', '/application')
			->and('コンテキストを実行する')
			->and('カレントディレクトリ値を改変する', null)
			->then('ワークが書き換わらない')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', ControllerState::getInstance())
			->and('次回の状態が正しい', MessageState::getInstance());
	}

	/** @scenario 状態終了ができる(何もしない) */
	public function shouldExitState()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('カレントディレクトリ値を改変する', '/application')
			->and('コンテキストを実行する')
			->and('コンテキストを実行する')
			->and('カレントディレクトリ値を改変する', null)
			->then('ワークが書き換わらない');
		// 状態遷移そのものはContextのテストでしているので、ここではテストしない。
	}
	
	/** インスタンスを取得する */
	protected function getInstance(array &$world, array $arguments)
	{
		$this->assertTrue(class_exists('UnityCrash\MyState\ControllerState'), 'クラスが存在する');
		$world[self::INSTANCE] = ControllerState::getInstance();
	}

	/** 初期状態としてインスタンスを食わせたコンテキストを取得する */
	protected function createContextWithState(array &$world, array $arguments)
	{
		$this->assertTrue(class_exists('UnityCrash\MyState\ControllerState'), 'クラスが存在する');
		$world[self::INSTANCE] = ControllerState::getInstance();
		$world[self::CONTEXT] = new Context($world[self::INSTANCE]);
	}

	/** コンテキストを実行する */
	protected function runPhase(array &$world, array $arguments)
	{
		$this->assertInstanceOf('UnityCrash\State\Context', $world[self::CONTEXT], 'コンテキストが存在する');
		$world[self::CONTEXT]->phase();
	}

	/** REST情報を設定する */
	protected function emulateHTTPRequest(array &$world, array $arguments)
	{
		$this->assertEquals(2, count($arguments), '引数は 2 つ必要');
		$values =
			array('REQUEST_METHOD' => $arguments[0], 'QUERY_STRING' => "_url={$arguments[1]}");
		Environment::getInstance()->setValues($values);
	}

	/** カレントディレクトリ値を改変する */
	protected function setCurrentDirectory(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		Environment::getInstance()->setCurrentDirectory(getcwd() . $arguments[0]);
	}

	/** 次の状態を指定する */
	protected function setNextState(array &$world, array $arguments)
	{
		$this->assertInstanceOf('UnityCrash\State\Context', $world[self::CONTEXT], 'コンテキストが存在する');
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$world[self::CONTEXT]->setNextState($arguments[0]);
	}

	/** 指定したインスタンスが取得できる */
	protected function validateInstance(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertInstanceOf($arguments[0], $world[self::INSTANCE]);
	}

	/** ワークが書き換わらない */
	protected function validateWorkIsEmpty(array &$world, array $arguments)
	{
		$this->assertInstanceOf('UnityCrash\State\Context', $world[self::CONTEXT], 'コンテキストが存在する');
		$this->assertEquals(0, count($world[self::CONTEXT]->getStorage()), 'ワークが空である');
	}

	/** 前回の状態が正しい */
	protected function validatePreviousState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getPreviousState(), '前回の状態が引数と等しい');
	}

	/** 現在の状態が正しい */
	protected function validateCurrentState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getCurrentState(), '現在の状態が引数と等しい');
	}

	/** 次回の状態が正しい */
	protected function validateNextState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getNextState(), '次回の状態が引数と等しい');
	}
}
