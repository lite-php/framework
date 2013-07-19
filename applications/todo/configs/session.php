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
	 * What handler do we use to track the records choose from native, file, redis, memcache or database
	 * You may also specifiy mysql and set the save path to the server.
	 * @var string
	 */
	public $handler = 'memcached';

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
	 * Options
	 * These are options specific to the handler
	 */
	public $opts = array(
		//Memcached Configuration
		"persistent_id" => null,
		"servers" => array(
			//host => port
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