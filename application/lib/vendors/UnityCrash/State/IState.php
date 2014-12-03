<?php

namespace UnityCrash\State;

/**
 * State�p�^�[���ɂ������Ԃ̒�`�ł��B
 * ���̃C���^�[�t�F�C�X���������āA���W�b�N�������L�q���܂��B
 *
 * @package UnityCrash\State
 * @author Mc at danmaq
 */
interface IState
{

	/**
	 * �R���e�L�X�g�ɂ��̏�Ԃ��K�p���ꂽ����ɌĂяo����܂��B
	 *
	 * @param iContext $context �R���e�L�X�g�B
	 */
	public function setup(iContext $context);

	/**
	 * �R���e�L�X�g�ɏ�Ԃ��K�p����Ă���ԁA�������ČĂяo����܂��B
	 *
	 * @param iContext $context �R���e�L�X�g�B
	 */
	public function loop(iContext $context);

	/**
	 * �R���e�L�X�g���ʂ̏�ԂւƑJ�ڂ���钼�O�ɌĂяo����܂��B
	 *
	 * @param iContext $context �R���e�L�X�g�B
	 */
	public function teardown(iContext $context);
}
