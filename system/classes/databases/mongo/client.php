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
class Mongo_Client extends MongoClient
{
	/**
	 * Constructor for Database Object
	 */
	public function __construct()
	{
		/**
		 * Fetch the configuration object from the registry
		 */
		$config = Registry::get('Application')->getConfiguration('mongo');

		/**
		 * @todo This should be config soon
		 */
		parent::__construct($config->dsn);
	}
}