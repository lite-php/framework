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
 * Require HTTP Interfaces and Loaders
 */
require_once('classes/http/input.php');
require_once('classes/http/output.php');

/**
 * Load the loader layer
 */
require_once('classes/loaders/base.php');
require_once('classes/loaders/model.php');
require_once('classes/loaders/library.php');
require_once('classes/loaders/config.php');

/**
 * Require Database libraries
 */
require_once('classes/database/database.php');

/**
 * Require the core application files
 */
require_once('classes/application.php');
require_once('classes/mvc/model.php');
require_once('classes/mvc/controller.php');
require_once('classes/mvc/view.php');
require_once('classes/route.php');

/**
 * Require other application dependancies
 * note: These classes may be changed toan autoload feature.
 */
require_once('classes/view/template.php');

/**
 * Instantiate objects into the registry
 */
Registry::set('View',			new View());
Registry::set('Model',			new Model());
Registry::set('Modelloader',	new ModelLoader());
Registry::set('Libraryloader',	new LibraryLoader());
Registry::set('ConfigLoader',	new ConfigLoader());
Registry::set('HTTPInput',		new HTTPInput());
Registry::set('HTTPOutput',		new HTTPOutput());
Registry::set('Route',			new Route());
Registry::set('Application',	new Application());