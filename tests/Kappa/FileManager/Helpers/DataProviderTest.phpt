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

use Kappa\FileManager\Helpers\DataProvider;
use Kappa\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class DataProviderTest
 * @package filemanager\Tests
 */
class DataProviderTest extends TestCase
{
	public function testSetAndGet()
	{
		$dp = new DataProvider();
		Assert::null($dp->setTestData('TestString'));
		Assert::same('TestString', $dp->getTestData());
	}

	public function testGetJson()
	{
		$dp = new DataProvider();
		Assert::null($dp->setTestData('Test string'));
		Assert::same('Test string', $dp->getTestData());
		Assert::same(json_encode(array('testData' => 'Test string')), $dp->getJson());
	}
}


\run(new DataProviderTest());