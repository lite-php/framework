<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
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
}