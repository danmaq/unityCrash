<?php

namespace UnityCrash\State;

/**
 * State パターンにおける状態を保持できるコンテキストの定義です。
 * このインターフェイスを実装して、コンテキストを記述します。
 * 既定の実装として、Contextクラスがありますので、そのクラスと
 * ロジックを実装した状態クラスを組み合わせることにより、
 * State パターンに準拠したロジックを簡単に作ることができます。
 *
 * @package UnityCrash\State
 * @author  Mc at danmaq
 */
interface IContext
{

	/**
	 * 汎用的に使用できる連想配列を取得します。
	 *
	 * @return array 連想配列。
	 */
	public function &getStorage();

	/**
	 * 前回有効だった状態を取得します。
	 *
	 * @return iState 状態。
	 */
	public function getPreviousState();

	/**
	 * 現在の状態を取得します。
	 *
	 * @return iState 状態。
	 */
	public function getCurrentState();

	/**
	 * 次に遷移すべき状態を取得します。
	 *
	 * @return iState 状態。
	 */
	public function getNextState();

	/**
	 * 次に遷移すべき状態を設定します。
	 *
	 * @param iState $state 状態。
	 */
	public function setNextState(iState $state = null);

	/**
	 * 状態が終了されたかどうかを取得します。
	 *
	 * @return bool 状態が終了された場合、true。
	 */
	public function isTerminate();

	/**
	 * 状態を実行します。
	 */
	public function phase();

	/**
	 * 次に遷移すべき状態が設定されている場合、状態を確定します。
	 */
	public function commitNextState();

	/**
	 * 空の状態を設定し、値をリセットして状態を終了します。
	 */
	public function terminate();
}
