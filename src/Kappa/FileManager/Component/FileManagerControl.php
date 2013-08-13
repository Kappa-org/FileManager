<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component;

use Nette\Application\UI\Control;
use Kappa\FileManager\Component\Forms\Directory\IDirectoryFormFactory;
use Kappa\FileManager\Component\Forms\File\IFileFormFactory;
use Kappa\FileSystem\Directory;
use Kappa\FileSystem\File;
use Nette\Http\Session;

/**
 * Class FileManagerControl
 * @package Kappa\FileManager
 */
class FileManagerControl extends Control
{
	/** @var \Nette\Http\SessionSection */
	private $session;

	/** @var array */
	private $_params;

	/** @var string */
	private $type;

	/** @var \Kappa\FileManager\Component\Forms\File\IFileFormFactory */
	private $fileFormFactory;

	/** @var \Kappa\FileManager\Component\Forms\Directory\IDirectoryFormFactory */
	private $directoryFormFactory;

	/**
	 * @param Session $session
	 * @param IFileFormFactory $fileFormFactory
	 * @param IDirectoryFormFactory $directoryFormFactory
	 */
	public function __construct(Session $session, IFileFormFactory $fileFormFactory, IDirectoryFormFactory $directoryFormFactory)
	{
		$this->session = $session->getSection('Kappa-FileManager');
		if (!$this->session->actualDir)
			$this->session->actualDir = array();
		$this->fileFormFactory = $fileFormFactory;
		$this->directoryFormFactory = $directoryFormFactory;
	}

	/**
	 * @param array $params
	 */
	public function setParams(array $params)
	{
		$this->_params = $params;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return Directory
	 */
	private function getActualDir()
	{
		$dir = $this->_params['wwwDir'];
		$dir .= '/';
		$dir .= $this->_params['uploadDir'];
		$dir .= '/';
		if (count($this->session->actualDir)) {
			$dir .= implode('/', $this->session->actualDir);
			$dir .= '/';
		}
		return new Directory($dir);
	}

	/**
	 * @param string $move
	 */
	public function handleMove($move)
	{
		if ($move == "home")
			$this->session->actualDir = array();
		else {
			if (in_array($move, $this->session->actualDir)) {
				$index = array_search($move, $this->session->actualDir) + 1;
				$to = count($this->session->actualDir) - 1;
				for ($i = $index; $i <= $to; $i++) {
					unset($this->session->actualDir[$i]);
				}
			} else
				$this->session->actualDir[] = $move;
		}
		if ($this->presenter->isAjax())
			$this->invalidateControl('Kappa-fileManager');
		else
			$this->redirect('this');
	}

	/**
	 * @param string $path
	 */
	public function handleDeleteDir($path)
	{
		$directory = new Directory($path);
		$directory->remove();
		if ($this->presenter->isAjax())
			$this->invalidateControl('Kappa-fileManager');
		else
			$this->redirect('this');
	}

	/**
	 * @param string $path
	 */
	public function handleDeleteFile($path)
	{
		$file = new File($path);
		$file->remove();
		if ($this->presenter->isAjax())
			$this->invalidateControl('Kappa-fileManager');
		else
			$this->redirect('this');
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentDirectory()
	{
		return $this->directoryFormFactory->create($this->getActualDir());
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentFile()
	{
		return $this->fileFormFactory->create($this->getActualDir(), $this->_params);
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/Templates/default.latte');
		$this->template->assetsDir = $this->_params['assetsDir'];
		$this->template->navigation = $this->session->actualDir;
		$this->template->directories = $this->getActualDir()->getDirectories();
		$this->template->files = $this->getActualDir()->getFiles();
		$this->template->maxFile = ini_get('max_file_uploads');
		$this->template->assetsFile = $this->_params['assetsDir'];
		$this->template->openType = $this->type;
		$this->template->render();
	}
}
