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

Add section fileManager into config

```yaml
fileManager:
	uploadDir: 'media/upload'
	maxWidth: 940
	maxHeight: null
	maxFileSize: 32000000
	wwwDir: %wwwDir%
	assetsDir: FileManager
```

and set route for FileManagerPresenter

```php
$router[] = new Route('file-manager/', 'FileManager:image');
```