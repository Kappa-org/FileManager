<?php
/**
 * FileManagerExtension.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 11.5.13
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Diagnostics\Debugger;

/**
 * Class FileManagerExtension
 *
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
		'assetsDir' => 'FileManager',
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultParams);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix("fileFormSubmitted"))
			->setClass('Kappa\FileManager\Forms\File\FileFormSubmitted');

		$builder->addDefinition($this->prefix('directoryFormSubmitted'))
			->setClass('Kappa\FileManager\Forms\Directory\DirectoryFormSubmitted');

		$builder->addDefinition($this->prefix('fileManagerFactory'))
			->setClass('Kappa\FileManager\FileManagerFactory', array('@session', '@fileManager.fileForm', '@fileManager.directoryForm'))
			->addSetup('setParams', array($config));

		$builder->addDefinition($this->prefix('fileForm'))
			->setFactory('Kappa\FileManager\Forms\File\FileForm', array($builder->literal('$directory'), $builder->literal('$params')))
			->setParameters(array('Kappa\FileSystem\Directory directory', 'array params'))
			->setImplement('Kappa\FileManager\Forms\File\IFileFormFactory')
			->setAutowired(false);

		$builder->addDefinition($this->prefix('directoryForm'))
			->setFactory('Kappa\FileManager\Forms\Directory\DirectoryForm', array($builder->literal('$directory')))
			->setParameters(array('Kappa\FileSystem\Directory directory'))
			->setImplement('Kappa\FileManager\Forms\Directory\IDirectoryFormFactory')
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
		self::addExtension('addSelectDirectory', '\Kappa\FileManager\Forms\Controls\SelectDirectory');
		self::addExtension('addSelectFile', '\Kappa\FileManager\Forms\Controls\SelectFile');
	}

	/**
	 * @param string $name
	 * @param string $class
	 */
	private static function addExtension($name, $class)
	{
		\Nette\Forms\Container::extensionMethod($name, function (\Nette\Forms\Container $container, $name, $label = null) use ($class) {
				return $container[$name] = new $class($label);
			}
		);
	}
}