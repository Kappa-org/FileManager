<?php
/**
 * FileManagerControl.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 1.9.12
 */

namespace Kappa\Packages\FileManager;
use Kappa\Utils;

class FileManagerControl extends \Nette\Application\UI\Control
{
	/**
	 * @var Nette\Http\Session
	 */
	private $_session;

	/**
	 * @var array
	 */
	private $_params;

	/**
	 * @var array
	 */
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
		$this->_session = $session->getSection('Kappa-FileManager');
		if(!$this->_session->actualDir)
			$this->_session->actualDir = array();
	}

	/**
	 * @param array $params
	 */
	public function setParams(array $params)
	{
		$this->_params = $params;
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
		if(count($this->_session->actualDir))
		{
			$dir .= implode('/', $this->_session->actualDir);
			$dir .= '/';
		}
		return $dir;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function getPath($path)
	{
		$path = explode($this->_params['uploadDir'], $path);
		$path = $this->_params['uploadDir'].$path[1];
		return $path;
	}

	/**
	 * @param string $move
	 */
	public function handleMove($move)
	{
		if($move == "home")
			$this->_session->actualDir = array();
		else
		{
			if(in_array($move, $this->_session->actualDir))
			{
				$index = array_search($move, $this->_session->actualDir) + 1;
				$to = count($this->_session->actualDir) - 1;
				for($i = $index; $i <= $to; $i++)
				{
					unset($this->_session->actualDir[$i]);
				}
			}
			else
				$this->_session->actualDir[] = $move;
		}
		if($this->presenter->isAjax())
			$this->invalidateControl('Kappa-fileManager');
		else
			$this->redirect('this');
	}

	/**
	 * @param string $path
	 */
	public function handleDeleteDir($path)
	{
		if(Utils\Folder::recursiveDelete($path))
			if($this->presenter->isAjax())
				$this->invalidateControl('Kappa-fileManager');
			else
				$this->redirect('this');
	}

	/**
	 * @param string $path
	 */
	public function handleDeleteFile($path)
	{
		$file = $this->_params['wwwDir'];
		$file .= '/'.$path;
		Utils\Files::deleteFiles($file);
		if($this->presenter->isAjax())
			$this->invalidateControl('Kappa-fileManager');
		else
			$this->redirect('this');
	}

	public function handleRefresh()
	{
		if($this->presenter->isAjax())
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
		if(Utils\Validators::isImage($file))
			return 'image';
		if(!Utils\Validators::isImage($file) && !array_key_exists($type, $this->_iconType))
			return 'other';
		if(array_key_exists($type, $this->_iconType))
			return $this->_iconType[$type];
	}

	/**
	 * @return array
	 */
	private function getDirectories()
	{
		$actualDir = $this->getActualDir();
		$directories = array();
		foreach (\Nette\Utils\Finder::findDirectories('*')->in($actualDir) as $path => $directory)
		{
			$directories[] = array(
				'name' => $directory->getFilename(),
				'path' => $path,
				'date' => Date('j.n.Y', $directory->getCTime()),
			);
		}
		$directories = Utils\Arrays::sortBySubArray($directories, 'name');
		return $directories;
	}

	/**
	 * @return array
	 */
	private function getFiles()
	{
		$actualDir = $this->getActualDir();
		$files = array();
		foreach (\Nette\Utils\Finder::findFiles('*')->in($actualDir) as $path => $file)
		{
			$files[] = array(
				'path' => $this->getPath($path),
				'name' => $file->getFilename(),
				'icon' => $this->getIcon($path),
				'date' => Date('j.n.Y', $file->getCTime()),
			);
		}
		$files = Utils\Arrays::sortBySubArray($files, 'name');
		return $files;
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentAddNewDir()
	{
		$form = new \Kappa\Application\UI\Form;
		$form->addText('name', 'Název složky')
			->setRequired('Musíte vyplnit jméno složky');
		$form->addSubmit('submit', 'Vytvořit složku')
			->setAttribute('class', 'nahrat_vytvorit');
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
		$path .= Utils\Strings::createUrlString($values['name']);
		Utils\Folder::create($path, 0777, true);
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentAddNewFile()
	{
		$form = new \Kappa\Application\UI\Form;
		$form->addMultifileUpload('files');
		$form->addSelect('zip', 'Po nahrátí na server', array('rozbalit', 'nerozbalovat'))
			->setDefaultValue(1);
		$form->addSubmit('submit', 'Nahrát soubor(y)');
		$form->onSuccess[] = $this->addNewFiles;
		return $form;
	}

	private function unZip($filename)
	{
		$zip = new \ZipArchive;
		$res = $zip->open($filename);
		if ($res === true)
		{
			$zip->extractTo($this->_params['wwwDir'].'/'.$this->_params['tempDir'].'/');
			$zip->close();
			@unlink($filename);
		}
	}
	private function uploadFileFromZip($name, $type, $file)
	{
		$fileName = $name.$type;
		$path = $this->_params['wwwDir'].'/'.$this->_params['tempDir'].'/';
		$file->move($path.$fileName);
		$this->unZip($path.$fileName);
		foreach (\Nette\Utils\Finder::findFiles('*')->in($path) as $path => $file)
		{
			$fileData = array(
				'name' => $file->getFilename(),
				'type' => $file->getType(),
				'size' => $file->getSize(),
				'tmp_name' => $path,
				'error' => 0,
			);
			$netteFile = new \Nette\Http\FileUpload($fileData);
			$type = (string)strrchr($file->getFilename(), ".");
			if(Utils\Validators::isImage($type))
				$this->uploadImage($netteFile, $file->getFilename(), $type);
			else
				$this->uploadFile($name, $type, $netteFile);
			@unlink($path);
		}
	}

	/**
	 * @param $name
	 * @param $type
	 * @param $file
	 */
	private function uploadFile($name, $type, $file)
	{
		$path = $this->getActualDir();
		if(isset($this->_params['maxFileSize']))
		{
			if($file->getSize() <= $this->_params['maxFileSize'])
			{
				$fileName = Utils\Files::createUniqueFileName($path, $name, $type);
				$file->move($fileName);
			}
		}
	}

	/**
	 * @param $file
	 * @param $name
	 * @param $type
	 */
	private function uploadImage($file, $name, $type)
	{
		$path = $this->getActualDir();
		$image = \Nette\Image::fromFile($file->getTemporaryFile());
		$sizes = explode('x', $this->_params['maxImgDimension']);
		foreach($sizes as $key => $size)
			$sizes[$key] = str_replace('%', 'null', $size);
		$image->resize($sizes[0], $sizes[1], \Nette\Image::SHRINK_ONLY);
		$fileName = Utils\Files::createUniqueFileName($path, $name, $type);
		$image->save($fileName);
	}

	/**
	 * @param \Kappa\Application\UI\Form $form
	 */
	public function addNewFiles(\Kappa\Application\UI\Form $form)
	{
		$values = $form->getValues();
		$files = $values['files'];
		foreach($files as $file)
		{
			$type = strrchr($file->getName(), ".");
			$name = Utils\Strings::createUrlString(substr($file->getName(), 0, -strlen($type)));
			if(Utils\Validators::isImage($file->getTemporaryFile()))
				$this->uploadImage($file, $name, $type);
			else
			{
				if($type == ".zip" && $values['zip'] == 0)
					$this->uploadFileFromZip($name, $type, $file);
				else
					$this->uploadFile($name, $type, $file);
			}
		}
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/../Templates/FileManager.latte');
		$this->template->navigation = $this->_session->actualDir;
		$this->template->directories = $this->getDirectories();
		$this->template->files = $this->getFiles();
		$this->template->maxFile = ini_get('max_file_uploads');
		$this->template->render();
	}
}
