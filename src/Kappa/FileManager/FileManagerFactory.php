<?php
/**
 * FileManagerFactory.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 29.9.12
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager;

use Kappa\FileManager\Forms\Directory\IDirectoryFormFactory;
use Kappa\FileManager\Forms\File\IFileFormFactory;
use Nette\Http\Session;
use Nette\Object;

/**
 * Class FileManagerFactory
 *
 * @package Kappa\FileManager
 */
class FileManagerFactory extends Object
{
	/** @var \Nette\Http\Session */
	private $session;

	/** @var array */
	private $params;

	/** @var string */
	private $type;

	/** @var \Kappa\FileManager\Forms\File\IFileFormFactory */
	private $fileFormFactory;

	/** @var \Kappa\FileManager\Forms\Directory\IDirectoryFormFactory */
	private $directoryFormFactory;

	/**
	 * @param Session $session
	 * @param IFileFormFactory $fileFormFactory
	 * @param IDirectoryFormFactory $directoryFormFactory
	 */
	public function __construct(Session $session, IFileFormFactory $fileFormFactory, IDirectoryFormFactory $directoryFormFactory)
	{
		$this->session = $session;
		$this->fileFormFactory = $fileFormFactory;
		$this->directoryFormFactory = $directoryFormFactory;
	}

	/**
	 * @param array $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return FileManagerControl
	 */
	public function create()
	{
		$manager = new FileManagerControl($this->session, $this->fileFormFactory, $this->directoryFormFactory);
		$manager->setParams($this->params);
		$manager->setType($this->type);
		return $manager;
	}
}
