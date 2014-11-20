<?php

namespace UnityCrash\State;

use SplObjectStorage;

class Context implements iContext
{
	/** �ėp�I�Ɏg�p�ł���l�̃X�g���[�W�B */
	private $storage = array();
	
	/** �O��L����������ԁB */
	private $previousState;
	
	/** ���݂̏�ԁB */
	private $currentState;
	
	/** ���ɑJ�ڂ��ׂ���ԁB */
	private $nextState;

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
	 * @return iState ��ԁB
	 */
	public function getPreviousState()
	{
	}

	/**
	 * ���݂̏�Ԃ��擾���܂��B
	 *
	 * @return iState ��ԁB
	 */
	public function getCurrentState()
	{
	}

	/**
	 * ���ɑJ�ڂ��ׂ���Ԃ��擾���܂��B
	 *
	 * @return iState ��ԁB
	 */
	public function getNextState()
	{
	}

	/**
	 * ���ɑJ�ڂ��ׂ���Ԃ�ݒ肵�܂��B
	 *
	 * @param iState $state ��ԁB
	 */
	public function setNextState(iState $state = null)
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

	/**
	 * �R���e�L�X�g�ɂ��̏�Ԃ��K�p���ꂽ����ɌĂяo����܂��B
	 *
	 * @param object $context �R���e�L�X�g�B
	 */
	public function setup(Context $context)
	{
	}

	/**
	 * �R���e�L�X�g���ʂ̏�ԂւƑJ�ڂ���钼�O�ɌĂяo����܂��B
	 *
	 * @param object $context �R���e�L�X�g�B
	 */
	public function teardown(Context $context)
	{
	}
}