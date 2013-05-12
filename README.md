#Kappa\FileManager

Modern system for easy working with files and folders on the server. Can by used as plugin fot tinyMCE, CKEditor or as clasic Nette Framework component too.

##Requirements:

* PHP 5.3.* or higher
* [Nette Framework](http://nette.org)
* [Kappa\Framework](https://github.com/Kappa-org/Framework)
* [Kappa\FileSystem](https://github.com/Kappa-org/FileSystem)
* [Composer](http://getcomposer.org/)

##Installation

The best way to install Kappa/FileManager is using Composer:

```bash
$ composer require kappa/filemanager:@dev
```

Add section fileManager into config and register extension

```yaml
fileManager:
	uploadDir: 'media/upload'
	maxWidth: 940
	maxHeight: null
	maxFileSize: 32000000
	wwwDir: %wwwDir%
	assetsDir: FileManager
```

```php
\Kappa\FileManager\DI\FileManagerExtension::register($configurator);
```

and set route for FileManagerPresenter

```php
$router[] = new Route('file-manager/', 'FileManager:image');
```