<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Route Class
 */
class Route
{
	/**
	 * @type HTTPInput Input Interface
	 */
	public $input;

	/**
	 * @type HTTPOutput Output interface
	 */
	public $output;

	/**
	 * Controller Value
	 */
	protected $controller = 'index';

	/**
	 * Controller Method
	 */
	protected $method = 'index';

	/**
	 * Arguments
	 */
	protected $arguments = array();

	/**
	 * @cosntructor
	 */
	public function __construct()
	{
		/**
		 * Fetch the input object from the registry
		 */
		$this->input = Registry::get('HTTPInput');

		/**
		 * Fetch the output object from the registry
		 */
		$this->output = Registry::get('HTTPOutput');

		/**
		 * Detect the route from the input
		 */
		$this->_parse();
	}

	private function _parse()
	{
		/**
		 * Fetch the query string from the request
		 */
		$query = $this->input->getQueryString();

		/**
		 * Parse the query string
		 */
		$segments = explode('/', trim($query, '/'));

		/**
		 * Parse the route
		 * /[controller,[method,[arg1,[arg2, [....]]]]]
		 */
		if(!empty($segments[0]))
		{
			/**
			 * We should validate charactors here
			 */
			$this->controller = $segments[0];

			/**
			 * Check to see if we have a method
			 */
			if(!empty($segments[1]))
			{
				/**
				 * Again, we should validate chars but also varifiy against magic methods.
				 */
				$this->method = $segments[1];

				/**
				 * We now can slice hte
				 */
				if(count($segments) > 2)
				{
					$this->arguments = array_slice($segments, 2);
				}
			}
		}
	}

	/**
	 * Returns the controller name
	 */
	public function getController()
	{
		return $this->controller;
	}

	/**
	 * Returns the method name
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Returns the arguments
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * Returns an argument at a specific index, otherwise a defualt value
	 */
	public function getArgumentsAt($position, $default = null)
	{
		return !empty($this->arguments[$position -1]) ? $this->arguments[$position -1] : $default;
	}
}