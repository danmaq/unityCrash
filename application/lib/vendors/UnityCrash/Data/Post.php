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

	/** 置換される文字列の文字列長。 */
	private static $_replaceLength;

	/**
	 * Constructor. 
	 *
	 * @param string $message メッセージ。
	 */
	public function __construct($message = null)
	{
		self::$_replaceLength = mb_strlen(self::REPLACE, Constants::ENCODING);
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
		$previous = $this->getMessage();
		$newbie = mb_strlen($previous, Constants::ENCODING) === 0;
		if ($force and !$newbie)
		{
			$insert = self::omit($message);
			$amount = self::LIMIT - mb_strlen($insert, Constants::ENCODING);
			if ($amount >= self::$_replaceLength)
			{
				$omittedPrev = self::omit($previous, $amount);
				$this->_message = "{$previous} {$insert}";
			}
			else
			{
				$this->_message = $insert;
			}
		}
		else
		{
			$combined = "{$previous} {$message}";
			$this->_message = self::omit($newbie ? $message : $combined);
		}
	}

	/**
	 * メッセージを短縮します。
	 * ソースが null である場合、空文字となります。
	 * ソースが文字数制限以下の場合、そのまま取得します。
	 * ソースが文字数制限を超える場合、短縮記号を含み文字列を短縮します。
	 *
	 * @param string $message ソースとなるメッセージ。
	 * @param integer $limit 文字数制限。
	 * @return string メッセージ。
	 * @throws \InvalidArgumentException 文字数制限が短縮記号よりも小さい場合。
	 * 具体的には、3未満である場合。
	 */
	public static function omit($message, $limit = self::LIMIT)
	{
		$max = $limit - self::$_replaceLength;
		if ($max < 0)
		{
			throw new InvalidArgumentException('Character limit is too short.');
		}
		$msg = isset($message) ? $message : '';
		$omit = mb_strlen($msg, Constants::ENCODING) > $limit;
		return $omit ? mb_substr($msg, 0, $max, Constants::ENCODING) . self::REPLACE: $msg;
	}
}
