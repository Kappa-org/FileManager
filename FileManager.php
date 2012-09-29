<?php
/**
 * FileManager.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 29.9.12
 */

class FileManagerPresenter extends \Nette\Application\UI\Presenter
{
	public function startup()
	{
		parent::startup();
		$this->setLayout(False);
	}

	public function formatTemplateFiles()
	{
		return array(LIBS_DIR . '/FileManager/Templates/default.latte');
	}

	protected function createComponentManager()
	{
		$manager = new \Kappa\Packages\FileManager\FileManagerFactory;
		return $manager->create();
	}
}
