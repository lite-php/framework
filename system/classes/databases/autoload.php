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
 * Register database autoloads
 */
spl_autoload_register(function($classname){
	/**
	 * Split the class names
	 */
	$path = str_replace("_", "/", strtolower($classname));

	/**
	 * Check to see if the file exists
	 */
	if(file_exists(__DIR__ . "/" . $path . ".php"))
	{
		require_once __DIR__ . "/" . $path . ".php";
	}
});