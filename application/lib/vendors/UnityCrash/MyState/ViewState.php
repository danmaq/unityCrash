<?php

namespace UnityCrash\MyState;

use \DOMDocument;
use UnityCrash\Data\Constants;
use UnityCrash\Data\Environment;
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
	 * @param IContext $context コンテキスト。
	 */
	public function phase(IContext $context)
	{
		//$storage =& $context->getStorage();
		
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
	 * XMLを作成します。
	 *
	 * @param string $message ステータス メッセージ。
	 * @param string $query 初期状態の入力文。
	 * @param bool $result 処理に成功したかどうか。
	 * @param bool $dynamic 動的ページかどうか(検索エンジンのインデックスを認めるかどうか)
	 * @param string $language 言語。
	 */
	public function createXml($message, $query, $result, $dynamic, $language = 'en')
	{
		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->appendChild($this->createXslPi($dom));
		// TODO: ここから。 xsd、xml:lang、各種パラメータ
		$body = $dom->appendChild($dom->createElement('body'));
		$messageAttr = $dom->createAttribute('message');
		$messageAttr->value = $message;
		$body->appendChild($messageAttr);
		$result = $dom->saveXML();
		//echo $result;
		return $result;
	}
	
	/**
	 * DOM用XSL読み込み命令を作成します。
	 *
	 * @param DOMDocument $dom DOMオブジェクト。
	 * @return DOMProcessingInstruction XML命令オブジェクト。
	 */
	private function createXslPi(DOMDocument $dom)
	{
		$xslData = 'type="text/xsl" href="skin/default.xsl"';
		return $dom->createProcessingInstruction('xml-stylesheet', $xslData);
	}
}
