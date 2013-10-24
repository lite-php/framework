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
 * Base Controller Class
 */
class Controller
{
	/**
	 * Returns an object or an action to the Applications Controller
	 * @param  string $object
	 * @return *
	 */
	public function __get($object)
	{
		switch($object)
		{
			case 'application':
				return Registry::get('Application');
			break;

			case 'input':
				return Registry::get('Input');
			break;

			case 'output':
				return Registry::get('Output');
			break;

			case 'view':
				return Registry::get('View');
			break;

			case 'model':
				return Registry::get('Modelloader');
			break;

			case 'library':
				return Registry::get('Libraryloader');
			break;

			case 'config':
				return Registry::get('ConfigLoader');
			break;

			case 'helper':
				return Registry::get('HelperLoader');
			break;
		}
	}
}