<?php

namespace UnityCrash\MyState;

use UnityCrash\Data\Constants;
use UnityCrash\Data\Environment;
use UnityCrash\State\IContext;
use UnityCrash\State\IState;
use UnityCrash\Utils\Singleton;

/**
 * 文章を通知する場合に ViewState に先立って遷移する状態。
 *
 * @package UnityCrash\MyState
 * @author Mc at danmaq
 */
final class MessageState extends Singleton implements IState
{

	/** 既定のステータスID。 */
	const DEFAULT_STATUS = 0;

	/** メッセージIDに対応したメッセージ。 */
	private static $_messages =
		array(
			'0' => array(false, ''),
			'200' => array(false, 'Completed!'),
			'401' => array(true, 'Auth Failed.'),
			'403' => array(true, 'Forbidden.'),
			'404' => array(true, 'Not Found.'),
			'500' => array(true, 'Internal Server Error.'),
			'900' => array(true, 'Unknown Error.'));

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
		$storage =& $context->getStorage();
		$messageId = self::getMessageId($storage);
		$message = self::$_messages[$messageId];
		$storage[Constants::DATA_MESSAGE_ID] = $messageId;
		$storage[Constants::DATA_MESSAGE_ERROR] = $message[0];
		$storage[Constants::DATA_MESSAGE_BODY] = $message[1];
		$context->setNextState(ViewState::getInstance());
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
	 * メッセージIDを取得します。
	 *
	 * @param IContext $storage コンテキストに備えられているワーク領域。
	 * @return integer メッセージID。
	 */
	private static function getMessageId(array $storage = null)
	{
		$messageId = self::innerGetMessageId($storage);
		$exist = isset(self::$_messages[$messageId]);
		return $exist ? $messageId : self::DEFAULT_STATUS;
	}

	/**
	 * メッセージIDを取得します。
	 *
	 * @param IContext $storage コンテキストに備えられているワーク領域。
	 * @return integer メッセージID。
	 */
	private static function innerGetMessageId(array $storage = null)
	{
		if (!is_null($storage) && isset($storage[Constants::DATA_MESSAGE_ID]))
		{
			return $storage[Constants::DATA_MESSAGE_ID];
		}
		$rest = Environment::getInstance()->getRestParams();
		if (count($rest) >= 2 && $rest[0] == 'error' && is_numeric($rest[1]))
		{
			return intval($rest[1]);
		}
		return self::DEFAULT_STATUS;
	}
}
