<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Data\Constants;
use UnityCrash\Data\Environment;
use UnityCrash\MyState\MessageState;
use UnityCrash\MyState\ViewState;
use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class MessageStateTest extends TestCaseExtension
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
		$this->givenTable['初期状態としてインスタンスを食わせたコンテキストを取得する'] = array($this, 'getInstanceWithContext');
		$this->whenTable['REST情報を設定する'] = array($this, 'setRestParams');
		$this->whenTable['コンテキストを生成する'] = array($this, 'createContext');
		$this->whenTable['コンテキストを実行する'] = array($this, 'phase');
		$this->whenTable['teardown を実行する'] = array($this, 'callTeardown');
		$this->whenTable['環境設定をリセットする'] = array($this, 'resetParams');
		$this->whenTable['コンテキストに情報を設定する'] = array($this, 'setStorage');
		$this->thenTable['指定したインスタンスが取得できる'] = array($this, 'validateInstanceType');
		$this->thenTable['ワークのエラー種別が正しい'] = array($this, 'validateMessageType');
		$this->thenTable['ワークのエラーコードが正しい'] = array($this, 'validateMessageId');
		$this->thenTable['ワークのメッセージが正しい'] = array($this, 'validateMessage');
		$this->thenTable['ワークは変化しない'] = array($this, 'validateNoChangeStorage');
		$this->thenTable['前回の状態が正しい'] = array($this, 'validatePreviousState');
		$this->thenTable['現在の状態が正しい'] = array($this, 'validateCurrentState');
		$this->thenTable['次回の状態が正しい'] = array($this, 'validateNextState');
	}

	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'UnityCrash\MyState\MessageState')
			->and('指定したインスタンスが取得できる', 'UnityCrash\State\IState');
	}

	/** @scenario エラーコードをRESTパラメータより取得、ワークに設定できる */
	public function shouldSetStatusFromRest()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('REST情報を設定する', 'get', '/error/500')
			->and('コンテキストを実行する')
			->and('環境設定をリセットする')
			->then('ワークのエラー種別が正しい', true)
			->and('ワークのエラーコードが正しい', 500)
			->and('ワークのメッセージが正しい', 'Internal Server Error.')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', MessageState::getInstance())
			->and('次回の状態が正しい', ViewState::getInstance());
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('REST情報を設定する', 'get', '/error/200')
			->and('コンテキストを実行する')
			->and('環境設定をリセットする')
			->then('ワークのエラー種別が正しい', false)
			->and('ワークのエラーコードが正しい', 200)
			->and('ワークのメッセージが正しい', 'Completed!')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', MessageState::getInstance())
			->and('次回の状態が正しい', ViewState::getInstance());
	}

	/** @scenario エラーコードをコンテキストのステータスより取得、ワークに設定できる */
	public function shouldSetStatusFromStorageStatus()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('コンテキストに情報を設定する', 404)
			->and('コンテキストを実行する')
			->then('ワークのエラー種別が正しい', true)
			->and('ワークのエラーコードが正しい', 404)
			->and('ワークのメッセージが正しい', 'Not Found.')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', MessageState::getInstance())
			->and('次回の状態が正しい', ViewState::getInstance());
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('コンテキストに情報を設定する', 200)
			->and('コンテキストを実行する')
			->then('ワークのエラー種別が正しい', false)
			->and('ワークのエラーコードが正しい', 200)
			->and('ワークのメッセージが正しい', 'Completed!')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', MessageState::getInstance())
			->and('次回の状態が正しい', ViewState::getInstance());
	}

	/** @scenario 既定のステータスをワークに設定できる */
	public function shouldSetDefaultStatus()
	{
		$this
			->given('初期状態としてインスタンスを食わせたコンテキストを取得する')
			->when('コンテキストを実行する')
			->then('ワークのエラー種別が正しい', false)
			->and('ワークのエラーコードが正しい', 0)
			->and('ワークのメッセージが正しい', '')
			->and('前回の状態が正しい', EmptyState::getInstance())
			->and('現在の状態が正しい', MessageState::getInstance())
			->and('次回の状態が正しい', ViewState::getInstance());
	}

	/** @scenario teardown は何もしない */
	public function shouldTeardown()
	{
		$this
			->given('インスタンスを取得する')
			->when('コンテキストを生成する')
			->and('teardown を実行する')
			->then('ワークは変化しない', true)
			->and('次回の状態が正しい', null);
	}

	/** インスタンスを取得する */
	protected function getInstance(array & $world, array $arguments)
	{
		$this->assertTrue(class_exists('UnityCrash\MyState\MessageState'), 'クラスが存在する');
		$world[self::INSTANCE] = MessageState::getInstance();
	}

	/** 初期状態としてインスタンスを食わせたコンテキストを取得する */
	protected function getInstanceWithContext(array & $world, array $arguments)
	{
		$this->assertTrue(class_exists('UnityCrash\MyState\MessageState'), 'クラスが存在する');
		$world[self::INSTANCE] = MessageState::getInstance();
		$world[self::CONTEXT] = new Context($world[self::INSTANCE]);
	}

	/** REST情報を設定する */
	protected function setRestParams(array & $world, array $arguments)
	{
		$this->assertEquals(2, count($arguments), '引数は 2 つ必要');
		$values =
			array('REQUEST_METHOD' => $arguments[0], 'QUERY_STRING' => "_url={$arguments[1]}");
		Environment::getInstance()->setValues($values);
	}

	/** コンテキストを生成する */
	protected function createContext(array & $world, array $arguments)
	{
		$world[self::CONTEXT] = new Context();
	}

	/** コンテキストを実行する */
	protected function phase(array & $world, array $arguments)
	{
		$world[self::CONTEXT]->phase();
	}

	/** teardown を実行する */
	protected function callTeardown(array & $world, array $arguments)
	{
		$world[self::INSTANCE]->teardown($world[self::CONTEXT]);
	}

	/** 環境設定をリセットする */
	protected function resetParams(array & $world, array $arguments)
	{
		Environment::getInstance()->setValues();
	}

	/** コンテキストに情報を設定する */
	protected function setStorage(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$storage =& $world[self::CONTEXT]->getStorage();
		$storage[Constants::DATA_MESSAGE_ID] = $arguments[0];
	}

	/** 指定したインスタンスが取得できる */
	protected function validateInstanceType(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertInstanceOf($arguments[0], $world[self::INSTANCE]);
	}

	/** ワークのエラー種別が正しい */
	protected function validateMessageType(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$storage = $world[self::CONTEXT]->getStorage();
		$this->assertTrue(
			isset($storage[Constants::DATA_MESSAGE_ERROR]), 'ワークのエラー種別が正しい');
		$this->assertEquals(
			$arguments[0], $storage[Constants::DATA_MESSAGE_ERROR], 'ワークのエラー種別が正しい');
	}

	/** ワークのエラーコードが正しい */
	protected function validateMessageId(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$storage = $world[self::CONTEXT]->getStorage();
		$this->assertTrue(
			isset($storage[Constants::DATA_MESSAGE_ID]), 'ワークのエラーコードが正しい');
		$this->assertEquals(
			$arguments[0], $storage[Constants::DATA_MESSAGE_ID], 'ワークのエラーコードが正しい');
	}

	/** ワークのメッセージが正しい */
	protected function validateMessage(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$storage = $world[self::CONTEXT]->getStorage();
		$this->assertTrue(
			isset($storage[Constants::DATA_MESSAGE_BODY]), 'ワークのメッセージが正しい');
		$this->assertEquals(
			$arguments[0], $storage[Constants::DATA_MESSAGE_BODY], 'ワークのメッセージが正しい');
	}

	/** ワークは変化しない */
	protected function validateNoChangeStorage(array & $world, array $arguments)
	{
		$this->assertEquals(0, count($world[self::CONTEXT]->getStorage()), 'ワークは変化しない');
	}

	/** 前回の状態が正しい */
	protected function validatePreviousState(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getPreviousState(), '前回の状態が引数と等しい');
	}

	/** 現在の状態が正しい */
	protected function validateCurrentState(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getCurrentState(), '現在の状態が引数と等しい');
	}

	/** 次回の状態が正しい */
	protected function validateNextState(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertEquals($arguments[0], $world[self::CONTEXT]->getNextState(), '次回の状態が引数と等しい');
	}
}
