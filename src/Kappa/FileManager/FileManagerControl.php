<?php
/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager;

use Kappa\FileManager\Forms\Directory\DirectoryFormFactory;
use Nette\Application\UI\Control;
use Kappa\FileManager\Forms\File\IFileFormFactory;
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

	/** @var \Kappa\FileManager\Forms\Directory\DirectoryFormFactory */
	private $directoryFormFactory;

	/**
	 * @param Session $session
	 * @param DirectoryFormFactory $directoryFormFactory
	 */
	public function __construct(Session $session, DirectoryFormFactory $directoryFormFactory)
	{
		$this->session = $session->getSection('Kappa-FileManager');
		if (!$this->session->actualDir)
			$this->session->actualDir = array();
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
		return new Directory($dir, Directory::LOAD);
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
		$directory = new Directory($path, Directory::LOAD);
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
		$file = new File($path, File::LOAD);
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
		return $this->directoryFormFactory->createForm($this->getActualDir());
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/Templates/filemanager.latte');
		$this->template->navigation = $this->session->actualDir;
		$this->template->directories = $this->getActualDir()->getDirectories();
		$this->template->files = $this->getActualDir()->getFiles();
		$this->template->maxFile = ini_get('max_file_uploads');
		$this->template->openType = $this->type;
		$this->template->render();
	}
}
