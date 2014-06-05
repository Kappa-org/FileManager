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

use Flame\Forms\IFormFactory;
use Kappa\FileSystem\Directory;
use Nette\Object;

/**
 * Class DirectoryFormFactory
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryFormFactory extends Object
{
	/** @var \Flame\Forms\IFormFactory */
	private $formFactory;

	/**
	 * @param IFormFactory $formFactory
	 * @param DirectoryFormProcessor $processor
	 */
	public function __construct(IFormFactory $formFactory, DirectoryFormProcessor $processor)
	{
		$this->formFactory = $formFactory;
		$this->formFactory->addProcessor($processor);
	}

	/**
	 * @param Directory $actualDirectory
	 * @param string $type
	 * @return \Nette\Application\UI\Form
	 */
	public function createForm(Directory $actualDirectory, $type)
	{
		$form = $this->formFactory->createForm();
		$form->addHidden('actualDirectory', $actualDirectory->getInfo()->getPathname());
		$form->addText('name', 'Název složky:');
		$form->addSubmit('send', 'Vytvořit složku');
		$form->addHidden('type', $type);

		return $form;
	}
}