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
	 * Connection state
	 * @var boolean
	 */
	protected $connected = false;

	/**
	 * Constructor for Database Object
	 */
	public function __construct()
	{
	}

	/**
	 * Connects to the database if not already connected
	 * @return boolean
	 */
	public function connect()
	{
		if($this->connected === true)
		{
			return true;
		}

		/**
		 * Fetch the configuration object from the registry
		 */
		$config = Registry::get('Application')->getConfiguration('database');

		/**
		 * Call the parent constructor
		 */
		parent::__construct($config->dsn, $config->username, $config->password, $config->driver_options);
	}

	/**
	 * Returns the connection state
	 * @return boolean
	 */
	public function isConnected()
	{
		return $this->connected;
	}

	/**
	 * Initiates a transaction
	 * @return boolean
	 */
	public function beginTransaction()
	{
		/**
		 * Assure we are connected
		 */
		$this->connect();

		/**
		 * Call the parent method
		 */
		return parent::beginTransaction();
	}

	/**
	 * Commits a transaction
	 * @return boolean
	 */
	public function commit()
	{
		/**
		 * Call the parent method
		 */
		return parent::commit();
	}

	/**
	 * Fetch the SQLSTATE associated with the last operation on the database handle
	 * @return int|null
	 */
	public function errorCode()
	{
		/**
		 * Call the parent method
		 */
		return parent::errorCode();

	}

	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 * @return array
	 */
	public function errorInfo()
	{
		/**
		 * Call the parent method
		 */
		return parent::errorInfo();
	}

	/**
	 * Execute an SQL statement and return the number of affected rows
	 * @param  string $statement
	 * @return int|boolean
	 */
	public function exec($statement)
	{
		/**
		 * Assure we are connected
		 */
		$this->connect();

		/**
		 * Call the parent method
		 */
		return parent::exec($statement);
	}

	/**
	 * Retrieve a database connection attribute
	 * @param  int $attribute
	 * @return int
	 */
	public function getAttribute($attribute)
	{
		/**
		 * Call the parent method
		 */
		return parent::getAttribute($attribute);
	}

	/**
	 * Return an array of available PDO drivers
	 * @return array
	 */
	public static function getAvailableDrivers()
	{
		/**
		 * Call the parent method
		 */
		return parent::getAvailableDrivers();
	}

	/**
	 * Checks if inside a transaction
	 * @return true
	 */
	public function inTransaction()
	{
		/**
		 * Call the parent method
		 */
		return parent::inTransaction();
	}

	/**
	 * Returns the ID of the last inserted row or sequence value
	 * @param  string $name
	 * @return string
	 */
	public function lastInsertId($name = null)
	{
		/**
		 * Call the parent method
		 */
		return parent::lastInsertId($name);
	}

	/**
	 * Prepares a statement for execution and returns a statement object
	 * @param  string $statement
	 * @param  array  $driver_options
	 * @return PDOStatement|boolean
	 */
	public function prepare($statement, $driver_options = array())
	{
		/**
		 * Assure we are connected
		 */
		$this->connect();

		/**
		 * Call the parent method
		 */
		return parent::prepare($statement, $driver_options);
	}

	/**
	 * Executes an SQL statement, returning a result set as a PDOStatement object
	 * Note: Even though this class is documentated to have overloads, you should configure
	 * those overloads on the returned PDOStatement
	 * @param  string $statement
	 * @return PDOStatement|boolean
	 */
	public function query($statement)
	{
		/**
		 * Assure we are connected
		 */
		$this->connect();

		/**
		 * Call the parent method
		 */
		return parent::query($statement);
	}

	/**
	 * Quotes a string for use in a query.
	 * @param  string $string
	 * @param  int $parameter_type
	 * @return string|false
	 */
	public function quote($string, $parameter_type = PDO::PARAM_STR)
	{
		/**
		 * Assure we are connected
		 */
		$this->connect();

		/**
		 * Call the parent method
		 */
		return parent::quote($string, $parameter_type);
	}

	/**
	 * Rolls back a transaction
	 * @return boolean
	 */
	public function rollBack()
	{
		/**
		 * Call the parent method
		 */
		return parent::rollBack();
	}

	/**
	 * Set an attribute
	 * @param int $attribute
	 * @param  *  $value
	 * @return boolean
	 */
	public function setAttribute($attribute, $value)
	{
		$this->connect();
		/**
		 * Call the parent method
		 */
		return parent::setAttribute($attribute, $value);
	}
}