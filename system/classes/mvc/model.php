<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Base Model Class
 * @extends Database
 */
class Model extends Database
{
	/**
	 * Returns all rows from a table
	 */
	public function all($table)
	{
		/**
		 * Create a select all statement
		 */
		$statement = $this->prepare("SELECT * FROM " . $table);

		/**
		 * Execute the statement
		 */
		if(!$statement || !$statement->execute())
		{
			throw new Exception("Unable to comunicate with the database", 1);
		}

		return $statement->fetchAll();
	}

	public function insert(){}
	public function get(){}
}