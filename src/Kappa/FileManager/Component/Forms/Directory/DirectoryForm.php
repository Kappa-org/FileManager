<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component\Forms\Directory;

use Kappa\Application\UI\Form;
use Kappa\FileSystem\Directory;

/**
 * Class DirectoryForm
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryForm extends Form
{
	/** @var DirectoryFormSubmitted */
	private $directoryFormSubmitted;

	/** @var \Kappa\FileSystem\Directory */
	private $directory;

	/**
	 * @param DirectoryFormSubmitted $directoryFormSubmitted
	 */
	public function injectDirectoryFormSubmitted(DirectoryFormSubmitted $directoryFormSubmitted)
	{
		$this->directoryFormSubmitted = $directoryFormSubmitted;
	}

	/**
	 * @param Directory $directory
	 */
	public function __construct(Directory $directory)
	{
		$this->compose();
		$this->directory = $directory;
		parent::__construct();
	}

	private function compose()
	{
		$this->addText('name', 'Název složky:')
			->setRequired('Musíte vyplnit jméno složky');
		$this->addSubmit('submit', 'Vytvořit složku')
			->setAttribute('class', 'btn btn-primary');
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param DirectoryForm $form
	 */
	public function formSubmitted(DirectoryForm $form)
	{
		$this->directoryFormSubmitted->create($form, $this->directory);
	}
}
