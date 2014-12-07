<?php

/**
 * PHPUnit (Story) の拡張機能。
 *
 * @author Mc at danmaq
 */
abstract class TestCaseExtension extends PHPUnit_Extensions_Story_TestCase
{

	/** アクションの定義をキーに挙動関数を格納するテーブル。 */
	protected $givenTable = array();

	/** アクションの定義をキーに挙動関数を格納するテーブル。 */
	protected $whenTable = array();

	/** アクションの定義をキーに挙動関数を格納するテーブル。 */
	protected $thenTable = array();

	/**
	 * 初期状態の挙動を実行します。
	 *
	 * @param array $world ワーク テーブル。
	 * @param string $action アクションの定義。
	 * @param string $arguments アクションの定義に関連付けられた引数一覧。
	 */
	public function runGiven(&$world, $action, $arguments)
	{
		$this->action($this->givenTable, $world, $action, $arguments);
	}

	/**
	 * 前提状態を作るための挙動を実行します。
	 *
	 * @param array $world ワーク テーブル。
	 * @param string $action アクションの定義。
	 * @param string $arguments アクションの定義に関連付けられた引数一覧。
	 */
	public function runWhen(&$world, $action, $arguments)
	{
		$this->action($this->whenTable, $world, $action, $arguments);
	}

	/**
	 * 想定をテストするための挙動を実行します。
	 *
	 * @param array $world ワーク テーブル。
	 * @param string $action アクションの定義。
	 * @param string $arguments アクションの定義に関連付けられた引数一覧。
	 */
	public function runThen(&$world, $action, $arguments)
	{
		$this->action($this->thenTable, $world, $action, $arguments);
	}

	/**
	 * 例外をテストします。
	 *
	 * @param callable $func 実行する関数。引数は渡しません。
	 * @param string $type 想定する例外の型。省略時は判定しません。
	 * @param string $desc 想定する例外のメッセージ。省略時は判定しません。
	 */
	public function assertException($func, $type = null, $desc = null)
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
	
	/**
	 * テーブルに登録されているアクションを実行します。
	 *
	 * @param array $table アクションの定義をキーに挙動関数を格納するテーブル。
	 * @param callable $world ワークテーブル。
	 * @param string $action アクションの定義。
	 * @param string $arguments アクションの定義に関連付けられた引数一覧。
	 */
	private function action(array $table, array &$world, $action, array $arguments)
	{
		if (isset($table[$action]))
		{
			$world = call_user_func($table[$action], $world, $arguments);
		}
		else
		{
			$this->notImplemented($action);
		}
	}
}
