#Kappa\FileManager

**EXPERIMENTAL!**

Modern system for easy working with files and folders on the server. Can by used as plugin fot tinyMCE, CKEditor or as clasic Nette Framework component too.

##Requirements:

* PHP 5.3.* or higher
* [Nette Framework](http://nette.org)
* [Kappa\Framework](https://github.com/Kappa-org/Framework)
* [Kappa\Nette-FileSystem](https://github.com/Kappa-app/Nette-FileSystem)

##Installation

The best way to install Kappa/FileManager is using Composer:

```bash
$ composer require kappa/filemanager:@dev
```

### 1. Step

Add into composer.json
```json
"minimum-stability": "dev"
```

### 2. Step

Register extension:

```php
\Kappa\FileManager\DI\FileManagerExtension::register($configurator);
```

or If you use Nette 2.1-dev:

```yaml
extensions:
	fileManager: Kappa\FileManager\Component\DI\FileManagerExtension
```

### 3. Step

Add section fileManager into

```yaml
fileManager:
	uploadDir: 'media/upload'
	maxWidth: 940
	maxHeight: null
	maxFileSize: 32000000
	wwwDir: %wwwDir%
	assetsDir: FileManager
```

### 4. Step

Add route for FileManager

```php
$router[] = new Route('/file-manager/<type>', array(
	'module' => 'FileManager',
	'presenter' => 'FileManager',
	'action' => 'default',
	'type' => 'images',
));
```