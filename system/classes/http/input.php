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
	 * Return the request URI
	 * @return string|null 	the current requested URI
	 */
	public function getRequestURI()
	{
		return $this->server('REQUEST_URI');
	}

	/**
	 * Return the request URI
	 * @return string|null 	the current query string
	 */
	public function getQueryString()
	{
		return $this->server('QUERY_STRING');
	}

	/**
	 * Retrive a value passed in the get params
	 * @param  string $key
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string|null
	 */
	public function get($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_GET, $key, $filters);
	}

	/**
	 * Retrive a value passed in the post params
	 * @param  string $key
	 * @return string|null
	 */
	public function post($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the post value
		 */
		return $this->_filtered_input(INPUT_POST, $key, $filters);
	}

	/**
	 * Retrive a value passed in the cookie header
	 * @param  string 	$key
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string|null
	 */
	public function cookie($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_COOKIE, $key, $filters);
	}

	/**
	 * Retrive a value from the server object
	 * @param  string 	$key
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string|null
	 */
	public function server($key, $filters = FILTER_DEFAULT)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_SERVER, $key, $filters);
	}

	/**
	 * Retrive a enviroment value
	 * @param  string 	$key
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string|null
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
	public function _filtered_input($type, $key, $filters = FILTER_DEFAULT, $options = array())
	{
		return filter_input($type, $key, $filters);
	}

	/**
	 * Returns the IP for the current requester
	 * @return string Ip address that made this request, even behind proxies.
	 */
	public function ip()
	{
		return $this->ip;
	}

	/**
	 * Checks to see if the current connecton is secure (SSL)
	 * @return boolean whether the connection is secure.
	 */
	public function isSecure()
	{
		return (!empty($_SERVER['HTTPS']) && $this->server('HTTPS') !== 'off')
			|| $this->server('SERVER_PORT', FILTER_VALIDATE_INT) == 443;
	}

	/**
	 * Returns true|false depending on if the connection is local.
	 * @return boolean true|false
	 */
	public function isLocal()
	{
		return !$this->_filtered_input($this->ip(), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
	}
}