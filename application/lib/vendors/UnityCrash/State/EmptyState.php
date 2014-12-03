<?php

namespace UnityCrash\State;

use UnityCrash\Utils\Singleton;

/**
 * State�p�^�[���ɂ�����I���̏�Ԃ̎����ł��B�R���e�L�X�g�����̏�Ԃ�
 * �������ꍇ�A�O�������ԑ���������Ȃ�����i���Ƀ_�~�[���[�v�Ɋׂ�܂��B
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
final class EmptyState extends Singleton implements IState
{

	/**
	 * �R���e�L�X�g�ɂ��̏�Ԃ��K�p���ꂽ����ɌĂяo����܂��B
	 *
	 * @param IContext $context �R���e�L�X�g�B
	 */
	public function setup(IContext $context)
	{
	}

	/**
	 * �R���e�L�X�g�ɏ�Ԃ��K�p����Ă���ԁA�������ČĂяo����܂��B
	 *
	 * @param IContext $context �R���e�L�X�g�B
	 */
	public function loop(IContext $context)
	{
	}

	/**
	 * �R���e�L�X�g���ʂ̏�ԂւƑJ�ڂ���钼�O�ɌĂяo����܂��B
	 *
	 * @param IContext $context �R���e�L�X�g�B
	 */
	public function teardown(IContext $context)
	{
	}
}
