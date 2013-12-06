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
 * Database Class
 * @extends PDO
 */
class LDAP_Model
{
	/**
	 * Connection Instance
	 */
	protected $_connection;

	/**
	 * LDAP Constructor
	 */
	public function __construct()
	{
		/**
		 * load the configuration.
		 * @var [type]
		 */
		$this->config = Registry::get('ConfigLoader')->get('ldap');

		/**
		 * Create an ldap connections
		 * @note This does not trigger an error
		 */
		if(!$this->_connection = ldap_connect($this->config->server, $this->config->port))
		{
			throw new Exception("Unable to communicate with LDAP Server.");
		}

		/**
		 * Bind with credentials
		 */
		$this->bind($this->config->username, $this->config->password);
	}

	public function bind($username, $password)
	{
		/**
		 * Bind to ldap
		 */
		if(!ldap_bind($this->_connection, $username, $password))
		{
			throw new Exception("Unable to bind to RDN.");
		}
	}

	public function read($base_dn, $filter, $attributes = null)
	{
		return ldap_read($this->_connection, $base_dn, $filter, $attributes);
	}

	public function search($base, $filter = null, $attributes = array())
	{
		return ldap_search($this->_connection, $base, $filter, $attributes);
	}

	protected function objectSIDToString($binsid)
	{
		$hex_sid = bin2hex($binsid);
		$rev = hexdec(substr($hex_sid, 0, 2));
		$auth = hexdec(substr($hex_sid, 4, 12));
		$subcount = hexdec(substr($hex_sid, 2, 2));

		$result    = "$rev-$auth";

		for ($x=0;$x < $subcount; $x++)
		{
			$subauth[$x] = hexdec($this->_little_endian(substr($hex_sid, 16 + ($x * 8), 8)));
			$result .= "-" . $subauth[$x];
		}

		// Cheat by tacking on the S-
		return 'S-' . $result;
	}

	protected function _little_endian($hex)
	{
		$result = '';
		for ($x = strlen($hex) - 2; $x >= 0; $x = $x - 2)
		{
			$result .= substr($hex, $x, 2);
		}

		return $result;
	}
}