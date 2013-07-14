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
		 * Return the insert value
		 */
		return $this->insert("todos", array(
			"description" => $description, "completed" => $completed
		));
	}
}