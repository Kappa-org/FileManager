<?php
/**
 * This file is part of the Kappa\FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\FileManager;

use Nette\Object;

/**
 * Class DataProvider
 * @package Kappa\FileManager
 */
class DataProvider extends Object
{
	/** @var array */
	private $data = array();

	/**
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 * @throws InvalidStateException
	 */
	public function __call($name, $args)
	{
		if ($this->getAction($name) === "set") {
			$this->data[$this->getSectionName($name)] = $args[0];
		}
		if ($this->getAction($name) === "get") {
			if (array_key_exists($this->getSectionName($name), $this->data)) {
				return $this->data[$this->getSectionName($name)];
			} else {
				throw new InvalidStateException("Parameter '{$this->getSectionName($name)}' has not been found!");
			}
		}
	}

	/**
	 * @param string $methodName
	 * @return string
	 */
	private function getSectionName($methodName)
	{
		return strtolower(substr($methodName, 3, 1)) . substr($methodName, 4, strlen($methodName) - 3);
	}

	/**
	 * @param string $methodName
	 * @return string
	 */
	private function getAction($methodName)
	{
		return substr($methodName, 0, 3);
	}
}