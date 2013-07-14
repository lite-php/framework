<?php
/**
 * LightPHP Framework
 * LitePHP is a framework that has been designed to be lite waight, extensible and fast.
 * 
 * @author Robert Pitt <robertpitt1988@gmail.com>
 * @category core
 * @copyright 2013 Robert Pitt
 * @license GPL v3 - GNU Public License v3
 * @version 1.0.0
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Database Configuration
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