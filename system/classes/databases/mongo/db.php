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
class Mongo_DB extends MongoDB
{
	/**
	 * Construct the DB Object
	 */
	public function __construct()
	{
		/**
		 * Create a new client
		 */
		$client = new Mongo_Client();

		/**
		 * Cosntruct the DB
		 */
		parent::__construct($client, "sample");
	}
}