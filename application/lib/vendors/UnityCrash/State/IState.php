<?php

namespace UnityCrash\State;

/**
 * Stateパターンにおける状態の定義です。
 * このインターフェイスを実装して、ロジック部分を記述します。
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
interface IState
{

	/**
	 * コンテキストにこの状態が適用された直後に呼び出されます。
	 * 現在の状態の初期化に必要な挙動があれば、ここに実装してください。
	 *
	 * @param IContext $context コンテキスト。
	 */
	public function setup(IContext $context);

	/**
	 * コンテキストに状態が適用されている間、 IContext.phase()
	 * メソッドを実行することで、このメソッドが呼び出されます。
	 * 現在の状態の挙動については、ここに実装してください。
	 *
	 * @param IContext $context コンテキスト。
	 */
	public function phase(IContext $context);

	/**
	 * コンテキストが別の状態へと遷移される直前に呼び出されます。
	 * 現在の状態を終える際に後片付けが必要であれば、ここに実装してください。
	 *
	 * @param IContext $context コンテキスト。
	 */
	public function teardown(IContext $context);
}
