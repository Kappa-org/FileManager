<?php
/**
 * DirectoryForm.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 10.5.13
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager\Forms\Directory;

use Kappa\Application\UI\Form;
use Kappa\FileSystem\Directory;

/**
 * Class DirectoryForm
 *
 * @package Kappa\FileManager\Forms\Directory
 */
class DirectoryForm extends Form
{
	/** @var DirectoryFormSubmitted */
	private $directoryFormSubmitted;

	/** @var \Kappa\FileSystem\Directory */
	private $directory;

	/**
	 * @param DirectoryFormSubmitted $directoryFormSubmitted
	 */
	public function injectDirectoryFormSubmitted(DirectoryFormSubmitted $directoryFormSubmitted)
	{
		$this->directoryFormSubmitted = $directoryFormSubmitted;
	}

	/**
	 * @param Directory $directory
	 */
	public function __construct(Directory $directory)
	{
		$this->compose();
		$this->directory = $directory;
		parent::__construct();
	}

	private function compose()
	{
		$this->addText('name', 'Název složky:')
			->setRequired('Musíte vyplnit jméno složky');
		$this->addSubmit('submit', 'Vytvořit složku')
			->setAttribute('class', 'btn btn-primary');
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param DirectoryForm $form
	 */
	public function formSubmitted(DirectoryForm $form)
	{
		$this->directoryFormSubmitted->create($form, $this->directory);
	}
}
