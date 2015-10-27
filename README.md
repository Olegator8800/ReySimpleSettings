# Simple Settings for Bitrix

Бибилиотека для расширения стандартного .settings.php в CMS Bitrix

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Olegator8800/ReySimpleSettings/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Olegator8800/ReySimpleSettings/?branch=master)

Установка
------------

Composer:

    $ composer require rey/simplesettings dev-master

Требования
------------

* Bitrix: >=12
* php: >=5.3.0

Использование
------------

Создать файл htdocs\bitrix\.settings_extra.php

```php
	require_once __DIR__.'/../../vendor/autoload.php';

	$parametrsFile = __DIR__.'/../../config/parameters.ini';
	$extendParametrsDir = __DIR__.'/../../config/parametrs.d/';

	$config = new Rey\SimpleSettings\SettingsExtender($parametrsFile, $extendParametrsDir);

	return $config->getExtendedSettings();
```

$parametrsFile - путь до основного конфига
$extendParametrsDir - путь до дириктории в которой могут находится файлы использующиеся для переопределения параметров основного конфига

После чего можно получить доступ к параметрам

```php
	$parameters = Bitrix\Main\Config\Configuration::getInstance();

	$parameters->get('some_value');
	//или
	$parameters['some_value'];
```

Пример файла parameters.ini

```ini
	[connections]
	host = localhost
	database = mydb
	login = root
	password =
	className = \Bitrix\Main\DB\MysqlConnection
	readonly = true
```

dbconn.php
------------

Для избавления от дублирования параметров, в файл dbconn.php добавить:

```php
	require_once __DIR__.'/../../htdocs/bitrix/modules/main/lib/loader.php';
	$parameters = Bitrix\Main\Config\Configuration::getInstance();

	$DBType = 'mysql';
	$DBHost = $parameters['connections']['default']['host'];
	$DBLogin = $parameters['connections']['default']['login'];
	$DBPassword = $parameters['connections']['default']['password'];
	$DBName = $parameters['connections']['default']['database'];
	$DBDebug = false;
	$DBDebugToFile = false;
```