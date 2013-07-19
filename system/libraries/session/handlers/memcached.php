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
 * Native Session Interface
 */
class Session_Library_Driver_Memcached extends Memcached implements SessionHandlerInterface
{
	/**
	 * Session prefix (filename prefix)
	 * @var string
	 */
	protected $prefix = 'sess_';

	/**
	 * Configuration Class
	 */
	protected $config;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		/**
		 * Fetch the configuration class
		 */
		$this->config = Registry::get('ConfigLoader')->session->driver_memcached;

		/**
		 * Construct the parent object with the persistent id
		 */
		parent::__construct($this->config['persistent_id']);

		/**
		 * Check to see if we need to add servers to the scope
		 */
		if(!empty($this->config['servers']))
		{
			foreach ($this->config['servers'] as $host => $port)
			{
				$this->addServer($host, $port);
			}
		}
	}

	/**
	 * Close a session pointer
	 * @return boolean returns true if session closed successfully
	 */
	public function close()
	{
		return true;
	}

	/**
	 * Destroys a session
	 * @param  string $id Session Identifier
	 * @return boolean    returns true if the session was destroyed.
	 */
	public function destroy($id)
	{
		return $this->delete($this->prefix . $id);
	}

	/**
	 * Garbage Cleaner
	 * @param  int $maxlifetime clears sessions within this lifetime
	 * @return bool             Returns true if the garbage collection was successful.
	 */
	public function gc($maxlifetime)
	{
		return true;
	}

	/**
	 * Opens the session for reading and writing.
	 * @param  string $savepath path to save the session
	 * @param  string $name     the session name
	 * @return boolean          returns true on success, false on failure.
	 */
	public function open($savepath, $name)
	{
		return true;
	}

	/**
	 * Reads a value from a session
	 * @param  string $id Session identifier
	 * @return string     Value stored, null if no value exists.
	 */
	public function read($id)
	{
		return $this->get($this->prefix . $id) ? : '';
	}

	/**
	 * Set value to the session store
	 * @param  string $id   the id that represents the value being stored
	 * @param  string $data data to be stored
	 * @return boolean      returns true if the value is stored, false otherwise
	 */
	public function write($id, $data)
	{
		$this->set($this->prefix . $id, $data, time() + $this->config->expiration);
	}
}