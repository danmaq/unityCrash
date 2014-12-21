<?php

namespace UnityCrash\MyState;

use \DOMDocument;
use \XSLTProcessor;
use UnityCrash\Data\Constants;
use UnityCrash\Data\Environment;
use UnityCrash\State\EmptyState;
use UnityCrash\State\IContext;
use UnityCrash\State\IState;
use UnityCrash\Utils\Output;
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
		$storage =& $context->getStorage();
		$dom = $this->createXmlFromStorage($storage);
		//$this->putXml($dom);
		$this->putHtml($dom);
		$context->setNextState(EmptyState::getInstance());
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
	 * XML を表示します。
	 *
	 * @param DOMDocument $dom XML DOMドキュメント。
	 */
	public function putXml(DOMDocument $dom)
	{
		header('Content-Type: text/xml; charset=utf-8');
		header('Pragma: no-cache');
		header('Cache-Control: no-cache');
		echo $dom->saveXML();
	}

	/**
	 * HTML を表示します。
	 *
	 * @param DOMDocument $dom XML DOMドキュメント。
	 */
	public function putHtml(DOMDocument $dom)
	{
		// FIXME: HTMLにすると、Google Adsenseが正しく表示されない
		//header('Content-Type: application/xhtml+xml; charset=utf-8');
		header('Content-Type: text/html; charset=utf-8');
		header('Pragma: no-cache');
		header('Cache-Control: no-cache');
		echo $this->processXslt($dom);
	}

	/**
	 * XML を XSLT に通して、整形します。
	 * 
	 * @param DOMDocument $dom XML DOMドキュメント。
	 * @return string HTML文書。
	 */
	public function processXslt(DOMDocument $dom)
	{
		$cwd = Environment::getCurrentDirectory();
		$xsl = new DOMDocument();
		$xsl->load("{$cwd}/skin/default.xsl");
		$xslt = new XSLTProcessor();
		$xslt->importStylesheet($xsl);
		$xslt->setParameter('', 'sourceLanguage', 'ja');
		$xslt->setParameter('', 'language', 'ja');
		return $xslt->transformToXML($dom);
	}

	/**
	 * ワーク領域に保存されたデータを基にXMLを作成します。
	 *
	 * @param array $storage コンテキストのワーク配列。
	 * @return string XML文書。
	 */
	public function createXmlFromStorage(array $storage)
	{
		$msg = $storage[Constants::DATA_MESSAGE_BODY];
		$query = 'Unity落ちた';
		$result = !$storage[Constants::DATA_MESSAGE_ERROR];
		$dynamic = count(Environment::getRestParams()) > 0;
		$lang = 'ja';
		return $this->createXml($msg, $query, $result, $dynamic, $lang);
	}

	/**
	 * XMLを作成します。
	 *
	 * @param string $message ステータス メッセージ。
	 * @param string $query 初期状態の入力文。
	 * @param bool $result 処理に成功したかどうか。
	 * @param bool $dynamic 動的ページかどうか
	 * (検索エンジンのインデックスを認めるかどうか)
	 * @param string $language 言語。
	 * @return DOMDocument XML DOMドキュメント。
	 */
	public function createXml(
		$message, $query, $result, $dynamic, $language = 'en')
	{
		$attributes =
			array(
				'xml:lang' => $language,
				'root' => Environment::getBasePath(),
				'query' => $query,
				'result' => $result ? 'true' : 'false',
				'message' => $message,
				'dynamic' => $dynamic ? 'true' : 'false');
		return Output::createXml($attributes);
	}
}
