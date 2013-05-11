<?php
/**
 * FileManagerControl.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 1.9.12
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager;

use Kappa\Application\UI\Control;
use Kappa\Utils\FileSystem\Directories;
use Kappa\Utils\FileSystem\Files;
use Kappa\Utils\Validators;

/**
 * Class FileManagerControl
 *
 * @package Kappa\FileManager
 */
class FileManagerControl extends Control
{
	/** @var \Nette\Http\Session */
	private $session;

	/** @var array */
	private $_params;

	/** @var string */
	private $type;

	/** @var array */
	private $_iconType = array(
		'.doc' => 'doc',
		'.docx' => 'doc',
		'.txt' => 'doc',
		'.xls' => 'xls',
		'.xlsx' => 'xls',
		'.csv' => 'xls',
		'.zip' => 'zip',
		'.rar' => 'zip',
		'.7z' => 'zip',
		'.pdf' => 'pdf',
	);

	/**
	 * @param \Nette\Http\Session $session
	 */
	public function setSession(\Nette\Http\Session $session)
	{
		$this->session = $session->getSection('Kappa-FileManager');
		if (!$this->session->actualDir)
			$this->session->actualDir = array();
	}

	/**
	 * @param array $params
	 */
	public function setParams(array $params)
	{
		$this->_params = $params;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
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
		return $dir;
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
		if (Directories::recursiveDelete($path))
			if ($this->presenter->isAjax())
				$this->invalidateControl('Kappa-fileManager');
			else
				$this->redirect('this');
	}

	/**
	 * @param string $file
	 */
	public function handleDeleteFile($file)
	{
		if (Files::deleteFiles($file))
			if ($this->presenter->isAjax())
				$this->invalidateControl('Kappa-fileManager');
			else
				$this->redirect('this');
	}

	public function handleRefresh()
	{
		if ($this->presenter->isAjax())
			$this->invalidateControl('Kappa-fileManager');
		else
			$this->redirect('this');
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function getIcon($file)
	{
		$type = strrchr($file, ".");
		if (Validators::isImage($file))
			return 'image';
		if (!Validators::isImage($file) && !array_key_exists($type, $this->_iconType))
			return 'other';
		if (array_key_exists($type, $this->_iconType))
			return $this->_iconType[$type];
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentDirectory()
	{
		$form = new \Kappa\Application\UI\Form;
		$form->addText('name', 'Název složky:')
			->setRequired('Musíte vyplnit jméno složky');
		$form->addSubmit('submit', 'Vytvořit složku')
			->setAttribute('class', 'btn btn-primary');
		$form->onSuccess[] = $this->createNewDir;
		return $form;
	}

	/**
	 * @param \Kappa\Application\UI\Form $form
	 */
	public function createNewDir(\Kappa\Application\UI\Form $form)
	{
		$values = $form->getValues();
		$path = $this->getActualDir();
		$path .= $values['name'];
		Directories::create($path, 0777, true);
		$this->redirect('this');
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentFile()
	{
		$form = new \Kappa\Application\UI\Form;
		$form->addMultifileUpload('files', 'Vyberte soubory:');
		$form->addSubmit('submit', 'Nahrát soubor(y)')
			->setAttribute('class', 'btn btn-primary');
		$form->onSuccess[] = $this->addNewFiles;
		return $form;
	}

	/**
	 * @param \Kappa\Application\UI\Form $form
	 */
	public function addNewFiles(\Kappa\Application\UI\Form $form)
	{
		$values = $form->getValues();
		$files = $values['files'];
		foreach ($files as $file) {
			$newImage = $this->getActualDir() . $file->getName();
			if (Validators::isImage($file->getTemporaryFile()))
				Files::saveImage($file->getTemporaryFile(), $newImage, array($this->_params['maxWidth'], $this->_params['maxHeight']), "SHRINK_ONLY", true);
			else
				if ($file->size <= $this->_params['maxFileSize'])
					Files::saveFile($file, $this->getActualDir(), true);
		}
		$this->redirect('this');
	}

	private function getDirectories()
	{
		$directories = Directories::getDirectories($this->getActualDir());
		foreach ($directories as $index => $directory) {
			$directories[$index]['relativePath'] = trim(str_replace($this->_params['wwwDir'], "", $directory['absolutePath']));
		}
		return $directories;
	}

	private function getFiles()
	{
		$files = Files::getFiles($this->getActualDir());
		foreach ($files as $index => $file) {
			$files[$index]['relativePath'] = trim(str_replace($this->_params['wwwDir'], "", $file['absolutePath']));
			$files[$index]['icon'] = $this->getIcon($file['absolutePath']);
		}
		return $files;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/Templates/default.latte');
		$this->template->navigation = $this->session->actualDir;
		$this->template->directories = $this->getDirectories();
		$this->template->files = $this->getFiles();
		$this->template->maxFile = ini_get('max_file_uploads');
		$this->template->assetsFile = $this->_params['assetsDir'];
		$this->template->openType = $this->type;
		$this->template->render();
	}
}
