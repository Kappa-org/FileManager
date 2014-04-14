<?php
/**
 * This file is part of the Kappa\FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

/**
 * Class FileManagerExtension
 * @package Kappa\FileManager\DI
 */
class FileManagerExtension extends CompilerExtension
{
	/** @var array */
	private $defaultParams = array(
		'uploadDir' => 'uploads',
		'maxWidth' => 940,
		'maxHeight' => 600,
		'maxFileSize' => '32mb',
		'wwwDir' => '%wwwDir%',
		'js' => 'public/js/filemanager.js',
		'css' => 'public/css/filemanager.css',
		'plupload' => 'public/components/plupload',
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultParams);
		$builder = $this->getContainerBuilder();
		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$presenterFactory->addSetup('setMapping', array(array('FileManager' => 'Kappa\FileManager\Application\UI\*Presenter')));
		$dataProvider = $builder->addDefinition($this->prefix('dataProvider'))
			->setClass('Kappa\FileManager\Helpers\DataProvider');
		foreach ($config as $key => $value) {
			$setterName = "set" . strtoupper(substr($key, 0, 1)) . substr($key, 1, strlen($key) - 1);
			$dataProvider->addSetup($setterName, array($value));
		}
		$builder->addDefinition($this->prefix('directoryForm'))
			->setClass('Kappa\FileManager\Forms\Directory\DirectoryFormFactory');
		$builder->addDefinition($this->prefix('directoryFormProcessor'))
			->setClass('Kappa\FileManager\Forms\Directory\DirectoryFormProcessor');
		$builder->addDefinition($this->prefix('fileNameHelper'))
			->setClass('Kappa\FileManager\Helpers\FileNameHelper');

		$builder->addDefinition($this->prefix('fileManagerFactory'))
			->setClass('Kappa\FileManager\FileManagerFactory', array('@session', $this->prefix('@directoryForm'), $this->prefix('@dataProvider')));
	}
}