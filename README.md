#File manager
-
**NOT WORKING**

Modern system for easy working with files and folders on the server. Can by used as plugin fot tinyMCE, CKEditor or as clasic Nette Framework component too.

###Requirements:
-
* PHP 5.3.*
* [Nette Framework](http://nette.org)
* [Kappa framework](https://github.com/Budry/Kappa)
* [Composer](http://getcomposer.org/)

###Install:
-
The best way to install File Manager is using Composer:
<pre>
$ cd project/root/path
$ composer install
$ mv libs/FileManager/Media/ www/path/FileManager
</pre>
If you use [Kappa/Sanbox](http://github.com/Kappa-org/Sanbox) you don't have to do next change and you have several options for settings file manager
Location for settings file manager are in config.neom file.
<pre>
root/
	app/
		config/
			config.neon
</pre>

and you can change next lines:

<pre>
uploadDir: 'path/to/your/upload/folder/'
maxImgDimension: '700x%'
maxFileSize: 50000000
</pre>

* **uploadDir:** Folder for upload your files and folders
* **maxImgDimension:** If uploaded image is larger than dimension entered on this line, it will be  scaled to entered dimension. You can replace second dimension with character % and this dimension will be calculated.
* **maxFileSize:** Maximum file size in byte


If you want to use file manager without [Kappa/Sanbox](http://github.com/Kappa-org/Sanbox) you must edit config file.
You must add this lines into section *"services"* in config file.

<pre>
FileManagerFactory:
	class: Kappa\Packages\FileManager\FileManagerFactory
     setup:
setParams(%FileManager%)
</pre>

and add this into section *"parameters"*:

<pre>
FileManager:
	uploadDir: 'media/upload'
     maxImgDimension: '700x%'
     maxFileSize: 2000000
</pre>

###Update:
-
The best way to update File Manager is using Composer:

<pre>
$ cd project/root/path
$ composer update
$ rm -r path/to/FileManager/
$ mv libs/FileManager/Media/ path/to/FileManager
</pre>

After update is recommended deleting cache in *"cache/"* folder