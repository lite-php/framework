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
 * HTTP Output Class
 */
class HTTPOutput
{
	/**
	 * Headers container
	 */
	protected $headers = array(
		'Content-Type' => 'text/html'
	);

	/**
	 * List of status codes
	 * Source: Wikipedia "List_of_HTTP_status_codes"
	 * @var array
	 */
	protected $status_codes = array(
		100 => "Continue",
		101 => "Switching Protocols",
		102 => "Processing",
		200 => "OK",
		201 =>"Created",
		202 => "Accepted",
		203 => "Non-Authoritative Information",
		204 => "No Content",
		205 => "Reset Content",
		206 => "Partial Content",
		207 => "Multi-Status",
		300 => "Multiple Choices",
		301 => "Moved Permanently",
		302 => "Found",
		303 => "See Other",
		304 => "Not Modified",
		305 => "Use Proxy",
		306 => "(Unused)",
		307 => "Temporary Redirect",
		308 => "Permanent Redirect",
		400 => "Bad Request",
		401 => "Unauthorized",
		402 => "Payment Required",
		403 => "Forbidden",
		404 => "Not Found",
		405 => "Method Not Allowed",
		406 => "Not Acceptable",
		407 => "Proxy Authentication Required",
		408 => "Request Timeout",
		409 => "Conflict",
		410 => "Gone",
		411 => "Length Required",
		412 => "Precondition Failed",
		413 => "Request Entity Too Large",
		414 => "Request-URI Too Long",
		415 => "Unsupported Media Type",
		416 => "Requested Range Not Satisfiable",
		417 => "Expectation Failed",
		418 => "I'm a teapot",
		419 => "Authentication Timeout",
		420 => "Enhance Your Calm",
		422 => "Unprocessable Entity",
		423 => "Locked",
		424 => "Failed Dependency",
		424 => "Method Failure",
		425 => "Unordered Collection",
		426 => "Upgrade Required",
		428 => "Precondition Required",
		429 => "Too Many Requests",
		431 => "Request Header Fields Too Large",
		444 => "No Response",
		449 => "Retry With",
		450 => "Blocked by Windows Parental Controls",
		451 => "Unavailable For Legal Reasons",
		494 => "Request Header Too Large",
		495 => "Cert Error",
		496 => "No Cert",
		497 => "HTTP to HTTPS",
		499 => "Client Closed Request",
		500 => "Internal Server Error",
		501 => "Not Implemented",
		502 => "Bad Gateway",
		503 => "Service Unavailable",
		504 => "Gateway Timeout",
		505 => "HTTP Version Not Supported",
		506 => "Variant Also Negotiates",
		507 => "Insufficient Storage",
		508 => "Loop Detected",
		509 => "Bandwidth Limit Exceeded",
		510 => "Not Extended",
		511 => "Network Authentication Required",
		598 => "Network read timeout error",
		599 => "Network connect timeout error");

	/**
	 * Initializes a new Output Object
	 */
	public function __construct()
	{
	}

	/**
	 * Clear the data from the store
	 */
	public function clear()
	{
		/**
		 * Reset the headers array
		 */
		$this->clearHeaders();
	}

	/**
	 * Set the content type header
	 * @param string $contentType
	 */
	public function setContentType($contentType)
	{
		$this->setHeader('Content-Type', $contentType);
	}

	public function setStatus($status_code = null)
	{
		$this->setHeader("HTTP/1.0", $status_code . " " . $this->status_codes[$status_code]);
	}

	/**
	 * Sends an object as a json string
	 * @param  *  $data
	 * @param  integer $options
	 */
	public function sendJSON($data, $options = 0)
	{
		/**
		 * encode the json
		 */
		$payload = json_encode($data, $options);

		/**
		 * Check to see wether we have a valid json string.
		 */
		if($payload === false)
		{
			throw new Exception("Unable to send json: " . json_last_error());
		}

		/**
		 * Set the application/josn header format
		 */
		$this->setContentType('application/json');

		/**
		 * if we have a callback parameter, then wrape the content in that entity.
		 */
		if(Registry::get("Input")->get('jsonp'))
		{
			$payload =  Registry::get("Input")->get('jsonp') . "(" . $payload . ");";
		}


		/**
		 * Send the payload to the client
		 */
		$this->send($payload);
	}

	/**
	 * Send a payload of data tot he client
	 * @param  string $data
	 */
	public function send($data)
	{
		/**
		 * Send the headers
		 */
		$this->sendHeaders();

		/**
		 * print the data
		 */
		echo $data;
	}

	/**
	 * Check to see if the headers have been sent
	 * @return bool true|false depening on if the headers are sent.
	 */
	public function headersSent()
	{
		return headers_sent();
	}

	/**
	 * Send the headers if headers have not already been sent
	 * @return boolean
	 */
	public function sendHeaders()
	{
		if(!$this->headersSent())
		{
			foreach ($this->headers as $key => $value)
			{
				header($key . ': ' . $value);
			}

			return true;
		}

		return false;
	}

	/**
	 * Set a header to the output.
	 */
	public function clearHeaders()
	{
		$this->headers = array();
	}

	/**
	 * Set a header to the output.
	 */
	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
	}

	/**
	 * Delete a header from out.
	 */
	public function removeHeader($key)
	{
		unset($this->headers[$key]);
	}

	/**
	 * get a header from the headers
	 */
	public function getHeader($key)
	{
		return $this->headers[$key];
	}

	/**
	 * Redirect method
	 */
	public function redirect($location, $terminate = true)
	{
		/**
		 * If the headers are sent, we need to throw an exception
		 */
		if($this->headersSent())
		{
			throw new Exception("Cannot redirect, headers are already sent.");
		}

		/**
		 * Add the header
		 */
		$this->setHeader('Location', $location);

		/**
		 * Terminate the request if required.
		 */
		if($terminate)
		{
			$this->send(null);
			exit;
		}
	}
}