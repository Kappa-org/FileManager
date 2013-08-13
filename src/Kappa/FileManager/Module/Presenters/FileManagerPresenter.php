<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Module\Presenters;

use Nette\Application\UI\Presenter;

class FileManagerPresenter extends Presenter
{
	/**
	 * @inject
	 * @var \Kappa\FileManager\Component\FileManagerFactory
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
		return array(__DIR__ . '/../Templates/@layout.latte');
	}

	/**
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		return array(__DIR__ . '/../Templates/component.latte');
	}

	/**
	 * @return \Kappa\FileManager\Component\FileManagerControl
	 */
	protected function createComponentFileManager()
	{
		$manager = $this->fileManagerFactory;
		$manager->setType($this->getParameter('type'));
		return $manager->create();
	}
}