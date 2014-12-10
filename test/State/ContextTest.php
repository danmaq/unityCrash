<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\State\IContext;
use UnityCrash\State\EmptyState;
use UnityCrash\State\IState;
use UnityCrash\Utils\Singleton;

/** テスト用状態の基底クラス。 */
class TestState extends Singleton implements IState
{
	/** setup() を通過したかどうか。 */
	public $_setup;
	/** phase() を通過したかどうか。 */
	public $_phase;
	/** teardown() を通過したかどうか。 */
	public $_teardown;

	/** 通過フラグをリセットします。 */
	public function reset()
	{
		$this->_setup = false;
		$this->_phase = false;
		$this->_teardown = false;
	}

	public function setup(IContext $context)
	{
		$this->_setup = true;
	}

	public function phase(IContext $context)
	{
		$this->_phase = true;
	}

	public function teardown(IContext $context)
	{
		$this->_teardown = true;
	}
}

/** テスト用状態A。 */
class StateA extends TestState
{
}

/** テスト用状態B。 */
class StateB extends TestState
{
}

/** テスト用状態C。 */
class StateC extends Singleton implements IState
{
	/** カウンタと紐づけるためのキー。 */
	const COUNTER = 'counter';
	/** 最大ループ数。 */
	const MAX = 10;

	public function setup(IContext $context)
	{
		$storage =& $context->getStorage();
		$storage[self::COUNTER] = 0;
	}

	public function phase(IContext $context)
	{
		$storage =& $context->getStorage();
		if (++$storage[self::COUNTER] >= self::MAX)
		{
			$context->setNextState(EmptyState::getInstance());
		}
	}

	public function teardown(IContext $context)
	{
	}
}


