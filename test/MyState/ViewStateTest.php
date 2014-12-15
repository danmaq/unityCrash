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
		$opt =
			array(
				'xml:lang' => 'en',
				'query' => 'foo',
				'result' => false,
				'message' => 'qux',
				'dynamic' => true);
		$expect =
			array(
				'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
				'xsi:noNamespaceSchemaLocation' => 'default.xsd',
				'xml:lang' => 'en',
				'query' => 'foo',
				'result' => 'false',
				'message' => 'qux',
				'dynamic' => 'true');
		$this
			->given('インスタンスを取得する')
			->when('XML生成を実行する', $opt)
			->then('生成されたXMLが正しい', $expect);
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
		$this->assertTrue(isset($world[self::XML]), '文字列自体は生成されている');
		$dom = new DOMDocument();
		$this->assertTrue($dom->loadXML($world[self::XML]), 'XML自体は生成されている');
		
		// TODO: テストコードここから
		$this->fail('"生成されたXMLが正しい" is not implemented.');
	}
}
