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

use Kappa\Application\UI\Form;
use Kappa\FileManager\FileNameHelper;
use Kappa\FileSystem\Directory;
use Kappa\Forms\FormProcessor;
use Kappa\Utils\Strings;

/**
 * Class DirectoryFormProcessor
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryFormProcessor extends FormProcessor
{
	/** @var \Kappa\FileManager\FileNameHelper */
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
		/** @var \Kappa\FileSystem\Directory $actualDirectory */
		$actualDirectory = $form->getData('actualDirectory');
		$values = $form->getValues();
		$newPath = $actualDirectory->getPath() . DIRECTORY_SEPARATOR . Strings::webalize($values['name']);
		$newDirectory = $this->fileNameHelper->getUniqueDirectoryName($newPath);
		try {
			new Directory($newDirectory);
			$form->getParent()->flashMessage("Nová složka '{$values['name']}' byla vytvořena", 'success');
		} catch (\Exception $e) {
			$form->getParent()->flashMessage("Složku '{$values['name']}' se nepodařilo vytořit", 'error');
		}
		$form->restore();
		if ($form->getParent()->getPresenter()->isAjax()) {
			$form->getParent()->invalidateControl('Kappa-fileManager');
		} else {
			$form->getParent()->redirect('this');
		}
	}
}