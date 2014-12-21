<?php

require_once 'test/TestCaseExtension.php';
require_once 'application/SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'application/lib/vendors');
$loader->register();

use UnityCrash\Data\Environment;
use UnityCrash\MyState\TweetState;
use UnityCrash\State\Context;
use UnityCrash\State\EmptyState;

class TweetStateTest extends TestCaseExtension
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
			->then('指定したインスタンスが取得できる', 'UnityCrash\MyState\TweetState')
			->and('指定したインスタンスが取得できる', 'UnityCrash\State\IState');
	}
}
