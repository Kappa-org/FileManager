<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager\Component;

use Kappa\FileManager\Component\Forms\Directory\IDirectoryFormFactory;
use Kappa\FileManager\Component\Forms\File\IFileFormFactory;
use Nette\Http\Session;
use Nette\Object;

/**
 * Class FileManagerFactory
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
		$this->session = $session;
		$this->fileFormFactory = $fileFormFactory;
		$this->directoryFormFactory = $directoryFormFactory;
	}

	/**
	 * @param array $params
	 */
	public function setParams(array $params)
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
	 * @return string
	 */
	public function getAssetsDir()
	{
		return $this->params['assetsDir'];
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
