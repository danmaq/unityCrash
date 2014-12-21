<?php

namespace UnityCrash\Data;

use \InvalidArgumentException;

/**
 * 投稿メッセージ。
 * 140文字を超える分はカットされます。
 *
 * @package UnityCrash\Data
 * @author Mc at danmaq
 */
final class Post
{
	/** 最大文字数。 */
	const LIMIT = 140;

	/** 置換される文字列。 */
	const REPLACE = '...';

	/** メッセージ。 */
	private $_message;

	/**
	 * Constructor. 
	 *
	 * @param string $message メッセージ。
	 */
	public function __construct($message = null)
	{
		$this->_message = self::omit($message);
	}

	/**
	 * メッセージを取得します。
	 *
	 * @return string メッセージ。
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * メッセージを追加します。
	 * 既に追加されているメッセージとはスペース1文字を置いて連結します。
	 *
	 * @param string $message メッセージ。
	 * @param boolean $force 強制的に追記するかどうか。
	 * 140文字を超える分は、falseの場合今回追加分からカットされますが、
	 * trueの場合は既存の文字列からカットされます。
	 */
	public function addMessage($message, $force = false)
	{
		$noSource = mb_strlen($this->_message, 'UTF-8') == 0;
		$combined = $noSource ? $message : "{$this->_message} {$message}";
		$this->_message = self::omit($combined);
	}

	/**
	 * メッセージを短縮します。
	 *
	 * @param string $message メッセージ。
	 * @param integer $limit 文字数制限。
	 * @return string メッセージ。
	 * @throws \InvalidArgumentException 文字数制限が短縮記号よりも小さい場合。
	 * 具体的には、3未満である場合。
	 */
	public static function omit($message, $limit = self::LIMIT)
	{
		$encode = 'UTF-8';
		$max = $limit - mb_strlen(self::REPLACE, $encode);
		if ($max < 0)
		{
			throw new InvalidArgumentException('Character limit is too short.');
		}
		$msg = isset($message) ? $message : '';
		$omit = mb_strlen($msg, $encode) > $limit;
		return $omit ? mb_substr($msg, 0, $max, $encode) . self::REPLACE: $msg;
	}
}
