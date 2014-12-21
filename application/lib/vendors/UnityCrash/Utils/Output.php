<?php

namespace UnityCrash\Utils;

use \DOMDocument;
use \XSLTProcessor;

/**
 * ビューを生成するための状態。
 * MVCモデルにおけるビュー(コントローラ)に該当する状態。
 *
 * @package UnityCrash\MyState
 * @author Mc at danmaq
 */
final class Output
{

	/** Constructor. */
	private function __construct()
	{
	}

	/**
	 * XMLを作成します。
	 *
	 * @param array $attributes 属性を示す連想配列。
	 * @return DOMDocument XML DOMドキュメント。
	 */
	public static function createXml(array $attributes)
	{
		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->appendChild(self::createXslPi($dom));
		$body = $dom->appendChild($dom->createElement('body'));
		$body->appendChild(self::createXsdReference($dom));
		$map = function ($node) use ($body) { $body->appendChild($node); };
		array_map($map, self::createAttributes($dom, $attributes));
		return $dom;
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
	 * XML Schemeへの参照を示す属性を作成します。
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
