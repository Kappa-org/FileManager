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

	/** @var \Kappa\FileManager\Forms\Directory\DirectoryFormFactory */
	private $directoryFormFactory;

	/**
	 * @param Session $session
	 * @param DirectoryFormFactory $directoryFormFactory
	 */
	public function __construct(Session $session, DirectoryFormFactory $directoryFormFactory)
	{
		$this->session = $session;
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
		return array(
			'js' => $this->params['js'],
			'css' => $this->params['css']
		);
	}

	/**
	 * @return FileManagerControl
	 */
	public function create()
	{
		$manager = new FileManagerControl($this->session, $this->directoryFormFactory);
		$manager->setParams($this->params);
		$manager->setType($this->type);

		return $manager;
	}
}