class ContextTest extends TestCaseExtension
{
	/** コンテキスト オブジェクトに紐づけられたキー。 */
	const CONTEXT = 'context';
	/** コンテキストが強制的に状態変更できたかどうかに紐づけられたキー。 */
	const COMMITED = 'commited';
	/** 反復実行数に紐づけられたキー。 */
	const LOOPED = 'looped';
	
	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable['インスタンスを生成する'] = array($this, 'createInstance');
		$this->givenTable['指定の引数でインスタンスを生成する'] = array($this, 'createInstanceWithFirstState');
		$this->whenTable['ストレージの特定のキーに値を設定する'] = array($this, 'setStorage');
		$this->whenTable['次回の状態を指定する'] = array($this, 'setNextPhase');
		$this->whenTable['フェーズを実行する'] = array($this, 'runPhase');
		$this->whenTable['現在の状態を次回の状態で確定する'] = array($this, 'commitState');
		$this->whenTable['コンテキストをリセットする'] = array($this, 'terminate');
		$this->whenTable['状態の実行履歴を初期化する'] = array($this, 'resetTestState');
		$this->whenTable['反復実行する'] = array($this, 'loop');
		$this->thenTable['特定のインターフェイスを実装している'] = array($this, 'validatehasInterface');
		$this->thenTable['前回の状態は指定した通りである'] = array($this, 'validatePreviousState');
		$this->thenTable['現在の状態は指定した通りである'] = array($this, 'validateCurrentState');
		$this->thenTable['次回の状態は指定した通りである'] = array($this, 'validateNextState');
		$this->thenTable['ストレージの内容は指定した通りである'] = array($this, 'validateStorage');
		$this->thenTable['状態確定の結果は引数の通りである'] = array($this, 'validateCommitStateResult');
		$this->thenTable['アイドル状態である'] = array($this, 'validateIsTerminate');
		$this->thenTable['指定の状態の setup が呼び出されたかどうか'] = array($this, 'validateThroughSetup');
		$this->thenTable['指定の状態の phase が呼び出されたかどうか'] = array($this, 'validateThroughPhase');
		$this->thenTable['指定の状態の teardown が呼び出されたかどうか'] = array($this, 'validateThroughTeardown');
		$this->thenTable['指定数の実行回数が呼び出された'] = array($this, 'validateLoopedCount');
	}
	
	/** @scenario null で正しく初期化できる */
	public function shouldInitialize()
	{
		$this
			->given('インスタンスを生成する')
			->then('特定のインターフェイスを実装している', 'UnityCrash\State\IContext')
			->and('前回の状態は指定した通りである', EmptyState::getInstance())
			->and('現在の状態は指定した通りである', EmptyState::getInstance())
			->and('次回の状態は指定した通りである', null)
			->and('ストレージの内容は指定した通りである', array());

		$this
			->given('指定の引数でインスタンスを生成する', null)
			->then('特定のインターフェイスを実装している', 'UnityCrash\State\IContext')
			->and('前回の状態は指定した通りである', EmptyState::getInstance())
			->and('現在の状態は指定した通りである', EmptyState::getInstance())
			->and('次回の状態は指定した通りである', null)
			->and('ストレージの内容は指定した通りである', array());
	}
	
	/** @scenario 指定の状態で正しく初期化できる */
	public function shouldInitializeWithState()
	{
		$this
			->given('指定の引数でインスタンスを生成する', StateB::getInstance())
			->then('特定のインターフェイスを実装している', 'UnityCrash\State\IContext')
			->and('前回の状態は指定した通りである', EmptyState::getInstance())
			->and('現在の状態は指定した通りである', StateB::getInstance())
			->and('次回の状態は指定した通りである', null)
			->and('指定の状態の setup が呼び出されたかどうか', StateB::getInstance(), true)
			->and('指定の状態の phase が呼び出されたかどうか', StateB::getInstance(), false)
			->and('指定の状態の teardown が呼び出されたかどうか', StateB::getInstance(), false)
			->and('ストレージの内容は指定した通りである', array());
	}

	/** @scenario ストレージに値を格納できる */
	public function shouldSetStorage()
	{
		$this
			->given('インスタンスを生成する')
			->then('ストレージの内容は指定した通りである', array())
			->when('ストレージの特定のキーに値を設定する', 'foo', 255)
			->then('ストレージの内容は指定した通りである', array('foo' => 255))
			->when('ストレージの特定のキーに値を設定する', 'bar', 0)
			->then('ストレージの内容は指定した通りである', array('foo' => 255, 'bar' => 0));
	}

	/** @scenario 自動的に状態遷移できる */
	public function shouldAutoChangeState()
	{
		$this
			->given('インスタンスを生成する')
			->when('次回の状態を指定する', StateA::getInstance())
			->then('次回の状態は指定した通りである', StateA::getInstance())
			->when('フェーズを実行する')
			->then('前回の状態は指定した通りである', EmptyState::getInstance())
			->and('現在の状態は指定した通りである', StateA::getInstance())
			->and('次回の状態は指定した通りである', null)
			->when('次回の状態を指定する', StateB::getInstance())
			->then('次回の状態は指定した通りである', StateB::getInstance())
			->when('フェーズを実行する')
			->then('前回の状態は指定した通りである', StateA::getInstance())
			->and('現在の状態は指定した通りである', StateB::getInstance())
			->and('次回の状態は指定した通りである', null);
	}

	/** @scenario 手動で強制的に状態遷移できる */
	public function shouldManualChangeState()
	{
		$this
			->given('インスタンスを生成する')
			->when('次回の状態を指定する', StateA::getInstance())
			->then('次回の状態は指定した通りである', StateA::getInstance())
			->when('現在の状態を次回の状態で確定する')
			->then('状態確定の結果は引数の通りである', true)
			->and('前回の状態は指定した通りである', EmptyState::getInstance())
			->and('現在の状態は指定した通りである', StateA::getInstance())
			->and('次回の状態は指定した通りである', null)
			->when('次回の状態を指定する', StateB::getInstance())
			->then('次回の状態は指定した通りである', StateB::getInstance())
			->when('現在の状態を次回の状態で確定する')
			->then('状態確定の結果は引数の通りである', true)
			->and('前回の状態は指定した通りである', StateA::getInstance())
			->and('現在の状態は指定した通りである', StateB::getInstance())
			->and('次回の状態は指定した通りである', null)
			->when('現在の状態を次回の状態で確定する')
			->then('状態確定の結果は引数の通りである', false)
			->when('次回の状態を指定する', null)
			->when('現在の状態を次回の状態で確定する')
			->then('状態確定の結果は引数の通りである', false);
	}

	/** @scenario アイドル状態かどうかを検出できる */
	public function shouldIsChangedTerminate()
	{
		$this
			->given('インスタンスを生成する')
			->then('アイドル状態である', true)
			->when('次回の状態を指定する', StateA::getInstance())
			->then('アイドル状態である', false)
			->when('現在の状態を次回の状態で確定する')
			->then('アイドル状態である', false)
			->when('次回の状態を指定する', EmptyState::getInstance())
			->then('アイドル状態である', false)
			->when('現在の状態を次回の状態で確定する')
			->then('アイドル状態である', true);
		$this
			->given('インスタンスを生成する')
			->when('ストレージの特定のキーに値を設定する', 'foo', 255)
			->then('アイドル状態である', true)
			->when('次回の状態を指定する', StateA::getInstance())
			->then('アイドル状態である', false)
			->when('現在の状態を次回の状態で確定する')
			->then('アイドル状態である', false)
			->when('次回の状態を指定する', EmptyState::getInstance())
			->then('アイドル状態である', false)
			->when('現在の状態を次回の状態で確定する')
			->then('アイドル状態である', true);
	}

	/** @scenario コンテキストをリセットできる */
	public function shouldCanTerminate()
	{
		$this
			->given('インスタンスを生成する')
			->when('次回の状態を指定する', StateA::getInstance())
			->and('現在の状態を次回の状態で確定する')
			->and('次回の状態を指定する', StateB::getInstance())
			->and('現在の状態を次回の状態で確定する')
			->and('ストレージの特定のキーに値を設定する', 'foo', 255)
			->and('コンテキストをリセットする')
			->then('ストレージの内容は指定した通りである', array())
			->and('前回の状態は指定した通りである', EmptyState::getInstance())
			->and('現在の状態は指定した通りである', EmptyState::getInstance())
			->and('次回の状態は指定した通りである', null);
	}

	/** @scenario 状態を実行できる */
	public function shouldRunPhase()
	{
		$this
			->given('インスタンスを生成する')
			->when('状態の実行履歴を初期化する')
			->and('次回の状態を指定する', StateA::getInstance())
			->then('指定の状態の setup が呼び出されたかどうか', StateA::getInstance(), false)
			->and('指定の状態の phase が呼び出されたかどうか', StateA::getInstance(), false)
			->and('指定の状態の teardown が呼び出されたかどうか', StateA::getInstance(), false)
			->when('現在の状態を次回の状態で確定する')
			->then('指定の状態の setup が呼び出されたかどうか', StateA::getInstance(), true)
			->and('指定の状態の phase が呼び出されたかどうか', StateA::getInstance(), false)
			->and('指定の状態の teardown が呼び出されたかどうか', StateA::getInstance(), false)
			->when('状態の実行履歴を初期化する')
			->and('フェーズを実行する')
			->then('指定の状態の setup が呼び出されたかどうか', StateA::getInstance(), false)
			->and('指定の状態の phase が呼び出されたかどうか', StateA::getInstance(), true)
			->and('指定の状態の teardown が呼び出されたかどうか', StateA::getInstance(), false)
			->when('状態の実行履歴を初期化する')
			->and('次回の状態を指定する', StateB::getInstance())
			->and('フェーズを実行する')
			->then('指定の状態の setup が呼び出されたかどうか', StateA::getInstance(), false)
			->and('指定の状態の phase が呼び出されたかどうか', StateA::getInstance(), false)
			->and('指定の状態の teardown が呼び出されたかどうか', StateA::getInstance(), true)
			->and('指定の状態の setup が呼び出されたかどうか', StateB::getInstance(), true)
			->and('指定の状態の phase が呼び出されたかどうか', StateB::getInstance(), true)
			->and('指定の状態の teardown が呼び出されたかどうか', StateB::getInstance(), false);
	}

	/** @scenario 状態を反復実行できる */
	public function shouldLoop()
	{
		$this
			->given('インスタンスを生成する')
			->when('反復実行する')
			->then('指定数の実行回数が呼び出された', 0)
			->when('次回の状態を指定する', StateC::getInstance())
			->and('反復実行する')
			->then('指定数の実行回数が呼び出された', 11); // commitNextStateするのに1フェーズ消費する
	}

	/** インスタンスを生成する */
	protected function createInstance(array &$world, array $arguments)
	{
		$world[self::CONTEXT] = new Context();
	}

	/** 指定の引数でインスタンスを生成する */
	protected function createInstanceWithFirstState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$world[self::CONTEXT] = new Context($arguments[0]);
	}

	/** ストレージの特定のキーに値を設定する */
	protected function setStorage(array &$world, array $arguments)
	{
		$this->assertEquals(2, count($arguments), '引数は 2 つ必要 (キー、及び値)');
		$storage =& $world[self::CONTEXT]->getStorage();
		$storage[$arguments[0]] = $arguments[1];
	}

	/** 次回の状態を指定する */
	protected function setNextPhase(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$world[self::CONTEXT]->setNextState($arguments[0]);
	}

	/** フェーズを実行する */
	protected function runPhase(array &$world, array $arguments)
	{
		$world[self::CONTEXT]->phase();
	}

	/** 現在の状態を次回の状態で確定する */
	protected function commitState(array &$world, array $arguments)
	{
		$world[self::COMMITED] = $world[self::CONTEXT]->commitNextState();
	}

	/** コンテキストをリセットする */
	protected function terminate(array &$world, array $arguments)
	{
		$world[self::CONTEXT]->terminate();
	}

	/** 状態の実行履歴を初期化する */
	protected function resetTestState(array &$world, array $arguments)
	{
		StateA::getInstance()->reset();
		StateB::getInstance()->reset();
	}

	/** 状態の実行履歴を初期化する */
	protected function loop(array &$world, array $arguments)
	{
		$world[self::LOOPED] = $world[self::CONTEXT]->loop();
	}

	/** 特定のインターフェイスを実装している */
	protected function validatehasInterface(array &$world, array $arguments)
	{
		$this->assertInstanceOf('UnityCrash\State\Context', $world[self::CONTEXT], 'コンテキストが存在する');
		$this->assertInstanceOf('UnityCrash\State\IContext', $world[self::CONTEXT], 'コンテキストが存在する');
	}

	/** 前回の状態は指定した通りである */
	protected function validatePreviousState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getPreviousState(), '前回の状態が引数と等しい');
	}

	/** 現在の状態は指定した通りである */
	protected function validateCurrentState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getCurrentState(), '現在の状態が引数と等しい');
	}

	/** 次回の状態は指定した通りである */
	protected function validateNextState(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getNextState(), '次回の状態が引数と等しい');
	}

	/** ストレージの内容は指定した通りである */
	protected function validateStorage(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getStorage(), 'ストレージの内容は引数と同価である');
	}

	/** 状態確定の結果は引数の通りである */
	protected function validateCommitStateResult(array &$world, array $arguments)
	{
		$this->assertTrue(isset($world[self::COMMITED]), '状態確定の結果が格納されている');
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::COMMITED], '状態確定の結果は引数の通りである');
	}

	/** アイドル状態である */
	protected function validateIsTerminate(array &$world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->isterminate(), 'アイドル状態である');
	}

	/** 指定の状態の setup が呼び出されたかどうか */
	protected function validateThroughSetup(array &$world, array $arguments)
	{
		$this->assertEquals(2, count($arguments), '引数は 2 つ必要');
		$this->assertInstanceOf('TestState', $arguments[0], '引数はテスト状態クラスである');
		$this->assertEquals($arguments[1], $arguments[0]->_setup, '指定の状態の setup が呼び出されたかどうか');
	}

	/** 指定の状態の phase が呼び出されたかどうか */
	protected function validateThroughPhase(array &$world, array $arguments)
	{
		$this->assertEquals(2, count($arguments), '引数は 2 つ必要');
		$this->assertInstanceOf('TestState', $arguments[0], '引数はテスト状態クラスである');
		$this->assertEquals($arguments[1], $arguments[0]->_phase, '指定の状態の phase が呼び出されたかどうか');
	}

	/** 指定の状態の teardown が呼び出されたかどうか */
	protected function validateThroughTeardown(array &$world, array $arguments)
	{
		$this->assertEquals(2, count($arguments), '引数は 2 つ必要');
		$this->assertInstanceOf('TestState', $arguments[0], '引数はテスト状態クラスである');
		$this->assertEquals($arguments[1], $arguments[0]->_teardown, '指定の状態の teardown が呼び出されたかどうか');
	}

	/** 指定数の実行回数が呼び出された */
	protected function validateLoopedCount(array &$world, array $arguments)
	{
		$this->assertTrue(isset($world[self::LOOPED]), '反復実行の結果が格納されている');
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::LOOPED], '指定数の実行回数が呼び出された');
	}
}
