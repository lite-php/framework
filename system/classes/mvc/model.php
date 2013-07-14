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
 * Base Model Class
 * @extends Database
 */
class Model extends Database
{
	/**
	 * Generic an insert method for conveinance
	 * @param  string $table   Table name for the insert
	 * @param  array  $values  array of values to be added if $columns is not key/value
	 * @return boolean         returns true if the statement executed successfully
	 */
	public function insert($table, $values)
	{
		/**
		 * Validate we have a table name
		 */
		if(empty($table))
		{
			throw new Exception("No table name given for insert.");
		}

		/**
		 * Split the array into columns => values
		 */
		$columns = array_keys($values);
		$values  = array_values($values);

		/**
		 * Create a set of imploded question marks for the query
		 */
		$placeholders = array_fill(0, count($columns), '?');

		/**
		 * Construct the query string
		 */
		$sql = "INSERT INTO " . $table . " (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";

		/**
		 * Create a statement for thge query
		 */
		$statement = parent::prepare($sql);

		/**
		 * Return the execution result
		 */
		return $statement->execute($values);
	}

	/**
	 * Selects a set of rows from a table
	 * @param  string  $table Table to perform the select on
	 * @param  array   $where where filter
	 * @param  array   $joins cross joins
	 * @param  int     $limit how many rows should be returned
	 * @param  array   $order array of column => DESC|ASC values.
	 * @return array          the returned results
	 */
	public function select($table, $where = array(), $joins = array(), $limit = null, $order = array())
	{
		/**
		 * This method is to be implemented, quite a complex operation and we should be focusing on
		 * supporting as much customization and flexibility without damaging performance in any way.
		 */
	}

	/**
	 * Update a table with a set of key/values and a possible where clause
	 * @param  string $table  tablwe to be effected
	 * @param  array  $values key/value pairs to be persisted
	 * @param  array  $where  string key/value pairs to be used to limit the modifications.
	 * @return boolean        returns true if the statement executed successfully
	 */
	public function update($table, $values, $where)
	{
	}

	/**
	 * Removes one or more rows from a table given the where clauses filter
	 * @param  string $table table to be effected
	 * @param  array  $where Where filter to be placed in the query
	 * @return boolean       returns true if the statement executed successfully
	 */
	public function delete($table, $where)
	{
	}

	/**
	 * Fetch all from a table
	 * @param  string $table table to select from
	 */
	public function all($table)
	{
		/**
		 * Create a new query
		 */
		$sql = "SELECT * FROM " . $table;

		/**
		 * Create a statement
		 */
		$statement = $this->prepare($sql);

		/**
		 * Return all rows
		 */
		return $statement->fetchAll();
	}


	/**
	 * Drops a table schema
	 * @param  string $table table to be dropped
	 * @return boolean       returns true if the statement executed successfully
	 */
	public function drop($table)
	{
		/**
		 * Create the drop query
		 */
		$sql = "DROP TABLE " . $table;

		/**
		 * Prepare the query
		 */
		$statement = $this->prepare($sql);

		/**
		 * Run the statement and return the result
		 */
		return $statement->execute();
	}

	/**
	 * Calls a stored procedure, as the return type is unknown we return the statement after execute.
	 * @param  string $procedure procedure function name
	 * @param  array  $params    arguments to be passed to the call
	 * @return PDOStatement      Returns a PDOStatement.
	 */
	public function call($procedure, $params = array())
	{
		/**
		 * Create a set of imploded question marks for the query.
		 */
		$placeholders = array_fill(0, count($params), '?');

		/**
		 * Create the query
		 */
		$sql = "CALL " . $procedure . " (" . implode(',', $placeholders) . ")";

		/**
		 * Prepare the query
		 */
		$statement = $this->prepare($sql);

		/**
		 * Execute the statement
		 */
		$statement->execute($params);

		/**
		 * Return the PDOStatment as we do not know what it does or what it returns.
		 */
		return $statement;
	}
}