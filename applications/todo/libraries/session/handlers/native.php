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
class Session_Library_Driver_Native implements SessionHandlerInterface
{
	protected $savepath;
	/**
	 * Constructor
	 */
	public function __construct()
	{
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
		/**
		 * Get the file path
		 * @var string
		 */
		$file = $this->savepath . '/sess_' . $id;

		/**
		 * Test to see if the file exists
		 */
        if(file_exists($file))
        {
			return unlink($file);
        }

        return false;
	}

	/**
	 * Garbage Cleaner
	 * @param  int $maxlifetime clears sessions within this lifetime
	 * @return bool             Returns true if the garbage collection was successful.
	 */
	public function gc($maxlifetime)
	{
       foreach (glob($this->savepath . '/sess_*') as $file)
       {
            if (filemtime($file) + $maxlifetime < time())
            {
                unlink($file);
            }
        }

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
        $this->savepath = $savepath;

        if (!is_dir($this->savepath))
        {
			return mkdir($this->savepath, 0777);
        }

        return true;
	}

	/**
	 * Reads a value from a session
	 * @param  string $id Session identifier
	 * @return string     Value stored, null if no value exists.
	 */
	public function read($id)
	{
		/**
		 * Create file path
		 */
		$file  = $this->savepath . '/sess_' . $id;

		/**
		 * Make sure the file exists
		 */
		if(!file_exists($file))
		{
			return false;
		}

		return file_get_contents($file);
	}

	/**
	 * Set value to the session store
	 * @param  string $id   the id that represents the value being stored
	 * @param  string $data data to be stored
	 * @return boolean      returns true if the value is stored, false otherwise
	 */
	public function write($id, $data)
	{
		return file_put_contents("$this->savepath/sess_$id", $data) === false ? false : true;
	}
}