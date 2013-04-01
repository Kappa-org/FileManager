<?php
/**
 * PostsPresenter.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 29.9.12
 *
 * @package Kappa
 */

namespace Kappa\App\AdminModule;

use Kappa\Application\UI\SecuredPresenter;

class FileManagerPresenter extends SecuredPresenter
{
	/**
	 * @autowire
	 * @var \Kappa\Packages\FileManager\FileManagerFactory
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
		$this->setLayout(false);
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
	 * @return \Kappa\Packages\FileManager\FileManagerControl
	 */
	protected function createComponentManager()
	{
		$manager = $this->fileManagerFactory;
		$manager->setOpenType($this->action);
		return $manager->create();
	}
}
