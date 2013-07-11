<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
abstract class Registry
{
	/**
	 * @type Array Entity Store
	 */
	public static $_registry = array();

	/**
	 * Sets an entity to the store
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
	public static function delete($key)
	{
		//return unset(self::$_registry[$key]);
	}
}