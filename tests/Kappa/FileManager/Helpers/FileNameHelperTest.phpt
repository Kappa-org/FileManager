<?php
/**
 * This file is part of the Kappa\FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 * 
 * @testCase
 */

namespace Kappa\FileManager\Tests;

use Kappa\FileManager\Helpers\FileNameHelper;
use Kappa\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class FileNameHelperTest
 * @package filemanager\Tests
 */
class FileNameHelperTest extends TestCase
{
	public function testUniqueDirectoryName()
	{
		$fnh = new FileNameHelper();
		Assert::same(__DIR__ . '-1', $fnh->getUniqueDirectoryName(__DIR__));
	}
}

\run(new FileNameHelperTest());