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
	 * Request Body
	 * @var String
	 */
	protected $body;

	/**
	 * Parsed request (query string from body)
	 * @var Array
	 */
	protected $body_parsed;

	/**
	 * Initiates a new Input Object
	 */
	public function __construct()
	{
		/**
		 * Detect the clients IP address
		 */
		$this->ip = $this->_detectIP();
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
	 * Return the request origin for this request
	 * @return string
	 */
	public function getOrigin()
	{
		return $this->server('HTTP_ORIGIN');
	}

	/**
	 * A simple check to see if the request is an XHR Request
	 * @return boolean
	 */
	public function isAjax()
	{
		return strtolower($this->server('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest';
	}

	/**
	 * Check if the request method is of type GET
	 * @return boolean
	 */
	public function isGet()
	{
		return 'GET' === $this->getRequestMethod();
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
	 * Get the scheme for the current schema.
	 * @return String Current request scheme.
	 */
	public function getScheme()
	{
		return HTTP_PROTOCOL == 'https://' ? "http" : "https";
	}

	/**
	 * FReturn the extension of teh current URI if exists
	 */
	public function getExtension()
	{
		throw new Exception("HTTPInput::getExtension not yet implemented");
	}

	/**
	 * Return the referrer
	 * @return String Referer string or Null
	 */
	public function getReferrer()
	{
		return $this->server('HTTP_REFERER');
	}

	/**
	 * Return the user agent strinmg
	 * @return String Useragent
	 */
	public function getUserAgent()
	{
		return $this->server('HTTP_USER_AGENT');
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
	public function get($key, $filters = FILTER_DEFAULT, $options = null)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_GET, $key, $filters, $options);
	}

	/**
	 * Retrive a value passed in the post params
	 * @param  string $key
	 * @return string|null
	 */
	public function post($key, $filters = FILTER_DEFAULT, $options = null)
	{
		/**
		 * Return the post value
		 */
		return $this->_filtered_input(INPUT_POST, $key, $filters, $options);
	}

	/**
	 * Retrive a value passed in the cookie header
	 * @param  string 	$key
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string|null
	 */
	public function cookie($key, $filters = FILTER_DEFAULT, $options = null)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_COOKIE, $key, $filters, $options);
	}

	/**
	 * Retrive a value from the server object
	 * @param  string 	$key
	 * @param  int 		$filters the bitmask for filers to use, see FILTER_*
	 * @return string|null
	 */
	public function server($key, $filters = FILTER_DEFAULT, $options = null)
	{
		/**
		 * Return the get variable
		 */
		return $this->_filtered_input(INPUT_SERVER, strtoupper($key), $filters, $options);
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
		return filter_input($type, $key, $filters, $options);
	}

	/**
	 * Return the PHP INput stream.
	 * @return {Resource|String}
	 */
	public function getContents($asResource = false)
	{
		if($asResource)
		{
			return fopen("php://input", "rb");
		}

		return $this->body ? $this->body : ($this->body = file_get_contents('php://input'));
	}

	/**
	 * Check to see if the request has a JSON Payload
	 * @return boolean
	 */
	public function hasJson()
	{
		return strstr($this->server('content_type'), '/json') == '/json';
	}

	/**
	 * Return the JSON Object that was part of the request body
	 * @return Object Decoded JSON Body
 	 */
	public function json()
	{
		return $this->hasJson() ? json_decode($this->getContents(false), true) : null;
	}

	/**
	 * This method returns the parsed content
	 * @return {*}
	 */
	public function body()
	{
		/**
		 * Return JSON Object if we have one.
		 */
		if($this->hasJson())
		{
			return $this->json();
		}

		/**
		 * Return a parsed query string
		 */
		if(strstr($this->server('content_type'), 'x-www-form-urlencoded'))
		{
			if($this->body_parsed)
			{
				return $this->body_parsed;
			}

			parse_str($this->getContents(), $this->body_parsed);
			return $this->body_parsed;
		}

		/**
		 * Plain string.
		 */
		return $this->getContents();
	}

	/**
	 * Set a cookie
	 * @todo Change this to appy the defaults from the configuration files
	 */
	public function setCookie($name, $value = '', $expire = 86500, $domain = '', $path = '/', $prefix = '', $secure = false, $httponly = false)
	{
		return setcookie($name, $value, time() + $expire, $path, $domain, $secure, $httponly);
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

	private function _detectIP()
	{
		foreach ($this->_ip_search as $index)
		{
			if($this->env($index, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6))
			{
				$this->ip = $this->env($index);
				break;
			}
		}

		return $this->ip;
	}
}