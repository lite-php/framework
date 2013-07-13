<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * HTTP Input Class
 */
class HTTPInput
{
	/**
	 * Envoroment keys in order of lookup to determain the clients ip.
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
	 * @cosntructor
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
	 * Check to see if there has been a POST request
	 * @return boolean
	 */
	public function isHead()
	{
		return $_SERVER['REQUEST_METHOD'] == 'HEAD';
	}

	/**
	 * Check to see if there has been a POST request
	 * @return boolean
	 */
	public function isPost()
	{
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	/**
	 * Check to see if there has been a POST request
	 * @return boolean
	 */
	public function isPut()
	{
		return $_SERVER['REQUEST_METHOD'] == 'PUT';
	}

	/**
	 * Check to see if the request was an ajax request
	 * @return boolean
	 */
	public function isAjax()
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
	 * Returns the IP for the current requester
	 */
	public function ip()
	{
		return $this->ip;
	}

	/**
	 * Returns the time the request hit the server
	 * @return flaot
	 */
	public function requestTimeFloat()
	{
		return $_SERVER['REQUEST_TIME'];
	}

	/**
	 * Return the request URI
	 */
	public function getScriptName()
	{
		return$_SERVER['SCRIPT_NAME'];
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
}