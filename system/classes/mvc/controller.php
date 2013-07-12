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
	public function __get($object)
	{
		switch($object)
		{
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
		}
	}
}