<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

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
	
	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
	}

	/** @scenario インスタンスを取得できる */
	public function shouldGetInstance()
	{
		$this
			->given('インスタンスを取得する')
			->then('指定したインスタンスが取得できる', 'UnityCrash\MyState\ViewState')
			->and('指定したインスタンスが取得できる', 'UnityCrash\State\IState');
	}

	/** @scenario 基本形のXMLを生成できる */
	public function shouldCreateSimpleXML()
	{
		$expect =
			array(
				'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
				'xsi:noNamespaceSchemaLocation' => 'default.xsd',
				'xml:lang' => 'ja',
				'query' => 'Unity落ちた',
				'result' => 'true',
				'message' => '',
				'dynamic' => 'false');
		$this
			->given('インスタンスを取得する')
			->when('XML生成を実行する', array())
			->then('生成されたXMLが正しい', $expect);
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
}
