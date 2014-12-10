<?php

namespace UnityCrash\MyState;

use UnityCrash\State\IContext;
use UnityCrash\State\IState;
use UnityCrash\Utils\Singleton;

/**
 * ビューを生成するための状態。
 * MVCモデルにおけるビュー(コントローラ)に該当する状態。
 *
 * @package UnityCrash\MyState
 * @author Mc at danmaq
 */
final class ViewState extends Singleton implements IState
{

	/**
	 * コンテキストにこの状態が適用された直後に呼び出されます。
	 *
	 * @param IContext $context コンテキスト。
	 */
	public function setup(IContext $context)
	{
	}

	/**
	 * コンテキストに状態が適用されている間、 IContext.phase()
	 * メソッドを実行することで、このメソッドが呼び出されます。
	 *
	 * @param iContext $context コンテキスト。
	 */
	public function phase(IContext $context)
	{
	}

	/**
	 * コンテキストが別の状態へと遷移される直前に呼び出されます。
	 *
	 * @param IContext $context コンテキスト。
	 */
	public function teardown(IContext $context)
	{
	}
}
