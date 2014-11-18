<?php

namespace UnityCrash\State;

/**
 * Stateパターンにおける状態の定義です。
 * このインターフェイスを実装して、ロジック部分を記述します。
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
interface iState
{

	/**
	 * コンテキストにこの状態が適用された直後に呼び出されます。
	 *
	 * @param object $context コンテキスト。
	 */
	public function setup($context);

	/**
	 * コンテキストに状態が適用されている間、反復して呼び出されます。
	 *
	 * @param object $context コンテキスト。
	 */
	public function loop($context);

	/**
	 * コンテキストが別の状態へと遷移される直前に呼び出されます。
	 *
	 * @param object $context コンテキスト。
	 */
	public function teardown($context);
}
