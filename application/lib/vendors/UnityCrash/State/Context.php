<?php

namespace UnityCrash\State;

use SplObjectStorage;

class Context implements IContext
{
//	/** �ėp�I�Ɏg�p�ł���l�̃X�g���[�W�B */
//	private $_storage = array();
//	
//	/** �O��L����������ԁB */
//	private $_previousState;
//	
//	/** ���݂̏�ԁB */
//	private $_currentState;
//	
//	/** ���ɑJ�ڂ��ׂ���ԁB */
//	private $_nextState;

	/**
	 * Constructor.
	 *
	 * @param IState $defaultState �����̏�ԁB
	 * �ȗ�����EmptyState��������ԂƂȂ�܂��B
	 */
	public function __construct(IState $defaultState = null)
	{
		
	}

	/**
	 * �ėp�I�Ɏg�p�ł���A�z�z����擾���܂��B
	 *
	 * @return array �A�z�z��B
	 */
	public function getStorage()
	{
	}

	/**
	 * �O��L����������Ԃ��擾���܂��B
	 *
	 * @return IState ��ԁB
	 */
	public function getPreviousState()
	{
	}

	/**
	 * ���݂̏�Ԃ��擾���܂��B
	 *
	 * @return IState ��ԁB
	 */
	public function getCurrentState()
	{
	}

	/**
	 * ���ɑJ�ڂ��ׂ���Ԃ��擾���܂��B
	 *
	 * @return IState ��ԁB
	 */
	public function getNextState()
	{
	}

	/**
	 * ���ɑJ�ڂ��ׂ���Ԃ�ݒ肵�܂��B
	 *
	 * @param IState $state ��ԁB
	 */
	public function setNextState(IState $state = null)
	{
	}

	/**
	 * ��Ԃ��I�����ꂽ���ǂ������擾���܂��B
	 *
	 * @return bool ��Ԃ��I�����ꂽ�ꍇ�Atrue�B
	 */
	public function isTerminate()
	{
	}

	/**
	 * �R���e�L�X�g�ɏ�Ԃ��K�p����Ă���ԁA�������ČĂяo����܂��B
	 *
	 * @param object $context �R���e�L�X�g�B
	 */
	public function loop()
	{
	}

	/**
	 * ���ɑJ�ڂ��ׂ���Ԃ��ݒ肳��Ă���ꍇ�A��Ԃ��m�肵�܂��B
	 */
	public function commitState()
	{
	}

	/**
	 * ��̏�Ԃ�ݒ肵�A�l�����Z�b�g���ď�Ԃ��I�����܂��B
	 */
	public function terminate()
	{
	}
}
