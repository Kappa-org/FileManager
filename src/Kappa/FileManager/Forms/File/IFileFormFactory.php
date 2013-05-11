<?php
/**
 * IFileFormFactory.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 10.5.13
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager\Forms\File;

use Kappa\FileSystem\Directory;

/**
 * Class IFileFormFactory
 *
 * @package Kappa\FileManager\Forms\File
 */
interface IFileFormFactory
{
	/**
	 * @param Directory $directory
	 * @param array $params
	 * @return FileForm
	 */
	public function create(Directory $directory, array $params);
}
