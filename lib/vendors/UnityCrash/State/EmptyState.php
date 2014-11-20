<?php

namespace UnityCrash\State;

use UnityCrash\Utils\Singleton;

/**
 * State�p�^�[���ɂ�����I���̏�Ԃ̎����ł��B
 * �R���e�L�X�g�����̏�ԂɎ������ꍇ�A�O�������ԑ���������Ȃ����蓮�삵�܂���B
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
final class EmptyState extends Singleton implements iState
{

	/**
	 * �R���e�L�X�g�ɂ��̏�Ԃ��K�p���ꂽ����ɌĂяo����܂��B
	 *
	 * @param iContext $context �R���e�L�X�g�B
	 */
	public function setup(iContext $context)
	{
	}

	/**
	 * �R���e�L�X�g�ɏ�Ԃ��K�p����Ă���ԁA�������ČĂяo����܂��B
	 *
	 * @param iContext $context �R���e�L�X�g�B
	 */
	public function loop(iContext $context)
	{
	}

	/**
	 * �R���e�L�X�g���ʂ̏�ԂւƑJ�ڂ���钼�O�ɌĂяo����܂��B
	 *
	 * @param iContext $context �R���e�L�X�g�B
	 */
	public function teardown(iContext $context)
	{
	}
}
