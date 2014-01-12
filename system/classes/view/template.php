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
	 * @var String
	 */
	private $__DATA_;

	/**
	 * An area to hold the data for in-view access.
	 * @var String
	 */
	private $__BASE_PATH_;

	/**
	 * We compile here so we don't clog the view's namespace
	 * @param String  	$__base_path Templates root path
	 * @param String  	$__template  Main template file
	 * @param Array  	$__data      Data to be exposed within view
	 * @param Function 	$callback    Callback to be called with compiled data, if ommited data is sent to the output
	 */
	public function __construct($__base_path, $__template, $__data, $callback = null)
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
		if($callback !== null)
		{
			return $callback($content);
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

	/**
	 * Encode a string into it's entities
	 * @param  String 	$data     Content to be encoded
	 * @param  Int 		$mode     ENT_* Mode to use when encoding
	 * @param  string 	$encoding Encoding to be used
	 * @return String             Encoded content
	 */
	public function encode($data, $mode = ENT_QUOTES, $encoding = "UTF-8")
	{
		return htmlentities($data, $mode, $encoding);
	}

	/**
	 * Encode data into it's entities and also encoding special chars.
	 * @param  String $value Content to be encoded
	 * @return String        Encoded content
	 */
	public function encodeSpecialChars($value)
	{
		return htmlspecialchars($value);
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
		$url = BASE_PATH . ($controller ? '/' . $controller : '') . ($controller && $method ? '/' . $method : '');

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
	 * Create a absolute link to the base url
	 */
	public function link()
	{
		return BASE_URL . call_user_func_array(array($this, 'route'), (array)func_get_args());
	}

	/**
	 * helper function for getting csrf tokens from the csrf library
	 *
	 * @throws Exception If csrf library is not available
	 */
	public function csrf()
	{
		/**
		 * Return the html block that is used for the forms
		 */
		return Registry::get('LibraryLoader')->csrf->html();
	}

	/**
	 * helper function for getting csrf tokens from the csrf library
	 *
	 * @throws Exception If csrf library is not available
	 */
	public function csrfToken()
	{
		/**
		 * Return the html block that is used for the forms
		 */
		return Registry::get('LibraryLoader')->csrf->token();
	}

	/**
	 * Fetch a value for GET/POST and return its encoded value
	 * @param  String  $key      key used for element
	 * @return String            Encoded GET/POST Value
	 */
	public function formValue($key)
	{
		/**
		 * Fetch the input class
		 */
		$input = Registry::get('Input');

		/**
		 * Check for post, then get
		 */
		if($input->isPost())
		{
			return htmlspecialchars($input->post($key));
		}

		return htmlspecialchars($input->get($key));
	}

	/**
	 * Helper to detect the controller/action on the current page
	 * @param  String  $controller Controller key
	 * @param  String  $method     Method to check as well, optional.
	 * @return boolean             [description]
	 */
	public function isRoute($controller, $method = null)
	{
		$Route = Registry::get('Route');

		/**
		 * Validate controller only
		 */
		if($method === null)
		{
			return $Route->getController() == $controller;
		}

		/**
		 * Validate both controller and method matches
		 */
		return $Route->getController() == $controller && $Route->getmethod() == $method;
	}
}