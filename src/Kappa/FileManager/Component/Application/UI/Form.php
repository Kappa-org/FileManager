<?php
/**
 * This file is part of the filemanager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component\Application\UI;

use Kappa\Application\UI\Form as BaseForm;
use Nette\ComponentModel\IContainer;

/**
 * Class Form
 * @package Kappa\FileManager\Application\UI
 */
class Form extends BaseForm
{
	/**
	 * @param IContainer $parent
	 * @param string|null $name
	 */
	public function __construct(IContainer $parent = null, $name = null)
	{
		parent::__construct($parent, $name);
		$this->addExtension('addSelectDirectory', '\Kappa\FileManager\Component\Forms\Controls\SelectDirectory');
		$this->addExtension('addSelectFile', '\Kappa\FileManager\Component\Forms\Controls\SelectFile');
	}
}