#Kappa\FileManager

Modern system for easy working with files and folders on the server. Can by used as plugin fot tinyMCE, CKEditor or as clasic Nette Framework component too.

##Requirements:

* PHP 5.3.* or higher
* [Nette Framework](http://nette.org)
* [Kappa\Framework](https://github.com/Kappa-org/Framework)
* [Kappa\FileSystem](https://github.com/Kappa-app/FileSystem)

##Installation

The best way to install Kappa/FileManager is using Composer:

```bash
$ composer require kappa/filemanager:@dev
```

### 1. Step

Register extension:

```php
\Kappa\FileManager\DI\FileManagerExtension::register($configurator);
```

or If you use Nette 2.1-dev:

```yaml
extensions:
	fileManager: Kappa\FileManager\Component\DI\FileManagerExtension
```


### 2. Step

Add section fileManager into config file

```yaml
fileManager:
	uploadDir: 'upload'
	maxWidth: 940
	maxHeight: null
	maxFileSize: 32mb
	wwwDir: %wwwDir%
	js: path/to/filemanager.js
	css: path/to/filemanager.css
	plupupload: path/to/plupuploadDir
```

And add scripts adn styles *(kappa/filemanager/client-side/public/js|css)* and plupload directory *(kappa/filemanager/client-side/public/plupload)* into public directory and wrtite path into config

### 3. Step

Add route for FileManager

```php
$router[] = new Route('/file-manager/<type>', array(
	'module' => 'FileManager',
	'presenter' => 'FileManager',
	'action' => 'default',
	'type' => 'files',
));
```

###4. Step

For work with form extension you must add

```javascript
$fileManager.init('/url', width, height);
```
