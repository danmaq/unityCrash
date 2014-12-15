<?php

namespace UnityCrash\Data;

use InvalidArgumentException;
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
	
	/** RESTパラメータに用いるためのURL。 */
	const URI = '_url';

	/** Query string. */
	private $_query;

	/** REST Parameters. */
	private $_rest;

	/** HTTP method. */
	private $_method;

	/** Current directory. */
	private $_currentDirectory;

	/**
	 * Constructor.
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->setValues();
		$this->setCurrentDirectory();
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
		$this->_query = self::getQueryFromEnvironment($values);
		$this->_method = self::getMethodFromEnvironment($values);
		$this->_rest = self::getRestParamsFromEnvironment($this->getQuery());
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
	 * クエリ配列から抽出・分解されたRESTパラメータを取得します。
	 *
	 * @return array RESTパラメータ。
	 */
	public function getRestParams()
	{
		return $this->_rest;
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

	/**
	 * 環境変数からクエリ文字列を抽出分解して、
	 * 連想配列化したオブジェクトを生成します。
	 *
	 * @param array $params 環境変数値を格納した連想配列。
	 * @return string クエリ連想配列。
	 */
	private static function getQueryFromEnvironment(array $params)
	{
		$query = isset($params[self::QUERY]) ? $params[self::QUERY] : '';
		parse_str($query, $result);
		return $result;
	}

	/**
	 * カレントディレクトリを取得します。
	 *
	 * @return string カレントディレクトリ。
	 * setCurrentDirectory メソッドで改変を加えている場合、その値。
	 */
	public function getCurrentDirectory()
	{
		return $this->_currentDirectory;
	}

	/**
	 * カレントディレクトリを改変します。
	 * 引数を省略すると、既定のカレントディレクトリを設定します。
	 *
	 * @param string $path 改変する値。
	 * @throws InvalidArgumentException 存在しないパスを指定した場合。
	 */
	public function setCurrentDirectory($path = null)
	{
		$result = is_null($path) ? getcwd() : $path;
		if (!file_exists($result))
		{
			$message = _("The path does not exist. :{$result}");
			throw new InvalidArgumentException($message);
		}
		$this->_currentDirectory = $result;
	}

	/**
	 * 環境変数からHTTPメソッドを抽出して取得します。
	 *
	 * @param array $params 環境変数値を格納した連想配列。
	 * @return string HTTPメソッド。環境変数から取得できなかった場合、GET。
	 */
	private static function getMethodFromEnvironment(array $params)
	{
		return isset($params[self::METHOD]) ? $params[self::METHOD] : 'GET';
	}

	/**
	 * クエリ連想配列からRESTパラメータを抽出分解して、
	 * 連想配列化したオブジェクトを生成します。
	 *
	 * 例えば、以下のように分割されます。
	 * 入力: /hoge/fuga/pi,yo
	 * 出力: ['hoge', 'fuga', ['pi', 'yo']]
	 *
	 * @param array $query クエリ連想配列。
	 * @return array REST配列。
	 */
	private static function getRestParamsFromEnvironment(array $query)
	{
		$uri = isset($query[self::URI]) ? $query[self::URI] : '/';
		$trimmed = preg_replace('/(^\/|\/$)/', '', $uri);
		$splited = strlen($trimmed) === 0 ? array() : explode('/', $trimmed);
		return array_map('self::splitMapper', $splited);
	}
	
	/**
	 * 配列からカンマ区切り文字列を探し、分割するためのコールバックです。
	 *
	 * @param string $item 分割対象文字列。
	 * @return mixed 分割された配列。分割不能な場合、引数をそのまま返します。
	 * @SuppressWarnings(PHPMD)
	 */
	private static function splitMapper($item)
	{
		$result = explode(',', $item);
		return count($result) === 1 ? $result[0] : $result;
	}
}
