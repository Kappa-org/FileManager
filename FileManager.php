<?php
/**
 * FileManager.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 29.9.12
 */

class FileManagerPresenter extends \Nette\Application\UI\Presenter
{
	/**
	 * @var Kappa\Packages\FileManager\FileManagerFactory
	 */
	private $fileManagerFactory;

	public function startup()
	{
		parent::startup();
		$this->setLayout(False);
	}

	/**
	 * @param Kappa\Packages\FileManager\FileManagerFactory $fileManagerFactory
	 */
	public function injectFileManagerFactory(\Kappa\Packages\FileManager\FileManagerFactory $fileManagerFactory)
	{
		$this->fileManagerFactory = $fileManagerFactory;
	}

	/**
	 * @return array
	 *
	 */
	public function formatTemplateFiles()
	{
		return array(LIBS_DIR . '/FileManager/Templates/default.latte');
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
