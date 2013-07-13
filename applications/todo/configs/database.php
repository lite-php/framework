<?php
/**
 * LightPHP Framework
 * 
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Application Configuration
 */
class Database_Config
{
	/**
	 * DSN Used to connect to the database
	 * @var string
	 * @see http://php.net/manual/en/pdo.construct.php
	 */
	public $dsn = null;

	/**
	 * Username used for authentication
	 * @var string
	 * @see http://php.net/manual/en/pdo.construct.php
	 */
	public $username = null;

	/**
	 * Password used for authentication
	 * @var string
	 * @see http://php.net/manual/en/pdo.construct.php
	 */
	public $password = null;

	/**
	 * Driver Options used for PDO
	 * @var array
	 * @see http://php.net/manual/en/pdo.construct.php
	 */
	public $driver_options = array();

	/**
	 * Within when using SQLLite we need to specifiy a path on teh filesystem.
	 * We do that by using the constructor to access the Application so we can get the application path
	 * For instances like mysql you can just set the value within the the class variables.
	 */
	public function __construct()
	{
		$this->dsn = "sqlite:" . Registry::get('Application')->getResourceLocation(null, 'todo', 'sqlite');
	}
}