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
class Mongo_Model extends Mongo_Collection
{
	/**
	 * Constructor for Database Object
	 */
	public function __construct()
	{
		/**
		 * Validate we have a collection
		 */
		if(isset($this->_collection) === false)
		{
			/**
			 * Use the class name as the collection
			 */
			$this->_collection = explode("_", get_class($this))[0];
		}

		/**
		 * Construct the parent object
		 */
		parent::__construct();
	}
}