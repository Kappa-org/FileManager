<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Application\UI;

use Nette\Application\UI\Presenter;

/**
 * Class FileManagerPresenter
 * @package Kappa\FileManager\Application\UI
 */
class FileManagerPresenter extends Presenter
{
	/**
	 * @inject
	 * @var \Kappa\FileManager\FileManagerFactory
	 */
	public $fileManagerFactory;

	protected function beforeRender()
	{
		$this->template->assetsDir = $this->fileManagerFactory->getAssetsDir();
	}

	/**
	 * @return array
	 */
	public function formatLayoutTemplateFiles()
	{
		return array(__DIR__ . '/../../Templates/@layout.latte');
	}

	/**
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		return array(__DIR__ . '/../../Templates/default.latte');
	}

	/**
	 * @return \Kappa\FileManager\FileManagerControl
	 */
	protected function createComponentFileManager()
	{
		$manager = $this->fileManagerFactory;
		$manager->setType($this->getParameter('type'));
		return $manager->create();
	}
}