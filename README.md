# Simple Settings for Bitrix

����������� ��� ���������� ������������ .settings.php � CMS Bitrix

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Olegator8800/ReySimpleSettings/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Olegator8800/ReySimpleSettings/?branch=master)

���������
------------

Composer:

    $ composer require rey/simplesettings dev-master

����������
------------

* Bitrix: >=12
* php: >=5.3.0

�������������
------------

������� ���� htdocs\bitrix\.settings_extra.php

```php
	require_once __DIR__.'/../../vendor/autoload.php';

	$parametrsFile = __DIR__.'/../../config/parameters.ini';
	$extendParametrsDir = __DIR__.'/../../config/parametrs.d/';

	$config = new Rey\SimpleSettings\SettingsExtender($parametrsFile, $extendParametrsDir);

	return $config->getExtendedSettings();
```

$parametrsFile - ���� �� ��������� �������
$extendParametrsDir - ���� �� ���������� � ������� ����� ��������� ����� �������������� ��� ��������������� ���������� ��������� �������

����� ���� ����� �������� ������ � ����������

```php
	$parameters = Bitrix\Main\Config\Configuration::getInstance();

	$parameters->get('some_value');
	//���
	$parameters['some_value'];
```

������ ����� parameters.ini

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

��� ���������� �� ������������ ����������, � ���� dbconn.php ��������:

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