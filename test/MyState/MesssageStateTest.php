<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

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
			->and('ワークのメッセージが正しい', 'Internal Server Error')
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
			->and('ワークのメッセージが正しい', 'Not Found')
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
}
