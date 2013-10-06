<?php
/**
 * This file is part of the Kappa\FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Helpers;

use Nette\Object;

/**
 * Class FileNameHelper
 * @package Kappa\FileManager
 */
class FileNameHelper extends Object
{
	/**
	 * @param string $path
	 * @return string
	 */
	public function getUniqueDirectoryName($path)
	{
		$i = 0;
		if (is_dir($path)) {
			$i++;
			while (is_dir($path . '-' . $i)) {
				$i++;
			}

			return $path . '-' . $i;
		} else {
			return $path;
		}
	}
}