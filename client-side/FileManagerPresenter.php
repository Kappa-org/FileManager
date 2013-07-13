<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
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
