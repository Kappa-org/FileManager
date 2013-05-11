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
use Kappa\FileSystem\Directory;
use Kappa\FileSystem\FileUpload;
use Kappa\FileSystem\Image;
use Nette\Http\Session;

/**
 * Class FileManagerControl
 *
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

	/**
	 * @param Session $session
	 */
	public function __construct(Session $session)
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

	public function handleRefresh()
	{
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
		$path = $this->getActualDir()->getInfo()->getPathname();
		$path .= DIRECTORY_SEPARATOR . $values['name'];
		$path = $this->createUniqueDirName($path);
		new Directory($path);
		$this->redirect('this');
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function createUniqueDirName($path)
	{
		$i = 0;
		if (is_dir($path)) {
			$i++;
			while (is_dir($path . '-' . $i)) {
				$i++;
			}
			return $path . '-' . $i;
		} else {
			return $path;
		}
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
		foreach ($values['files'] as $file) {
			$newFile = $this->getActualDir()->getInfo()->getPathname() . DIRECTORY_SEPARATOR . $file->getName();
			if($file->isImage()) {
				new Image($file->getTemporaryFile(), $this->createUniqueFileName($newFile), array($this->_params['maxWidth'], $this->_params['maxHeight']), "shrink_only");
			} else {
				if ($file->size <= $this->_params['maxFileSize'])
					new FileUpload($file, $this->createUniqueFileName($newFile));
			}
		}
		$this->redirect('this');
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function createUniqueFileName($path)
	{
		$i = 0;
		if (file_exists($path)) {
			$i++;
			$type = strrchr($path, ".");
			$newFile = str_replace($type, "-" . $i . $type, $path);
			while (file_exists($newFile)) {
				$i++;
				$newFile = str_replace($type, "-" . $i . $type, $path);
			}
			return $newFile;
		} else
			return $path;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/Templates/default.latte');
		$this->template->navigation = $this->session->actualDir;
		$this->template->directories = $this->getActualDir()->getDirectories();
		$this->template->files = $this->getActualDir()->getFiles();
		$this->template->maxFile = ini_get('max_file_uploads');
		$this->template->assetsFile = $this->_params['assetsDir'];
		$this->template->openType = $this->type;
		$this->template->render();
	}
}
