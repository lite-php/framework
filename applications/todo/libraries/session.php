<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
class Session_Library
{
	/**
	 * Session Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Set a value in the session
	 * @param string $key     Index used to store the value.
	 * @param    *   $value   A serializable value to be stored.s
	 */
	public function set($key, $value)
	{
	}

	/**
	 * Get a value from the session store
	 * @param  strint $key     the index of the stored value
	 * @return *
	 */
	public function get($key)
	{
	}

	/**
	 * Get a value from the session store
	 * @param  strint $key     the index of the stored value
	 * @return *
	 */
	public function exists($key)
	{
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