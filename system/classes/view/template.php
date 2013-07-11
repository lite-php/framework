<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
class Template
{
	/**
	 * An area to hold the data for in-view access.
	 */
	private $__DATA_;

	/**
	 * An area to hold the data for in-view access.
	 */
	private $__BASE_PATH_;

	/**
	 * @constructor - We compile here so we don't clog the view's namespace
	 */
	public function __construct($__base_path, $__template, $__data)
	{
		/**
		 * Set the base path
		 */
		$this->__BASE_PATH_ = $__base_path;

		/**
		 * Set the data object to the class scope
		 */
		$this->__DATA_ = $__data;

		/**
		 * Unset the local variables as we have passed it unto scope
		 */
		unset($__data);

		/**
		 * Create a new output buffer
		 */
		if(!ob_start(null))
		{
			throw new Exception("Unable to start buffering for view renderer.", 1);
		}

		/**
		 * No that we are buffering compile and render the data
		 */
		require $__base_path . '/' . $__template;

		/**
		 * Fetch the content from the buffer
		 */
		$content = ob_get_contents();

		/**
		 * Clean the buffer and turn it off.
		 */
		ob_end_clean();

		/**
		 * Send this data to the output class
		 */
		Registry::get('HTTPOutput')->send($content);
	}

	public function partial($partial, $data = array())
	{
		/**
		 * Check to see if the partial exists
		 */
		include $this->__BASE_PATH_ . '/partials/' . $partial . '.php';
	}

	public function bold($string)
	{
		echo '<strong>'. $string .'<strong>';
	}
}