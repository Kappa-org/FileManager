<?php
/**
 * FileManager.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 29.9.12
 */

namespace App;

class FileManagerPresenter extends \Kappa\Application\UI\Presenter
{
	/**
	 * @autowire
	 * @var Kappa\Packages\FileManager\FileManagerFactory
	 */
	protected $fileManagerFactory;

	public function beforeRender()
	{
		$assetsDir = $this->context->getParameters();
		$assetsDir = $assetsDir['FileManager']['assetsDir'];
		$this->template->assetsDir = $assetsDir;
	}

	public function startup()
	{
		parent::startup();
		$this->setLayout(False);
	}

	/**
	 * @return array
	 *
	 */
	public function formatTemplateFiles()
	{
		return array(__DIR__ . '/Templates/@layout.latte');
	}

	/**
	 * @return Kappa\Packages\FileManager\FileManagerControl
	 */
	protected function createComponentManager()
	{
		$manager = $this->fileManagerFactory;
		return $manager->create();
	}
}
