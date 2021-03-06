<?php

namespace UnityCrash\Utils;

use \LogicException;
use UnityCrash\Data\Message;

/**
 * Singleton パターンにおける既定の実装です。
 * この抽象クラスを継承することで、Singleton クラスとして機能します。
 *
 * @package UnityCrash\Utils
 * @author Mc at danmaq
 */
abstract class Singleton
{

	/** クラス オブジェクト一覧。 */
	private static $_instance = array();

	/**
	 * コンストラクタ。
	 */
	protected function __construct()
	{
	}

	/**
	 * クラスの複製を抑制します。
	 */
	final public function __clone()
	{
		throw new LogicException(_('Singleton object must not clone.'));
	}

	/**
	 * シングルトン オブジェクトを取得します。
	 *
	 * @return シングルトン オブジェクト。
	 */
	public static function getInstance()
	{
		$key = get_called_class();
		if (!isset(self::$_instance[$key]))
		{
			self::$_instance[$key] = new static();
		}
		return self::$_instance[$key];
	}
}
