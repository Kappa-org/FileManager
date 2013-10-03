<?php
/**
 * This file is part of the Kappa/FileManager package.
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
use Nette\Diagnostics\Debugger;

/**
 * Class FileManagerExtension
 * @package Kappa\FileManager\DI
 */
class FileManagerExtension extends CompilerExtension
{
	private $defaultParams = array(
		'uploadDir' => 'uploads',
		'maxWidth' => 940,
		'maxHeight' => null,
		'maxFileSize' => 32000000,
		'wwwDir' => '%wwwDir%',
		'js' => 'public/js/filemanager.js',
		'css' => 'public/css/filemanager.css',
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultParams);
		$builder = $this->getContainerBuilder();
		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$presenterFactory->addSetup('setMapping', array(array('FileManager' => 'Kappa\FileManager\Application\UI\*Presenter')));

		$builder->addDefinition($this->prefix("fileFormSubmitted"))
			->setClass('Kappa\FileManager\Forms\File\FileFormSubmitted');
		$builder->addDefinition($this->prefix('directoryForm'))
			->setClass('Kappa\FileManager\Forms\Directory\DirectoryFormFactory');
		$builder->addDefinition($this->prefix('directoryFormProcessor'))
			->setClass('Kappa\FileManager\Forms\Directory\DirectoryFormProcessor');
		$builder->addDefinition($this->prefix('fileNameHelper'))
			->setClass('Kappa\FileManager\FileNameHelper');


		$builder->addDefinition($this->prefix('fileManagerFactory'))
			->setClass('Kappa\FileManager\FileManagerFactory', array('@session', '@filemanager.fileForm', '@filemanager.directoryForm'))
			->addSetup('setParams', array($config));

		$builder->addDefinition($this->prefix('fileForm'))
			->setFactory('Kappa\FileManager\Forms\File\FileForm', array($builder->literal('$directory'), $builder->literal('$params')))
			->setParameters(array('Kappa\FileSystem\Directory directory', 'array params'))
			->setImplement('Kappa\FileManager\Forms\File\IFileFormFactory')
			->setAutowired(false);
	}

	/**
	 * @param \Nette\Configurator $config
	 */
	public static function register(Configurator $config)
	{
		$config->onCompile[] = function (\Nette\Configurator $config, Compiler $compiler) {
			$compiler->addExtension('fileManager', new FileManagerExtension());
		};
	}
}