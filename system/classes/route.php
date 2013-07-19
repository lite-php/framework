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
 * Route Class
 */
class Route
{
	/**
	 * Input Object for interfacing with the input
	 * @type HTTPInput Input Interface
	 */
	public $input;

	/**
	 * Output Object for interfacing with the output
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
	 * Initialize a new Route object and setting I/O Objects.
	 * @constructor
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

	/**
	 * Analyses various values to detect requested controller and method.
	 * @return [type]
	 */
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