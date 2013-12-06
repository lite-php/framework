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
 * Registry Class
 */
abstract class Registry
{
	/**
	 * Used for storing instantiated objects passed in via Registry::set
	 * @see Registry::set
	 * @var Array
	 */
	public static $_registry = array();

	/**
	 * Sets an entity to the store
	 * @return [type] [description]
	 */
	public static function set($key, $entity)
	{
		return self::$_registry[$key] = $entity;
	}

	/**
	 * Return an entity from the store
	 */
	public static function get($key)
	{
		return self::$_registry[$key];
	}

	/**
	 * Remove an entity from thes tore.
	 */
	public static function exists($key)
	{
		return isset(self::$_registry[$key]);
	}

	/**
	 * Remove an entity from thes tore.
	 */
	public static function delete($key)
	{
		//return unset(self::$_registry[$key]);
	}
}