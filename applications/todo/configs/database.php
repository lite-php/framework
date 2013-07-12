<?php
/**
 * LightPHP Framework
 * 
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Get the applications base path for the dsn
 */
$basepath = Registry::get('Application')->getBasePath();

/**
 * Database Configuration array
 * @var array
 */
$config = array(
	/**
	 * DSN Path used to lookup the database.
	 * @link http://www.php.net/manual/en/pdo.construct.php PDO::Construct
	 */
	"dsn" => "sqlite:$basepath/todo.sqlite",

	/**
	 * Username to be passed to the connection handler
	 */
	"username" => null,

	/**
	 * Password used for authentication.
	 */
	"password" => null,

	/**
	 * Driver Options passed to the PDO Connection Handler
	 */
	"driver_options" => array()
);