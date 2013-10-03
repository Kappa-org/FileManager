<?php
/**
 * This file is part of the Kappa\FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Forms\Directory;

use Kappa\FileSystem\Directory;
use Kappa\Forms\ITemplateFormFactory;
use Nette\Object;

/**
 * Class DirectoryFormFactory
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryFormFactory extends Object
{
	/** @var \Kappa\Forms\TemplateFormFactory */
	private $formFactory;

	/**
	 * @param ITemplateFormFactory $formFactory
	 * @param DirectoryFormProcessor $processor
	 */
	public function __construct(ITemplateFormFactory $formFactory, DirectoryFormProcessor $processor)
	{
		$this->formFactory = $formFactory->setProcessor($processor)
			->setTemplate(__DIR__ . '/../../templates/forms/directoryForm.latte');
	}

	/**
	 * @param Directory $actualDirectory
	 * @return \Kappa\Application\UI\TemplateForm
	 */
	public function createForm(Directory $actualDirectory)
	{
		$form = $this->formFactory->createForm();
		$form->setData('actualDirectory', $actualDirectory);
		$form->addText('name', 'Název složky:');
		$form->addSubmit('send', 'Vytvořit složku');

		return $form;
	}
}