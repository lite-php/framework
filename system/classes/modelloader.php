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
class ModelLoader extends Loader
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
	 * Override the __get method and set the base if required
	 * @param  string $key
	 * @return object
	 */
	public function __get($key)
	{
		if(empty($this->base))
		{
			$this->base = Registry::get('Application')->getModelsPath();
		}

		/**
		 * Continue the process in the parent loader
		 */
		return parent::__get($key);
	}
}