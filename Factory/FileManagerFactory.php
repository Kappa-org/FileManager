<?php
/**
 * FileManagerFactory.php
 * Author: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 29.9.12
 */

namespace Kappa\Packages\FileManager;

class FileManagerFactory extends \Nette\Object
{

	private $_session;
	private $_params;

	public function injectControlFactory(\Nette\Http\Session $session)
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
