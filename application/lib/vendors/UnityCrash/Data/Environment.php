<?php

namespace UnityCrash\Data;

use UnityCrash\Utils\Singleton;

/**
 * 環境依存の値、特にクエリやメソッド値をラッピングします。
 *
 * @package UnityCrash\Data
 * @author Mc at danmaq
 */
final class Environment extends Singleton
{
	
	/** クエリ文字列に紐づいた連想配列のキー。 */
	const QUERY = 'QUERY_STRING';
	
	/** HTTPメソッドに紐づいた連想配列のキー。 */
	const METHOD = 'REQUEST_METHOD';
	
	/** Query string. */
	private $_query;

	/** HTTP method. */
	private $_method;
	
	/**
	 * Constructor.
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->setValues();
	}

	/**
	 * ここに連想配列を設定すると、スーパーグローバル値を破壊することなく、
	 * 環境変数値を上書きすることができます。
	 *
	 * @param array $values 環境変数値を上書きするための連想配列。
	 */
	public function setValues(array $values = null)
	{
		$values = is_null($values) ? $_SERVER : $values;
		$q = isset($values[self::QUERY]) ? $values[self::QUERY] : '';
		parse_str($q, $this->_query);
		$this->_method =
			isset($values[self::METHOD]) ? $values[self::METHOD] : 'GET';
	}

	/**
	 * クエリ文字列を分解して、連想配列化したオブジェクトを取得します。
	 *
	 * @return array クエリ連想配列。
	 */
	public function getQuery()
	{
		return $this->_query;
	}

	/**
	 * HTTPメソッドを取得します。
	 *
	 * @return string HTTPメソッド。環境変数から取得できなかった場合、GET。
	 */
	public function getMethod()
	{
		return $this->_method;
	}
}
