<?php
/**
 * PostsPresenter.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 29.9.12
 *
 * @package Kappa
 */

class FileManagerPresenter extends \Nette\Application\UI\Presenter
{
	/**
	 * @inject
	 * @var \Kappa\FileManager\FileManagerFactory
	 */
	public $fileManagerFactory;

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
	 * @return \Kappa\FileManager\FileManagerControl
	 */
	protected function createComponentManager()
	{
		$manager = $this->fileManagerFactory;
		$manager->setOpenType($this->action);
		return $manager->create();
	}
}
