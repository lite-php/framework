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
	 * Regular Expression to validate class and function names.
	 * @var string Regular Expression validate both class name and method names.
	 */
	public $construct_regex = '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

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
		if(IS_CLI)
		{
			/**
			 * Configure route for a CLI Application
			 */
			$this->_parse_cli();
			return;
		}
		/**
		 * Fetch the input object from the registry
		 */
		$this->input = Registry::get('Input');

		/**
		 * Fetch the output object from the registry
		 */
		$this->output = Registry::get('Output');

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
		$uri = $this->input->getRequesturi();

		/**
		 * We are only interested in the path
		 */
		$path = explode("?", $uri);

		/**
		 * Parse the query string
		 */
		$segments = explode('/', trim($path[0], '/'));

		/**
		 * Parse the route
		 * /[controller,[method,[arg1,[arg2, [....]]]]]
		 */
		if(!empty($segments[0]))
		{
			/**
			 * Validate that the class name is valid, this also prevents directory traversal.
			 */
			if(!preg_match($this->construct_regex, $segments[0]))
			{
				throw new Exception("Invalid charators detecting in the route.");
			}

			/**
			 * Set the controller value
			 * @var string
			 */
			$this->controller = $segments[0];

			/**
			 * Check to see if we have a method
			 */
			if(!empty($segments[1]))
			{
				/**
				 * Validate that the method name is valid, this also prevents directory traversal.
				 */
				if(!preg_match($this->construct_regex, $segments[1]))
				{
					throw new Exception("Invalid charators detecting in the route.");
				}

				/**
				 * Set the method name to the local scope
				 * @var string
				 */
				$this->method = $segments[1];

				/**
				 * Slice the rest of hte arguments and set them as arguments for route.
				 */
				if(count($segments) > 2)
				{
					$this->arguments = array_map(array($this, '_decodeParam'), array_slice($segments, 2));
				}
			}
		}
	}

	public function _parse_cli()
	{
		/**
		 * Get the CLIParser object from the registry
		 */
		$parser = Registry::get("CLIParser");

		/**
		 * First argument is the controller
		 */
		if($parser->getPositional(0))
		{
			$this->controller = $parser->getPositional(0);

			/**
			 * Method
			 */
			if($parser->getPositional(1))
			{
				$this->method = $parser->getPositional(1);
			}

			/**
			 * Get a clone fo the arguments to pass to to the handler
			 * With the cli its hard to have control over the argument order
			 * so we wass teh assoc array to the first argument.
			 */
			$this->arguments = array_slice($parser->getPositionals(), 2);
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
	 * Returns the controller name
	 */
	public function setController($controller)
	{
		return $this->controller = $controller;
	}

	/**
	 * Returns the method name
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the method
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}

	/**
	 * Returns the arguments
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * Set a arguments value at a specific position
	 * @param int $index position, not zero based
	 * @param string $value Value to be assigned
	 */
	public function setArgument($index, $value)
	{
		$this->arguments[$index - 1] = $value;
	}

	/**
	 * Returns an argument at a specific index, otherwise a defualt value
	 */
	public function getArgumentsAt($position, $default = null)
	{
		return !empty($this->arguments[$position -1]) ? $this->arguments[$position -1] : $default;
	}

	/**
	 * Shift the arguments for rerouting
	 */
	public function shiftArguments()
	{
		return array_shift($this->arguments);
	}

	public function unshiftArguments($value)
	{
		return array_unshift($this->arguments, $value);
	}

	/**
	 * Decodes a parameter passed via the query string
	 * @param  string $param the segment to decode
	 * @return string        the decoded segment
	 */
	private function _decodeParam($param)
	{
		return urldecode($param);
	}
}