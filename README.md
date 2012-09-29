#File manager - <small>isn't working</small>
-
Modern system for easy working with files and images on the server.
Can be used as plugin for tinyMCE or CKEditor or as clasic Nette Framework component to.

###Requirements:
-
* PHP 5.3.*
* [Nette framework](http://nette.org)
* [Kappa framework](https://github.com/Budry/Kappa)
* [Composer](http://getcomposer.org/)

###Install:
-
**Recommended** install with [Composer](http://getcomposer.org/):
<pre>
$ cd project/root/path
$ composer install
$ mv libs/FileManager/Media/ www/assets/FileManager
</pre>

###Update:
-
<pre>
$ cd project/root/path
$ composer update
$ rm -r path/to/FileManager/
$ mv libs/FileManager/Media/ path/to/FileManager
</pre>