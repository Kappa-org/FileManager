<?php
/**
 * This file is part of the filemanager package.
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

	protected function createComponentFileManager()
	{
		$manager = $this->fileManagerFactory;
		$manager->setType($this->getParameter('type'));
		return $manager->create();
	}
}