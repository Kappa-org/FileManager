<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component\DI;

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
		'uploadDir' => 'media/upload',
		'maxWidth' => 940,
		'maxHeight' => null,
		'maxFileSize' => 32000000,
		'wwwDir' => '%wwwDir%',
		'assetsDir' => 'kappa-filemanager',
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultParams);
		$builder = $this->getContainerBuilder();
		$presenterFactory = $builder->getDefinition('nette.presenterFactory');
		$presenterFactory->addSetup('setMapping', array(array('FileManager' => 'Kappa\FileManager\Module\Presenters\*Presenter')));

		$builder->addDefinition($this->prefix("fileFormSubmitted"))
			->setClass('Kappa\FileManager\Component\Forms\File\FileFormSubmitted');

		$builder->addDefinition($this->prefix('directoryFormSubmitted'))
			->setClass('Kappa\FileManager\Component\Forms\Directory\DirectoryFormSubmitted');

		$builder->addDefinition($this->prefix('fileManagerFactory'))
			->setClass('Kappa\FileManager\Component\FileManagerFactory', array('@session', '@fileManager.fileForm', '@fileManager.directoryForm'))
			->addSetup('setParams', array($config));

		$builder->addDefinition($this->prefix('fileForm'))
			->setFactory('Kappa\FileManager\Component\Forms\File\FileForm', array($builder->literal('$directory'), $builder->literal('$params')))
			->setParameters(array('Kappa\FileSystem\Directory directory', 'array params'))
			->setImplement('Kappa\FileManager\Component\Forms\File\IFileFormFactory')
			->setAutowired(false);

		$builder->addDefinition($this->prefix('directoryForm'))
			->setFactory('Kappa\FileManager\Component\Forms\Directory\DirectoryForm', array($builder->literal('$directory')))
			->setParameters(array('Kappa\FileSystem\Directory directory'))
			->setImplement('Kappa\FileManager\Component\Forms\Directory\IDirectoryFormFactory')
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