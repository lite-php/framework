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
	 * Returns a list of todos forem th
	 * @return [type]
	 */
	public function getTodos()
	{
		/**
		 * Return a list of todos
		 */
		return $this->all('todos');
	}
}