<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use \DOMDocument;
use UnityCrash\Data\Environment;
use UnityCrash\MyState\ViewState;
use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class ViewStateTest extends TestCaseExtension
{

	/** コンテキスト オブジェクトに紐づけられたキー。 */
	const CONTEXT = 'context';

	/** インスタンスと紐づけられるキー。 */
	const INSTANCE = 'instance';

	/** XMLに紐づけられたキー。 */
	const XML = 'xml';

	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable['インスタンスを取得する'] = array($this, 'getInstance');
		$this->whenTable['XML生成を実行する'] = array($this, 'createXml');
		$this->thenTable['指定したインスタンスが取得できる'] = array($this, 'validateInstance');
		$this->thenTable['生成されたXMLが正しい'] = array($this, 'validateXml');
		$this->thenTable['生成されたXMLが正しい(名前空間付き検証)'] = array($this, 'validateXmlWithNS');
	}

	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'UnityCrash\MyState\ViewState')
			->and('指定したインスタンスが取得できる', 'UnityCrash\State\IState');
	}

	/** @scenario 全オプション指定のXMLを生成できる */
	public function shouldCreateFullXML()
	{
		$phpunitPath = pathinfo(`which phpunit`);
		$opt =
			array(
				'xml:lang' => 'en',
				'query' => 'foo',
				'result' => false,
				'message' => 'qux',
				'dynamic' => true);
		$expectNS =
			array(
				 array(
					'http://www.w3.org/2001/XMLSchema-instance', 'noNamespaceSchemaLocation', 'skin/default.xsd'));
		$expect =
			array(
				'xml:lang' => 'en',
				'root' => $phpunitPath['dirname'],
				'query' => 'foo',
				'result' => 'false',
				'message' => 'qux',
				'dynamic' => 'true');
		$this
			->given('インスタンスを取得する')
			->when('XML生成を実行する', $opt)
			->then('生成されたXMLが正しい', $expect)
			->and('生成されたXMLが正しい(名前空間付き検証)', $expectNS);
	}

	/** インスタンスを取得する */
	protected function getInstance(array & $world, array $arguments)
	{
		$world[self::INSTANCE] = ViewState::getInstance();
	}

	/** XML生成を実行する */
	protected function createXml(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$options = $arguments[0];
		$world[self::XML] = $world[self::INSTANCE]->createXml(
			$options['message'],
			$options['query'],
			$options['result'],
			$options['dynamic'],
			$options['xml:lang']);
	}

	/** 指定したインスタンスが取得できる */
	protected function validateInstance(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertInstanceOf(
			$arguments[0], $world[self::INSTANCE], 'インスタンスが指定したクラスかそのサブクラスである');
	}

	/** 生成されたXMLが正しい */
	protected function validateXml(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$attrs = $this->validateXmlRootAndGetAttricutes($world, $arguments);
		$this->assertGreaterThanOrEqual(count($arguments[0]), $attrs->length, '最低限の要素数がある');
		foreach ($arguments[0] as $key => $value)
		{
			$attr = $attrs->getNamedItem($key);
			$this->assertNotNull($attr, $key . '属性は生成されている');
			$this->assertEquals($value, $attr->nodeValue, $key . '属性の値は生成されている');
		}
	}

	/** 生成されたXMLが正しい(名前空間付き検証) */
	protected function validateXmlWithNS(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$attrs = $this->validateXmlRootAndGetAttricutes($world, $arguments);
		$this->assertGreaterThanOrEqual(count($arguments[0]), $attrs->length, '最低限の要素数がある');
		foreach ($arguments[0] as $params)
		{
			$attr = $attrs->getNamedItemNS($params[0], $params[1]);
			$this->assertNotNull($attr, $params[1] . '属性は生成されている');
			$this->assertEquals($params[2], $attr->nodeValue, $params[1] . '属性の値は生成されている');
		}
	}

	/** [共通]ルート要素を検証し、属性一覧を取得する */
	private function validateXmlRootAndGetAttricutes(array & $world, array $arguments)
	{
		$this->assertEquals(1, count($arguments), '引数は 1 つ必要');
		$this->assertTrue(isset($world[self::XML]), 'XML自体は生成されている');
		$dom = $world[self::XML];
		$nodes = $dom->getElementsByTagName('body');
		$this->assertEquals(1, $nodes->length, 'ルート要素は生成されている');
		return $nodes->item(0)->attributes;
	}
}
