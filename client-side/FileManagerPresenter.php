<?php
/**
 * PostsPresenter.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 29.9.12
 *
 * @package Kappa\FileManager
 */

/**
 * Class FileManagerPresenter
 */
class FileManagerPresenter extends \Nette\Application\UI\Presenter
{
	/**
	 * @inject
	 * @var \Kappa\FileManager\FileManagerFactory
	 */
	public $fileManagerFactory;

	/**
	 * @return \Kappa\FileManager\FileManagerControl
	 */
	protected function createComponentManager()
	{
		$manager = $this->fileManagerFactory;
		$manager->setType($this->action);
		return $manager->create();
	}
}
