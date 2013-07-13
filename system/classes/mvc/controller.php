<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
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
				return Registry::get('HTTPInput');
			break;

			case 'output':
				return Registry::get('HTTPOutput');
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
		}
	}
}