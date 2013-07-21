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
		 * Return the insert value from the parent Model
		 */
		return $this->insert("todos", array(
			"description" => $description,
			"completed"   => $completed
		));
	}
}