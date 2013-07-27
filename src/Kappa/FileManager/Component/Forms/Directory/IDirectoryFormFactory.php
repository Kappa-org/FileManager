<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component\Forms\Directory;

use Kappa\FileSystem\Directory;

/**
 * Class IDirectoryFormFactory
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
