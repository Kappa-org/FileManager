<?php
/**
 * IDirectoryFormFactory.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 10.5.13
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager\Forms\Directory;

use Kappa\FileSystem\Directory;

/**
 * Class IDirectoryFormFactory
 *
 * @package Kappa\FileManager\Forms\Directory
 */
interface IDirectoryFormFactory
{
	/**
	 * @param Directory $directory
	 * @return DirectoryForm
	 */
	public function create(Directory $directory);
}
