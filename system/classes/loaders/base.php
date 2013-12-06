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
 * This class loads objects from a directory and keeps an instance of them
 */
class BaseLoader
{
	/**
	 * Base Directory - Path where objects should be loaded
	 * @var string
	 */
	protected $base = array();

	/**
	 * Class prefix
	 * @var string
	 */
	protected $class_prefix = '';

	/**
	 * Class suffix
	 * @var string
	 */
	protected $class_suffix = '';

	/**
	 * File Prefix
	 * @var string
	 */
	protected $file_prefix = '';

	/**
	 * File suffix
	 * @var string
	 */
	protected $file_suffix = '.php';

	/**
	 * Objects Loaded
	 * @var array
	 */
	protected $objects = array();

	/**
	 * does this loader require classes to be lowercased
	 * @var boolean
	 */
	protected $lowercase = true;

	/**
	 * Return a configuration
	 */
	public function get($key)
	{
		if($this->lowercase)
		{
			$key = strtolower($key);
		}

		/**
		 * Do we have this object in the objects store
		 */
		if(isset($this->objects[ $key ]))
		{
			return $this->objects[ $key ];
		}

		/**
		 * Require the object
		 */
		$filename = $this->getProcessedFilename($key);

		/**
		 * Attempt to lookup the file location
		 */
		$filelocation = $this->lookup($key);

		/**
		 * Compile the class name
		 */
		$class = $this->getProcessedClassname($key);

		/**
		 * If we do not have a location, then the file does not exist
		 */
		if($filelocation === false)
		{
			throw new Exception("Unable to load class (" . $class . "), file does not exists");
		}

		/**
		 * Load the file
		 */
		require_once $filelocation;

		/**
		 * Check to see if the class exists
		 */
		if(!class_exists($class))
		{
			throw new Exception("Unable to load class (" . $class . "), class is not defined");
		}

		/**
		 * Instatiate and store the class
		 */
		return $this->objects[ $key ] = new $class();
	}

	/**
	 * USed for getting and triggering the loading of an object
	 * @return object
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	public function lookup($key)
	{
		/**
		 * Compile the file name
		 */
		$filename = $this->getProcessedFilename($key);
		/**
		 * Attempt to discover the location of file
		 */
		foreach ($this->base as $basepath)
		{
			/**
			 * Create a pointer to the current file
			 */
			$file = $basepath . '/' . $filename;


			/**
			 * If it exists, return the path
			 */
			if(file_exists($file))
			{
				return $file;
			}
		}
		
		return false;
	}

	/**
	 * Checks to see if a file exists
	 */
	public function exists($key)
	{
		return $this->lookup($key) !== false;
	}

	/**
	 * Return an absolute/relative path for a given key.
	 * @param  string $key
	 * @return string
	 */
	protected function getProcessedFilename($key)
	{
		return $this->file_prefix . $key . $this->file_suffix;
	}

	/**
	 * Return a compiled class name for a given key
	 * @param  string $key
	 * @return string
	 */
	protected function getProcessedClassname($key)
	{
		return $this->class_prefix . $key . $this->class_suffix;
	}

	protected function addSearchPath($path)
	{
		/**
		 * Valdiate we have a path and that it does not exist
		 */
		if(!empty($path) && !in_array($path, $this->base))
		{
			array_push($this->base, $path);
		}
	}
}