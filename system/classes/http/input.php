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
			if(getenv($index))
			{
				$this->ip = getenv($index);
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
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * A simple check to see if the request is an XHR Request
	 * @return boolean
	 */
	public function isAjaxRequest()
	{
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	/**
	 * Retrieve a variable that has been psased by the post method
	 * @param  string $key
	 * @return string
	 */
	public function post($key)
	{
		/**
		 * Return the post value
		 */
		return !empty($_POST) && isset($_POST[$key]) ? $_POST[$key] : null;
	}

	/**
	 * Retrive a variable passed in the get params
	 * @param  string $key
	 * @return string
	 */
	public function get($key)
	{
		/**
		 * Return the get variable
		 */
		return !empty($_GET) && isset($_POST[$key]) ? $_GET[$key] : null;
	}

	/**
	 * Retrive a cookie value passed via the header
	 * @param  string $key
	 * @return string
	 */
	public function cookie($key)
	{
		/**
		 * Return the get variable
		 */
		return !empty($_COOKIE) && isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
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