<?php

namespace UnityCrash\State;

class Context implements IContext
{
	/** 汎用的に使用できる値のストレージ。 */
	private $_storage = array();

	/** 前回有効だった状態。 */
	private $_previousState;
	
	/** 現在の状態。 */
	private $_currentState;

	/** 次に遷移すべき状態。 */
	private $_nextState = null;

	/**
	 * Constructor.
	 *
	 * @param IState $defaultState 初期の状態。
	 * 省略時はEmptyStateが初期状態となります。
	 */
	public function __construct(IState $defaultState = null)
	{
		$this->_previousState = EmptyState::getInstance();
		$this->_currentState =
			isset($defaultState) ? $defaultState : EmptyState::getInstance();
		$this->getCurrentState()->setup($this);
	}

	/**
	 * 汎用的に使用できる連想配列を取得します。
	 *
	 * @return array 連想配列。
	 */
	public function &getStorage()
	{
		return $this->_storage;
	}

	/**
	 * 前回有効だった状態を取得します。
	 *
	 * @return IState 状態。
	 */
	public function getPreviousState()
	{
		return $this->_previousState;
	}

	/**
	 * 現在の状態を取得します。
	 *
	 * @return IState 状態。
	 */
	public function getCurrentState()
	{
		return $this->_currentState;
	}

	/**
	 * 次に遷移すべき状態を取得します。
	 *
	 * @return IState 状態。
	 */
	public function getNextState()
	{
		return $this->_nextState;
	}

	/**
	 * 次に遷移すべき状態を設定します。
	 *
	 * @param IState $state 状態。
	 */
	public function setNextState(IState $state = null)
	{
		$this->_nextState = $state;
	}

	/**
	 * 状態が終了されたかどうかを取得します。
	 *
	 * @return bool 状態が終了された場合、true。
	 */
	public function isTerminate()
	{
		return
			$this->getCurrentState() == EmptyState::getInstance() &&
			is_null($this->getNextState());
	}

	/**
	 * 状態を実行します。
	 */
	public function phase()
	{
		$this->commitNextState();
		$this->getCurrentState()->phase($this);
	}

	/**
	 * 反復して状態を実行します。状態が終了されたタイミングで制御が戻ります。
	 * 構造上、状態の実装次第では無限ループに陥ることがありますので、
	 * 扱いには十分注意してください。
	 * 
	 * @return 状態が終了するまでに要したフェーズの反復実行回数。
	 */
	public function loop()
	{
		for ($i = 0; !$this->isTerminate(); $i++)
		{
			$this->phase();
		}
		return $i;
	}

	/**
	 * 次に遷移すべき状態が設定されている場合、状態を確定します。
	 */
	public function commitNextState()
	{
		$result = !is_null($this->getNextState());
		if ($result)
		{
			$this->getCurrentState()->teardown($this);
			$this->_previousState = $this->_currentState;
			$this->_currentState = $this->_nextState;
			$this->setnextState();
			$this->getCurrentState()->setup($this);
		}
		return $result;
	}

	/**
	 * 空の状態を設定し、値をリセットして状態を終了します。
	 */
	public function terminate()
	{
		$this->setNextState(EmptyState::getInstance());
		$this->commitNextState();
		$this->_previousState = EmptyState::getInstance();
		$this->_currentState = EmptyState::getInstance();
		$this->_nextState = null;
		$this->_storage = array();
	}
}
