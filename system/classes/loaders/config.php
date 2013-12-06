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
class ConfigLoader extends BaseLoader
{
	/**
	 * Class suffix
	 * @var string
	 */
	protected $class_suffix = '_Config';

	/**
	 * File suffix
	 * @var string
	 */
	protected $file_suffix = '.php';

	/**
	 * Override the get method and set the base if required
	 * @param  string $key
	 * @return object
	 */
	public function get($key)
	{
		/**
		 * Add the search path the application
		 */
		$this->addSearchPath(Registry::get('Application')->getResourceLocation('configs'));
		

		/**
		 * Continue the process in the parent loader
		 */
		return parent::get($key);
	}
}