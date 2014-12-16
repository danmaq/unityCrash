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
	 * @param bool $dynamic 動的ページかどうか
	 * (検索エンジンのインデックスを認めるかどうか)
	 * @param string $language 言語。
	 * @return string XML文書。
	 */
	public function createXml(
		$message, $query, $result, $dynamic, $language = 'en')
	{
		$attributes =
			array(
				'xml:lang' => $language,
				'root' => '.',
				'query' => $query,
				'result' => $result ? 'true' : 'false',
				'message' => $message,
				'dynamic' => $dynamic ? 'true' : 'false');
		return self::innerCreaateXml($attributes);
	}
	
	/**
	 * XMLを作成します。
	 *
	 * @param array $attributes 属性を示す連想配列。
	 * @return string XML文書。
	 */
	private static function innerCreaateXml(array $attributes)
	{
		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->appendChild(self::createXslPi($dom));
		$body = $dom->appendChild($dom->createElement('body'));
		$body->appendChild(self::createXsdReference($dom));
		$map = function ($node) use ($body) { $body->appendChild($node); };
		array_map($map, self::createAttributes($dom, $attributes));
		return $dom->saveXML();
	}
	
	/**
	 * XML Schemeへの参照を作成します。
	 *
	 * @param DOMDocument $dom DOMオブジェクト。
	 * @return DOMAttr XML Schemeへの参照オブジェクト。
	 */
	private static function createXsdReference(DOMDocument $dom)
	{
		$uri = 'http://www.w3.org/2001/XMLSchema-instance';
		$name = 'xsi:noNamespaceSchemaLocation';
		$attr = $dom->createAttributeNS($uri, $name);
		$attr->value = 'skin/default.xsd';
		return $attr;
	}

	/**
	 * DOM用XSL読み込み命令を作成します。
	 *
	 * @param DOMDocument $dom DOMオブジェクト。
	 * @return DOMProcessingInstruction XML命令オブジェクト。
	 */
	private static function createXslPi(DOMDocument $dom)
	{
		$xslData = 'type="text/xsl" href="skin/default.xsl"';
		return $dom->createProcessingInstruction('xml-stylesheet', $xslData);
	}

	/**
	 * 属性一覧を作成します。
	 *
	 * @param DOMDocument $dom DOMオブジェクト。
	 * @return array 属性一覧。
	 */
	private static function createAttributes(DOMDocument $dom, array $array)
	{
		$map =
			function ($key, $value) use ($dom)
			{
				$attr = $dom->createAttribute($key);
				$attr->value = $value;
				return $attr;
			};
		return array_map($map, array_keys($array), array_values($array));
	}
}
