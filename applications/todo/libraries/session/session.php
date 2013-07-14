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
class Session_Library
{
	/**
	 * Configuration Object
	 * @var object
	 */
	protected $_config;

	/**
	 * Session Handler Interface
	 * @var SessionHandlerInterface
	 */
	protected $_handler;

	/**
	 * Session Constructor
	 */
	public function __construct()
	{
		/**
		 * Fetch the session configuration
		 */
		$this->_config = Registry::get('ConfigLoader')->session;

		/**
		 * Attempt to load the session
		 */
		if(!$this->_config->handler)
		{
			throw new Exception("A session driver is required within the confio/session.php");
		}

		/**
		 * Check that the driver exists
		 */
		if(!file_exists(__DIR__ . '/handlers/' . $this->_config->handler . '.php'))
		{
			throw new Exception("Unknown session driver: " . $this->_config->handler);
		}

		/**
		 * Load the driver
		 */
		require_once __DIR__ . '/handlers/' . $this->_config->handler . '.php';

		/**
		 * Generate the class name
		 */
		$class = 'Session_Library_Driver_' . $this->_config->handler;

		/**
		 * Check to see if the class exists
		 */
		if(!class_exists($class))
		{
			throw new Exception("Unable to load session, class name malformed: " . $this->_config->handler);
		}

		/**
		 * Instiatiate the handler
		 * @var SessionHandlerInterface
		 */
		$this->_handler = new $class();

		/**
		 * Set the PHP session Save Handler
		 */
		session_set_save_handler($this->_handler);

		/**
		 * Set the cache expire value
		 */
		if(isset($this->_config->expiration))
		{
			session_cache_expire((int)$this->_config->expiration);
		}

		/**
		 * Configure the session save path.
		 */
		if(!empty($this->_config->savepath))
		{
			session_save_path($this->_config->savepath);
		}

		/**
		 * Regenerate the session id if enabled
		 */
		if($this->_config->regenerate)
		{
			session_regenerate_id($this->_config->delete_old_session);
		}

		/**
		 * Set the session name.
		 */
		if(!empty($this->_config->name))
		{
			session_name($this->_config->name);
		}

		/**
		 * Start the session
		 */
		session_start($this->_config->name);
	}

	/**
	 * Set a value in the session
	 * @param string $key     Index used to store the value.
	 * @param    *   $value   A serializable value to be stored.
	 */
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Get a value from the session store
	 * @param  strint $key     the index of the stored value
	 * @return *
	 */
	public function get($key)
	{
		if($this->exists($key))
		{
			return $_SESSION[$key];
		}
	}

	/**
	 * Get a value from the session store
	 * @param  strint $key     the index of the stored value
	 * @return *
	 */
	public function exists($key)
	{
		return array_key_exists($key, $_SESSION);
	}

	/**
	 * Returna value using the magic __get call
	 * @param  string $key Index used to get the value
	 * @return *
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * Set a value using the __set call
	 * @param string $key   Index used to store the value
	 * @param * $value
	 */
	public function __set($key, $value)
	{
		return $this->set($key, $value);
	}

	/**
	 * check if a value is set using the magic __isset call
	 * @param string $key   Index used to store the value
	 * @param * $value
	 */
	public function __isset($key)
	{
		return $this->exists($key);
	}
}