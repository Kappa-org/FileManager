<?php
/**
 * FileManagerFactory.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 29.9.12
 */

namespace Kappa\Packages\FileManager;

class FileManagerFactory extends \Nette\Object
{
	/**
	 * @var Nette\Http\Session
	 */
	private $_session;
	private $_params;

	public function injectSession(\Nette\Http\Session $session)
	{
		$this->_session = $session;
	}

	public function setParams($parmas)
	{
		$this->_params = $parmas;
	}

	public function create()
	{
		$manager = new \Kappa\Packages\FileManager\FileManagerControl;
		$manager->setSession($this->_session);
		$manager->setParams($this->_params);
		return $manager;
	}
}
