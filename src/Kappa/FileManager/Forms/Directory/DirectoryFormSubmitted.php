<?php
/**
 * DirectoryFormSubmitted.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 10.5.13
 *
 * @package Kappa\FileManager
 */
 
namespace Kappa\FileManager\Forms\Directory;

use Kappa\FileSystem\Directory;
use Kappa\Utils\FileSystem\Directories;
use Nette\Object;

/**
 * Class DirectoryFormSubmitted
 *
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryFormSubmitted extends Object
{
	/**
	 * @param DirectoryForm $form
	 * @param Directory $directory
	 */
	public function create(DirectoryForm $form, Directory $directory)
	{
		$values = $form->getValues();
		$presenter = $form->presenter;
		$path = $directory->getInfo()->getPathname();
		$path .= DIRECTORY_SEPARATOR . $values['name'];
		$path = $this->createUniqueName($path);
		new Directory($path);
		if($presenter->isAjax()) {
			$form->restore();
			$presenter->invalidateControl('Kappa-fileManager');
		} else {
			$presenter->redirect('this');
		}
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function createUniqueName($path)
	{
		$i = 0;
		if (is_dir($path)) {
			$i++;
			while (is_dir($path . '-' . $i)) {
				$i++;
			}
			return $path . '-' . $i;
		} else
			return $path;
	}
}