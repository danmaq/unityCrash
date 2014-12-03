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
	 *
	 * @param iContext $context コンテキスト。
	 */
	public function setup(iContext $context);

	/**
	 * コンテキストに状態が適用されている間、反復して呼び出されます。
	 *
	 * @param iContext $context コンテキスト。
	 */
	public function loop(iContext $context);

	/**
	 * コンテキストが別の状態へと遷移される直前に呼び出されます。
	 *
	 * @param iContext $context コンテキスト。
	 */
	public function teardown(iContext $context);
}
