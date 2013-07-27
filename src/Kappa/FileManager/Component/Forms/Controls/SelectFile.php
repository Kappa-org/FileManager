<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component\Forms\Controls;

use Nette\Forms\Controls\TextBase;

/**
 * Class SelectFile
 * @package Kappa\FileManager\Forms\Controls
 */
class SelectFile extends TextBase
{
	/** @var string */
	private $_value;

	/**
	 * @param null $label
	 */
	public function __construct($label = null)
	{
		parent::__construct($label);
		$this->control->type = "text";
	}

	/**
	 * @param $value
	 * @return $this|\Nette\Forms\Controls\BaseControl
	 */
	public function setDefaultValue($value)
	{
		$form = $this->getForm(false);
		if (!$form || !$form->isAnchored() || !$form->isSubmitted()) {
			$this->_value = $value;
		}
		return $this;
	}

	/**
	 * @return \Nette\Utils\Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		$control->addAttributes(array('data-kappa-form' => 'Kappa-SelectImage'));
		$control->addValue($this->_value);
		return $control;
	}
}
