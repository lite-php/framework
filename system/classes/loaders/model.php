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
class ModelLoader extends BaseLoader
{
	/**
	 * Class suffix
	 * @var string
	 */
	protected $class_suffix = '_Model';

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
		if(empty($this->base))
		{
			$this->base = Registry::get('Application')->getResourceLocation('models');
		}

		/**
		 * Continue the process in the parent loader
		 */
		return parent::get($key);
	}
}