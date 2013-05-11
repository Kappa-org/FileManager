<?php
/**
 * FileForm.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 10.5.13
 *
 * @package Kappa\FileManager
 */

namespace Kappa\FileManager\Forms\File;

use Kappa\Application\UI\Form;
use Kappa\FileSystem\Directory;

/**
 * Class FileForm
 *
 * @package Kappa\FileManager\Forms\File
 */
class FileForm extends Form
{
	/** @var FileFormSubmitted */
	private $fileFormSubmitted;

	/** @var \Kappa\FileSystem\Directory */
	private $directory;

	/** @var array */
	private $params;

	/**
	 * @param FileFormSubmitted $fileFormSubmitted
	 */
	public function injectFileFormSubmitted(FileFormSubmitted $fileFormSubmitted)
	{
		$this->fileFormSubmitted = $fileFormSubmitted;
	}

	/**
	 * @param Directory $directory
	 * @param array $params
	 */
	public function __construct(Directory $directory, array $params)
	{
		parent::__construct();
		$this->compose();
		$this->directory = $directory;
		$this->params = $params;
	}

	private function compose()
	{
		$this->addMultifileUpload('files', 'Vyberte soubory:');
		$this->addSubmit('submit', 'Nahrát soubor(y)')
			->setAttribute('class', 'btn btn-primary');
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param FileForm $form
	 */
	public function formSubmitted(FileForm $form)
	{
		$this->fileFormSubmitted->create($form, $this->directory, $this->params);
	}
}
