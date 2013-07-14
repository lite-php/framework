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
class LibraryLoader extends BaseLoader
{
	/**
	 * Class suffix
	 * @var string
	 */
	protected $class_suffix = '_Library';

	/**
	 * File suffix
	 * @var string
	 */
	protected $file_suffix = '.php';

	/**
	 * Override the gets method and set the base if required
	 * @param  string $key
	 * @return object
	 */
	public function get($key)
	{
		if(empty($this->base))
		{
			$this->base = Registry::get('Application')->getResourceLocation('libraries');
		}

		/**
		 * Continue the process in the parent loader
		 */
		return parent::get($key);
	}

	/**
	 * Return an absolute/relative path for a given key.
	 * This function overrides the BaseLoader's method as libraries are stored in
	 * a subfolder.
	 * 
	 * @param  string $key
	 * @return string
	 */
	protected function getProcessedFilename($key)
	{
		return $this->base . '/' . $key . '/' . $this->file_prefix . $key . $this->file_suffix;
	}
}