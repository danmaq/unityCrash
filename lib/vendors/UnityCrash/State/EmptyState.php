<?php

namespace UnityCrash\State;

/**
 * Stateパターンにおける終末の状態の実装です。
 * コンテキストがこの状態に至った場合、外部から状態操作を加えない限り動作しません。
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
final class EmptyState implements iState
{

	/**
	 * コンテキストにこの状態が適用された直後に呼び出されます。
	 *
	 * @param object $context コンテキスト。
	 */
	public function setup($context)
	{
	}

	/**
	 * コンテキストに状態が適用されている間、反復して呼び出されます。
	 *
	 * @param object $context コンテキスト。
	 */
	public function loop($context)
	{
	}

	/**
	 * コンテキストが別の状態へと遷移される直前に呼び出されます。
	 *
	 * @param object $context コンテキスト。
	 */
	public function teardown($context)
	{
	}
}
