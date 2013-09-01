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
 * Template Class
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
	 * We compile here so we don't clog the view's namespace
	 */
	public function __construct($__base_path, $__template, $__data, $callback = false)
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
		 * If we are returning we need to return by reference
		 */
		if($callback !== false)
		{
			$callback($content);
			return;
		}
		
		/**
		 * Send this data to the output class
		 */
		Registry::get('Output')->send($content);
	}

	/**
	 * Returns a variable from the data stack
	 * @param  string $param
	 * @return *
	 */
	public function __get($param)
	{
		return isset($this->__DATA_[$param]) ? $this->__DATA_[$param] : null;
	}

	/**
	 * Returns true/false depending on if a template param has been set
	 * @param  string  $param
	 * @return boolean
	 */
	public function __isset($param)
	{
		return isset($this->__DATA_[$param]);
	}

	public function encode($data, $mode = ENT_QUOTES, $encoding = "UTF-8")
	{
		return htmlentities($data, $mode, $encoding);
	}

	/**
	 * Requires a partial template file and renders in place.
	 * @param  string $partial
	 * @param  array  $data
	 */
	public function partial($partial, $data = array())
	{
		/**
		 * Check to see if the partial exists
		 */
		include $this->__BASE_PATH_ . '/partials/' . $partial . '.php';
	}

	/**
	 * Creates a link relative to the base path of the application
	 */
	public function route($controller = null, $method = null)
	{
		/**
		 * Create a url that is set the the base path
		 */
		$url = BASE_URL . ($controller ? '/' . $controller : '') . ($controller && $method ? '/' . $method : '');

		/**
		 * Loop the arguments
		 */
		for($offset = 2; $offset < func_num_args(); $offset++)
		{
			$url .= '/' . urlencode(func_get_arg($offset));
		}

		return $url;
	}

	/**
	 * Create a relative link to the base url
	 */
	public function link($path)
	{
		return BASE_URL . '/' . ltrim($path, '/');
	}

	/**
	 * Helper to detect the controller/action on the current page
	 */
	public function isRoute($controller, $method = false)
	{
		$Route = Registry::get('Route');

		/**
		 * Validate controller only
		 */
		if($method == false)
		{
			return $Route->getController() == $controller;
		}

		return $Route->getController() == $controller && $Route->getmethod() == $method;
	}
}