#Used In
Used for Magetitan Mexico site

## Install Via composer
To install this translation package with composer you need access to the command line of your server and you need to have [Composer](https://getcomposer.org).
```
cd <your magento path>
composer config repositories.language_es_mx vcs git@bitbucket.org:wagento-global/language_es_mx.git
composer require  wagento/language_es_mx dev-master
php bin/magento cache:clean
```
#Usage
To use this language pack login to your admin panel and goto `Stores -> Configuration -> General > General -> Locale options` and set the '*locale*' option as '*Spanish (Mexico)*'

