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
 * HTTP Input Class
 *
 * This class tries to encapsulates all the input components of the PHP, this inlcudes
 * User Input, Browser Input such as Cokies, 
 */
class HTTPInput
{
	/**
	 * Envoroment keys in order of lookup to determain the clients ip.
	 * @see http://stackoverflow.com/a/3003233/353790
	 * @var array
	 */
	private $_ip_search = array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
	);

	/**
	 * Client IP
	 * @var string
	 */
	protected $ip = '0.0.0.0';

	/**
	 * Initiates a new Input Object
	 */
	public function __construct()
	{
		/**
		 * Detect the clients IP address
		 */
		foreach ($this->_ip_search as $index)
		{
			if($this->env($index, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6))
			{
				$this->ip = $this->env($index);
				break;
			}
		}
	}

	/**
	 * Return the request method for this request
	 * @return string Uppercase version of the the request method.
	 */
	public function getRequestMethod()
	{
		return strtoupper($this->server('REQUEST_METHOD'));
	}

	/**
	 * A simple check to see if the request is an XHR Request
	 * @return boolean
	 */
	public function isAjax()
	{
		return strtolower($this->server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
	}

	/**
	 * Detect if a form has been submitted to this page.
	 * @return boolean if there has been a post request.
	 */
	public function isPost()
	{
		return 'POST' === $this->getRequestMethod();
	}

	/**
	 * Retrieve a variable that has been psased by the post method
	 * @param  string $key
	 * @return string
	 */
	public function post($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the post value
		 */
		return $this->_filtered_input(INPUT_POST, $key, $filters);
	}

	/**
	 * Retrive a variable passed in the get params
	 * @param  string $key
	 * @param int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string
	 */
	public function get($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_GET, $key, $filters);
	}

	/**
	 * Retrive a cookie value passed via the header
	 * @param string 	$key
	 * @param int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string
	 */
	public function cookie($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_COOKIE, $key, $filters);
	}

	/**
	 * Retrive a server value
	 * @param  string $key
	 * @param int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string
	 */
	public function server($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_SERVER, $key, $filters | FILTER_NULL_ON_FAILURE);
	}

	/**
	 * Retrive a enviroment value
	 * @param  string $key
	 * @param int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string
	 */
	public function env($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_ENV, $key, $filters | FILTER_NULL_ON_FAILURE);
	}

	/**
	 * Return a fitlered value
	 * @param  int 		$type  Filter types to search
	 * @param  string 	$key   key to look for in the input stac
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return *        Fitlered input, null if not found.
	 */
	public function _filtered_input($type, $key, $filters = FILTER_DEFAULT)
	{
		return filter_input($type, $key, $filters);
	}

	/**
	 * Return the request URI
	 */
	public function getRequestURI()
	{
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Return the request URI
	 */
	public function getQueryString()
	{
		return $_SERVER['QUERY_STRING'];
	}

	/**
	 * Returns the IP for the current requester
	 * @return string Best matched IP Address for the current request.
	 */
	public function ip()
	{
		return $this->ip;
	}
}