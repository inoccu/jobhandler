<?php
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'jobhandler',
		'password' => 'jobhandler',
		'database' => 'jobhandler',
	);

	public $nagios = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'nagios',
		'password' => 'nagios',
		'database' => 'nagios',
	);

	public $scheduler = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'scheduler',
		'password' => 'scheduler',
		'database' => 'scheduler',
	);

}
