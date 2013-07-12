<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * This class loads objects from a directory and keeps an instance of them
 */
class Loader
{
	/**
	 * Base Directory - Path where objects should be loaded
	 * @var string
	 */
	protected $base = '';

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
	 * USed for getting and triggering the loading of an object
	 * @return object
	 */
	public function __get($key)
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
		 * does the file exists
		 */
		if(!$this->exists($key))
		{
			throw new Exception("Unable to load class (" . $key . "), file does not exists");
		}

		/**
		 * Require the object
		 */
		require_once $this->getProcessedFilename($key);

		/**
		 * Compile the class name
		 */
		$class = $this->getProcessedClassname($key);

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
	 * Checks to see if a file exists
	 */
	protected function exists($key)
	{
		if(!$this->base)
		{
			throw new Exception("Base class needs to be set by the parent object");
		}

		/**
		 * Compile the file name
		 */
		$filename = $this->getProcessedFilename($key);

		/**
		 * Return true / false on weither the file exists
		 */
		return file_exists($filename);
	}

	/**
	 * Return an absolute/relative path for a given key.
	 * @param  string $key
	 * @return string
	 */
	protected function getProcessedFilename($key)
	{
		return $this->base . '/' . $this->file_prefix . $key . $this->file_suffix;
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
}