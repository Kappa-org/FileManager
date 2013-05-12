<?php
/**
 * SelectImage.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 12.5.13
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager\Forms\Controls;

use Nette\Forms\Controls\TextBase;

/**
 * Class SelectImage
 *
 * @package Kappa\FileManager\Forms\Controls
 */
class SelectImage extends TextBase
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
