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

use Flame\Forms\FormProcessor;
use Nette\Application\UI\Form;
use Kappa\FileManager\Helpers\FileNameHelper;
use Kappa\FileSystem\Directory;
use Kappa\Utils\Strings;

/**
 * Class DirectoryFormProcessor
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryFormProcessor extends FormProcessor
{
	/** @var \Kappa\FileManager\Helpers\FileNameHelper */
	private $fileNameHelper;

	/**
	 * @param FileNameHelper $fileNameHelper
	 */
	public function __construct(FileNameHelper $fileNameHelper)
	{
		$this->fileNameHelper = $fileNameHelper;
	}

	/**
	 * @param Form $form
	 */
	public function success(Form $form)
	{
		$values = $form->getValues();
		$actualDirectory = Directory::open($values->actualDirectory);
		$newPath = $actualDirectory->getInfo()->getPathname() . DIRECTORY_SEPARATOR . Strings::webalize($values->name);
		$newDirectory = $this->fileNameHelper->getUniqueDirectoryName($newPath);
		Directory::create($newDirectory);
		$form->getParent()->flashMessage("Nová složka '{$values['name']}' byla vytvořena", 'success');
		$form->setDefaults(array());
		$form->getParent()->getPresenter()->redirect('this', array('type' => $values->type));
	}
}