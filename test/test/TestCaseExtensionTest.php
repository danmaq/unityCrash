<?php

require_once 'test/TestCaseExtension.php';
class TestCaseExtensionTest extends TestCaseExtension
{
	
	/** Constructor. */
	public function __construct()
	{
		parent::__construct();
		$this->givenTable['givenを実行する'] = array($this, 'throughGiven');
		$this->givenTable['givenを実行する2'] = array($this, 'throughGiven2');
		$this->whenTable['whenを実行する'] = array($this, 'throughWhen');
		$this->whenTable['whenを実行する2'] = array($this, 'throughWhen2');
		$this->thenTable['thenを実行する'] = array($this, 'throughThen');
		$this->thenTable['thenを実行する2'] = array($this, 'throughThen2');
		$this->thenTable['given,when,thenがすべて実行されている'] = array($this, 'allThru');
		$this->thenTable['given,when,thenの2がすべて実行されている'] = array($this, 'allThru2');
	}
	
	/** @scenario 該当するgiven-when-thenを実行する */
	public function shouldRunThrough()
	{
		$this
			->given('givenを実行する', 'hoge', 1)
			->when('whenを実行する', 'fuga', 2)
			->then('thenを実行する', 'piyo', 3)
			->and('given,when,thenがすべて実行されている');
	}
	
	/** @scenario 該当するgiven-when-thenを実行する */
	public function shouldRunThroughAlternative()
	{
		$this
			->given('givenを実行する2', 'foo', 10)
			->when('whenを実行する2', 'bar', 20)
			->then('thenを実行する2', 'baz', 30)
			->and('given,when,thenの2がすべて実行されている');
	}
	
	/** givenを実行する */
	protected function throughGiven(array $world, array $arguments)
	{
		$this->assertNotNull($world, 'ワーク テーブルは null ではない');
		$this->assertNotNull($arguments, '引数は null ではない');
		$this->assertEquals(0, count($world), 'ワーク テーブルは初期状態は空の配列である');
		$this->assertEquals(2, count($arguments), '引数が設定されている');
		$this->assertEquals('hoge', $arguments[0], '引数が設定されている');
		$this->assertEquals(1, $arguments[1], '引数が設定されている');
		$world['given'] = $arguments[0];
		$world[$arguments[0]] = $arguments[1];
		return $world;
	}
	
	/** givenを実行する2 */
	protected function throughGiven2(array $world, array $arguments)
	{
		$this->assertNotNull($world, 'ワーク テーブルは null ではない');
		$this->assertNotNull($arguments, '引数は null ではない');
		$this->assertEquals(0, count($world), 'ワーク テーブルは初期状態は空の配列である');
		$this->assertEquals(2, count($arguments), '引数が設定されている');
		$this->assertEquals('foo', $arguments[0], '引数が設定されている');
		$this->assertEquals(10, $arguments[1], '引数が設定されている');
		$world['given'] = $arguments[0];
		$world[$arguments[0]] = $arguments[1];
		return $world;
	}
	
	/** whenを実行する */
	protected function throughWhen(array $world, array $arguments)
	{
		$this->assertEquals(2, count($world), 'ワーク テーブルに要素が増えている');
		$this->assertEquals(2, count($arguments), '引数が設定されている');
		$this->assertEquals('fuga', $arguments[0], '引数が設定されている');
		$this->assertEquals(2, $arguments[1], '引数が設定されている');
		$world['when'] = $arguments[0];
		$world[$arguments[0]] = $arguments[1];
		return $world;
	}
	
	/** whenを実行する2 */
	protected function throughWhen2(array $world, array $arguments)
	{
		$this->assertEquals(2, count($world), 'ワーク テーブルに要素が増えている');
		$this->assertEquals(2, count($arguments), '引数が設定されている');
		$this->assertEquals('bar', $arguments[0], '引数が設定されている');
		$this->assertEquals(20, $arguments[1], '引数が設定されている');
		$world['when'] = $arguments[0];
		$world[$arguments[0]] = $arguments[1];
		return $world;
	}
	
	/** thenを実行する */
	protected function throughThen(array $world, array $arguments)
	{
		$this->assertEquals(4, count($world), 'ワーク テーブルに要素が増えている');
		$this->assertEquals(2, count($arguments), '引数が設定されている');
		$this->assertEquals('piyo', $arguments[0], '引数が設定されている');
		$this->assertEquals(3, $arguments[1], '引数が設定されている');
		$world['then'] = $arguments[0];
		$world[$arguments[0]] = $arguments[1];
		return $world;
	}
	
	/** thenを実行する2 */
	protected function throughThen2(array $world, array $arguments)
	{
		$this->assertEquals(4, count($world), 'ワーク テーブルに要素が増えている');
		$this->assertEquals(2, count($arguments), '引数が設定されている');
		$this->assertEquals('baz', $arguments[0], '引数が設定されている');
		$this->assertEquals(30, $arguments[1], '引数が設定されている');
		$world['then'] = $arguments[0];
		$world[$arguments[0]] = $arguments[1];
		return $world;
	}

	/** given,when,thenがすべて実行されている */
	protected function allThru(array $world, array $arguments)
	{
		$this->assertEquals(6, count($world), 'ワーク テーブルに要素が増えている');
		$this->assertEquals(0, count($arguments), '引数が設定されている');
		$this->assertTrue(isset($world['given']), 'Givenが実行されている');
		$this->assertTrue(isset($world[$world['given']]), 'Givenが実行されている');
		$this->assertEquals('hoge', $world['given'], 'Givenが実行されている');
		$this->assertEquals(1, $world[$world['given']], 'Givenが実行されている');
		$this->assertTrue(isset($world['when']), 'Whenが実行されている');
		$this->assertTrue(isset($world[$world['when']]), 'Whenが実行されている');
		$this->assertEquals('fuga', $world['when'], 'Whenが実行されている');
		$this->assertEquals(2, $world[$world['when']], 'Whenが実行されている');
		$this->assertTrue(isset($world['then']), 'Thenが実行されている');
		$this->assertTrue(isset($world[$world['then']]), 'Thenが実行されている');
		$this->assertEquals('piyo', $world['then'], 'Thenが実行されている');
		$this->assertEquals(3, $world[$world['then']], 'Thenが実行されている');
		return $world;
	}

	/** given,when,thenの2がすべて実行されている */
	protected function allThru2(array $world, array $arguments)
	{
		$this->assertEquals(6, count($world), 'ワーク テーブルに要素が増えている');
		$this->assertEquals(0, count($arguments), '引数が設定されている');
		$this->assertTrue(isset($world['given']), 'Givenが実行されている');
		$this->assertTrue(isset($world[$world['given']]), 'Givenが実行されている');
		$this->assertEquals('foo', $world['given'], 'Givenが実行されている');
		$this->assertEquals(10, $world[$world['given']], 'Givenが実行されている');
		$this->assertTrue(isset($world['when']), 'Whenが実行されている');
		$this->assertTrue(isset($world[$world['when']]), 'Whenが実行されている');
		$this->assertEquals('bar', $world['when'], 'Whenが実行されている');
		$this->assertEquals(20, $world[$world['when']], 'Whenが実行されている');
		$this->assertTrue(isset($world['then']), 'Thenが実行されている');
		$this->assertTrue(isset($world[$world['then']]), 'Thenが実行されている');
		$this->assertEquals('baz', $world['then'], 'Thenが実行されている');
		$this->assertEquals(30, $world[$world['then']], 'Thenが実行されている');
		return $world;
	}
}
