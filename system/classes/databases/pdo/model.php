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
 * Database Class
 * @extends PDO
 */
class PDO_Model extends PDO
{
	/**
	 * Constructor for Database Object
	 */
	public function __construct()
	{
		/**
		 * Fetch the configuration object from the registry
		 */
		$config = Registry::get('Application')->getConfiguration('pdo');

		/**
		 * Call the parent constructor
		 */
		parent::__construct($config->dsn, $config->username, $config->password, $config->driver_options);

		/**
		 * Set the PDO Instance to throw exceptions on error
		 */
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
}