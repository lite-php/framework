<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Require constants
 */
require_once('constants.php');

/**
 * Require core system classes
 */
require_once('classes/registry.php');
require_once('classes/errors/handler.php');

/**
 * Get an instance of the ErrorHandler object and store it globally.
 */
Registry::set('ErrorHandler', ErrorHandler::getInstance());

/**
 * Require HTTP Interfaces
 */
require_once('classes/http/input.php');
require_once('classes/http/output.php');

/**
 * Require the main application runner
 */
require_once('classes/application.php');

/**
 * Require Database core
 */
require_once('classes/database/database.php');

/**
 * Require the route and MVC Classes
 */
require_once('classes/mvc/model.php');
require_once('classes/mvc/controller.php');
require_once('classes/mvc/view.php');

/**
 * Require the loaders
 */
require_once('classes/loaders/base.php');
require_once('classes/loaders/model.php');
require_once('classes/loaders/library.php');
require_once('classes/loaders/config.php');

/**
 * Require other dependancies
 */
require_once('classes/route.php');
require_once('classes/view/template.php');

/**
 * Instantiate objects into the registry
 */
Registry::set('View', 			new View());
Registry::set('Model', 			new Model());
Registry::set('Modelloader',	new ModelLoader());
Registry::set('Libraryloader',	new LibraryLoader());
Registry::set('ConfigLoader',	new ConfigLoader());
Registry::set('HTTPInput', 		new HTTPInput());
Registry::set('HTTPOutput', 	new HTTPOutput());
Registry::set('Route', 			new Route());
Registry::set('Application',	new Application());