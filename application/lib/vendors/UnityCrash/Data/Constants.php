<?php

namespace UnityCrash\Data;

/**
 * 定数を扱います。
 *
 * @package UnityCrash\Data
 * @author Mc at danmaq
 */
final class Constants
{
	/** 既定の文字エンコード。 */
	const ENCODING = 'UTF-8';

	/** エラーメッセージを含むかどうか。 */
	const DATA_MESSAGE_ERROR = 'messageIsError';

	/** メッセージID。 */
	const DATA_MESSAGE_ID = 'messageId';

	/** メッセージ本体。 */
	const DATA_MESSAGE_BODY = 'messageBody';

	/** エラー表示用 REST エントリ ポイント。 */
	const REST_ERROR = 'error';

	/** ビュー用 REST エントリ ポイント。 */
	const REST_MESSAGE = 'message';

	/** ツイート投稿用 REST エントリ ポイント。 */
	const REST_TWEET = 'tweet';

	/** Constructor. */
	private function __construct()
	{
	}
}
