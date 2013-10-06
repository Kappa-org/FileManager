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

use Kappa\FileManager\Forms\Directory\DirectoryFormFactory;
use Kappa\FileManager\Forms\Directory\DirectoryFormProcessor;
use Kappa\FileManager\Helpers\FileNameHelper;
use Kappa\FileSystem\Directory;
use Kappa\Forms\TemplateFormFactory;
use Kappa\Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class DirectoryFormFactoryTest
 * @package Kappa\FileManager\Tests
 */
class DirectoryFormFactoryTest extends TestCase
{
	/** @var \Kappa\FileManager\Forms\Directory\DirectoryFormFactory */
	private $directoryFormFactory;

	protected function setUp()
	{
		$templateFormFactory = new TemplateFormFactory();
		$fileNameHelper = new FileNameHelper();
		$directoryFormProcessor = new DirectoryFormProcessor($fileNameHelper);
		$this->directoryFormFactory = new DirectoryFormFactory($templateFormFactory, $directoryFormProcessor);
	}

	public function testCreate()
	{
		$directory = new Directory(__DIR__, Directory::LOAD);
		Assert::type('\Kappa\Application\UI\Form', $this->directoryFormFactory->createForm($directory));
	}
}

\run(new DirectoryFormFactoryTest());