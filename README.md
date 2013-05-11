#File manager

Modern system for easy working with files and folders on the server. Can by used as plugin fot tinyMCE, CKEditor or as clasic Nette Framework component too.

##Requirements:

* PHP 5.3.*
* [Nette Framework](http://nette.org)
* [Kappa framework](https://github.com/Budry/Kappa)
* [Composer](http://getcomposer.org/)

##Installation

The best way to install Kappa/FileManager is using Composer:

```bash
$ composer require kappa/filemanager:@dev
```

Add section **FileManager** into *parameters*

```yaml
parameters:
	FileManager:
		uploadDir: media/upload
		maxWidth: 940
		maxHeight: null
		maxFileSize: 32000000
		wwwDir: %wwwDir%
		assetsDir: FileManager
```

Register services and factories
```yaml
services:
	- Kappa\FileManager\Forms\Directory\DirectoryFormSubmitted
        - Kappa\FileManager\Forms\File\FileFormSubmitted
    
factories:
	DirectoryForm:
		create: Kappa\FileManager\Forms\Directory\DirectoryForm(%directory%)
		implement: Kappa\FileManager\Forms\Directory\IDirectoryFormFactory
		parameters: [Kappa\FileSystem\Directory directory]

	FileForm:
		create: Kappa\FileManager\Forms\File\FileForm(%directory%, %params%)
		implement: Kappa\FileManager\Forms\File\IFileFormFactory
		parameters: [Kappa\FileSystem\Directory directory, array params]
```

Set route for FileManagerPresenter

```php
$router[] = new Route('file-manager/', 'FileManager:image');
```