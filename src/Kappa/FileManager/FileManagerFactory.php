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

	/**
	 * @param \Nette\Http\Session $session
	 */
	public function injectSession(\Nette\Http\Session $session)
	{
		$this->session = $session;
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
		$manager = new \Kappa\FileManager\FileManagerControl;
		$manager->setSession($this->session);
		$manager->setParams($this->params);
		$manager->setType($this->type);
		return $manager;
	}
}
