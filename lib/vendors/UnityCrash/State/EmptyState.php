<?php

namespace UnityCrash\State;

/**
 * State�p�^�[���ɂ�����I���̏�Ԃ̎����ł��B
 * �R���e�L�X�g�����̏�ԂɎ������ꍇ�A�O�������ԑ���������Ȃ����蓮�삵�܂���B
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
final class EmptyState implements iState
{

	/**
	 * �R���e�L�X�g�ɂ��̏�Ԃ��K�p���ꂽ����ɌĂяo����܂��B
	 *
	 * @param object $context �R���e�L�X�g�B
	 */
	public function setup($context)
	{
	}

	/**
	 * �R���e�L�X�g�ɏ�Ԃ��K�p����Ă���ԁA�������ČĂяo����܂��B
	 *
	 * @param object $context �R���e�L�X�g�B
	 */
	public function loop($context)
	{
	}

	/**
	 * �R���e�L�X�g���ʂ̏�ԂւƑJ�ڂ���钼�O�ɌĂяo����܂��B
	 *
	 * @param object $context �R���e�L�X�g�B
	 */
	public function teardown($context)
	{
	}
}
