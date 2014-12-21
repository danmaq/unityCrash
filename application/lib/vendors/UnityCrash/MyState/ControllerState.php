<?php

namespace UnityCrash\MyState;

use UnityCrash\Data\Constants;
use UnityCrash\Data\Environment;
use UnityCrash\State\IContext;
use UnityCrash\State\IState;
use UnityCrash\Utils\Singleton;

/**
 * WebからのREST入力に対応して、動的に次に必要な処理を読み込むための、
 * いわゆるMVCモデルにおけるコントローラに該当する状態。
 *
 * @package UnityCrash\MyState
 * @author Mc at danmaq
 */
final class ControllerState extends Singleton implements IState
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
	 * @param IContext $context コンテキスト。
	 */
	public function phase(IContext $context)
	{
		$rest = Environment::getRestParams();
		$entry = count($rest) === 0 ? Constants::REST_MESSAGE : $rest[0];
		$context->setNextState(include self::getPath($entry));
	}

	/**
	 * コンテキストが別の状態へと遷移される直前に呼び出されます。
	 *
	 * @param IContext $context コンテキスト。
	 */
	public function teardown(IContext $context)
	{
	}

	/**
	 * RESTパラメータに合わせたパスを取得します。
	 *
	 * @param string $entry RESTエントリポイント。
	 * @return string パス。
	 */
	private static function getPath($entry)
	{
		$cwd = Environment::getCurrentDirectory();
		$path = "{$cwd}/rest/{$entry}.php";
		$exists = $entry === Constants::REST_ERROR || file_exists($path);
		return $exists ? $path : self::getPath(Constants::REST_ERROR);
	}
}
