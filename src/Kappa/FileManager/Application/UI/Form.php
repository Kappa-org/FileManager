<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Application\UI;

use Kappa\Application\UI\Form as BaseForm;
use Kappa\FileManager\Forms\Controls\SelectDirectories;
use Kappa\FileManager\Forms\Controls\SelectFiles;

/**
 * Class Form
 * @package Kappa\FileManager\Application\UI
 */
class Form extends BaseForm
{
	/**
	 * @param string $name
	 * @param string|null $label
	 * @return SelectFiles
	 */
	public function addSelectFiles($name, $label = null)
	{
		$control = new SelectFiles($label);

		return $this[$name] = $control;
	}

	/**
	 * @param string $name
	 * @param string|null $label
	 * @return SelectDirectories
	 */
	public function addSelectDirectories($name, $label = null)
	{
		$control = new SelectDirectories($label);

		return $this[$name] = $control;
	}
}