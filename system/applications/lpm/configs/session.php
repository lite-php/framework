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
 * Session Configuration
 */
class Session_Config
{
	/**
	 * The driver handler specifies the storage method used for sessions
	 * Possible options are
	 * * native:     This simulates PHP's defualt storage mechanism
	 * * memcached:  The stores data on a Memcached server (requires php5-memcached, memcached and $opts values)
	 * * database:   Stores the sessions in a database.. @todo
	 * * redis:      Stores the sessions in redis.. @todo
	 * @var string
	 */
	public $handler = 'native';

	/**
	 * Session name, this is to allow domain specific data.
	 * @var string
	 */
	public $name = 'session';

	/**
	 * Expiration TTL
	 * @var integer
	 */
	public $expiration = 7200;

	/**
	 * Session Regeneration
	 * @var boolean
	 */
	public $regenerate = true;

	/**
	 * Delete the old session data, this will only take effect if regenerate is set to true.
	 * @var boolean
	 */
	public $delete_old_session = true;

	/**
	 * Session save path
	 * @var string
	 */
	public $savepath = '/tmp';

	/**
	 * Specific memcached options if memcached is selected
	 */
	public $driver_memcached = array(
		"persistent_id" => null,
		"servers" => array(
			"127.0.0.1" => 11211
		)
	);

	/**
	 * Cookie Parameters for the session.
	 * @var array
	 */
	public $cookie_params = array(
		//Lifetime of the cookie in seconds
		'lifetime'	=> 0,

		//Path on the domain where the cookie will work.
		'path'		=> '/',

		//Domain where the cookie will work. use .site.com for all subdomains
		'domain'	=> null,

		//Send cookies over https
		'secure'	=> false,

		//attempt to send the http only flag when setting the cookie.
		'httponly'	=> false
	);
}