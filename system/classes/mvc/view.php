<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
class View
{
	protected $data = array();

	/**
	 * Returns anentity from the data array.
	 */
	public function __get($key)
	{
		return $this->data[$key];
	}

	/**
	 * Sets an entity to the data array
	 */
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Returns true/false if a variable exists in the data array
	 */
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}

	public function render($view)
	{
		/**
		 * Get the application
		 */
		$application = Registry::get('Application');

		/**
		 * Get the application
		 */
		$Output = Registry::get('HTTPOutput');

		/**
		 * Check to see if the template exists
		 */
		if(!$application->viewExists($view))
		{
			throw new Exception("Unalbe to locate view file");
		}

		/**
		 * Instantiate a new Template object
		 * This Template will send via the HTTPOutput object
		 */
		new Template($application->getViewPath(), $view . '.php', $this->data);
	}
}