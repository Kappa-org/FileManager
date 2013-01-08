<?php
/**
 * FileManager.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 29.9.12
 */

class FileManagerPresenter extends \Kappa\Application\UI\Presenter
{
	/**
	 * @autowire
	 * @var Kappa\Packages\FileManager\FileManagerFactory
	 */
	protected $fileManagerFactory;

	public function beforeRender()
	{
		$this->template->assetsDir = $this->context->getParameters()['FileManager']['assetsDir'];
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
