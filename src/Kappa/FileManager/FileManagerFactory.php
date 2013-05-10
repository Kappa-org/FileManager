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
	private $_session;

	/** @var array */
	private $_params;

	/** @var string */
	private $openType;

	/**
	 * @param \Nette\Http\Session $session
	 */
	public function injectSession(\Nette\Http\Session $session)
	{
		$this->_session = $session;
	}

	/**
	 * @param array $parmas
	 */
	public function setParams($parmas)
	{
		$this->_params = $parmas;
	}

	/**
	 * @param string $type
	 */
	public function setOpenType($type)
	{
		$this->openType = $type;
	}

	/**
	 * @return FileManagerControl
	 */
	public function create()
	{
		$manager = new \Kappa\FileManager\FileManagerControl;
		$manager->setSession($this->_session);
		$manager->setParams($this->_params);
		$manager->setOpenType($this->openType);
		return $manager;
	}
}
