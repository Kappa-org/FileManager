<?php
/**
 * FileFormSubmitted.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 10.5.13
 *
 * @package Kappa\FileManager
 */
 
namespace Kappa\FileManager\Forms\File;

use Kappa\FileSystem\Directory;
use Kappa\FileSystem\FileUpload;
use Kappa\FileSystem\Image;
use Nette\Object;

/**
 * Class FileFormSubmitted
 *
 * @package Kappa\FileManager\Forms\File
 */
class FileFormSubmitted extends Object
{
	/**
	 * @param FileForm $form
	 * @param Directory $directory
	 * @param array $params
	 */
	public function create(FileForm $form, Directory $directory, array $params)
	{
		$values = $form->values;
		$presenter = $form->presenter;
		foreach ($values['files'] as $file) {
			$newFile = $directory->getInfo()->getPathname() . DIRECTORY_SEPARATOR . $file->getName();
			if($file->isImage()) {
				new Image($file->getTemporaryFile(), $newFile, array($params['maxWidth'], $params['maxHeight']), "shrink_only");
			} else {
				if ($file->size <= $params['maxFileSize'])
					new FileUpload($file, $this->createUniqueName($newFile));
			}
		}
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
		if (file_exists($path)) {
			$i++;
			$type = strrchr($path, ".");
			$newFile = str_replace($type, "-" . $i . $type, $path);
			while (file_exists($newFile)) {
				$i++;
				$newFile = str_replace($type, "-" . $i . $type, $path);
			}
			return $newFile;
		} else
			return $path;
	}
}