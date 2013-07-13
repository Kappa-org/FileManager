<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Forms\File;

use Kappa\FileSystem\Directory;

/**
 * Class IFileFormFactory
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
