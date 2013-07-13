<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Sample Model class
 * @extends Model
 */
class Todos_Model extends Model
{
	/**
	 * Insert a new todo task into the the todos
	 * @param  string $description
	 * @param  int $completed
	 * @return int|boolean
	 */
	public function create($description, $completed)
	{
		/**
		 * Try and insert
		 */
		$statement = $this->prepare("INSERT INTO todos (description, completed) VALUES (:description, :completed)");

		/**
		 * Valdiate that we have a statement
		 */
		if(!$statement)
		{
			throw new Exception("Unable to insert todo into the database", 1);
		}

		/**
		 * Execute the request
		 */
		$result = $statement->execute(array(
			':description' => $description,
			':completed' => $completed
		));

		/**
		 * If we have a success, return the id
		 */
		if($result)
		{
			return $this->lastInsertId('id');
		}

		return false;
	}
}