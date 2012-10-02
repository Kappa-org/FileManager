<?php
/**
 * FileManagerControl.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 1.9.12
 */

namespace Kappa\Packages\FileManager;

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
	public function setParams($params)
	{
		$this->_params = $params;
	}


	/**
	 * @return string
	 */
	private function getActualDir()
	{
		$dir = WWW_DIR;
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
	 * @param $path
	 * @return array|string
	 */
	private function getPath($path)
	{
		$path = explode($this->_params['uploadDir'], $path);
		$path = '/'.$this->_params['uploadDir'].$path[1];
		return $path;
	}

	/**
	 * @param $directory
	 * @return bool
	 */
	private function deleteDirectory($directory)
	{
		$count = strlen($directory)-1;
		if(($directory[$count] != '/') && ($directory[$count] != '')) $directory .= '/';
		$d = @opendir($directory);
		while($f = @readdir($d))
		{
			if(($f == '.') || ($f == '..')) continue;
			if(@is_dir($directory.$f))
				$this->deleteDirectory($directory.$f.'/');
			else
				@unlink($directory.$f);
		}
		@closedir($d);
		return @rmdir($directory);
	}

	/**
	 * @param $move
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
	 * @param $path
	 */
	public function handleDeleteDir($path)
	{
		if($this->deleteDirectory($path))
			if($this->presenter->isAjax())
				$this->invalidateControl('Kappa-fileManager');
			else
				$this->redirect('this');
	}

	/**
	 * @param $path
	 */
	public function handleDeleteFile($path)
	{
		$file = WWW_DIR;
		$file .= $path;
		@unlink($file);
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
	 * @param $fileName
	 * @return string
	 */
	private function getIcon($fileName)
	{
		$type = (string)strrchr($fileName, ".");
		if(\Kappa\Utils\Validators::isImage($type))
			return 'image';
		if(!\Kappa\Utils\Validators::isImage($type) && !array_key_exists($type, $this->_iconType))
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
		$directories = \Kappa\Utils\Arrays::sortBySubArray($directories, 'date');
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
				'icon' => $this->getIcon($file->getFilename()),
				'date' => Date('j.n.Y', $file->getCTime()),
			);
		}
		$files = \Kappa\Utils\Arrays::sortBySubArray($files, 'date');
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
		$path .= $values['name'];
		$i = 0;
		if(is_dir($path))
		{
			$i++;
			while(is_dir($path.'-'.$i))
			{
				$i++;
			}
			@mkdir($path.'-'.$i, 0777);
		}
		else
			@mkdir($path, 0777);
	}

	/**
	 * @return \Kappa\Application\UI\Form
	 */
	protected function createComponentAddNewFile()
	{
		$form = new \Kappa\Application\UI\Form;
		$form->addMultifileUpload('files');
		$form->addSubmit('submit', 'Nahrát soubor(y)');
		$form->onSuccess[] = $this->addNewFiles;
		return $form;
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
				$fileName = \Kappa\Utils\Parser::createUniqueFileName($path, $name, $type);
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
		$fileName = \Kappa\Utils\Parser::createUniqueFileName($path, $name, $type);
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
			$name = \Kappa\Utils\Parser::createUrlString(substr($file->getName(), 0, -strlen($type)));
			if(\Kappa\Utils\Validators::isImage($type))
				$this->uploadImage($file, $name, $type);
			else
				$this->uploadFile($name, $type, $file);
		}
	}

	public function render()
	{
		$this->template->setFile(LIBS_DIR . '/FileManager/Templates/FileManager.latte');
		$this->template->navigation = $this->_session->actualDir;
		$this->template->directories = $this->getDirectories();
		$this->template->files = $this->getFiles();
		$this->template->render();
	}
}
