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
 * Load the enviroment IO Classes.
 */
require_once('classes/' . (IS_CLI ? 'cli' : 'http') . '/bootstrap.php');


/**
 * Load the base loader and sub loaders for autoloading
 * configurations, models and libraries.
 */
require_once('classes/loaders/base.php');
require_once('classes/loaders/model.php');
require_once('classes/loaders/library.php');
require_once('classes/loaders/helper.php');
require_once('classes/loaders/config.php');

/**
 * Require the database layer, this needs to be abstracted to a library
 * but need to think about the Database Model Base.
 */
require_once('classes/databases/autoload.php');

/**
 * Require the core application files
 */
require_once('classes/application.php');
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
Registry::set('ModelLoader',	new ModelLoader());
Registry::set('LibraryLoader',	new LibraryLoader());
Registry::set('ConfigLoader',	new ConfigLoader());
Registry::set('HelperLoader',	new HelperLoader());
Registry::set('Route',			new Route());
Registry::set('Application',	new Application());

/**
 * Define a constant to allow for profiling the boot time of the system
 */
define('SYSTEM_BOOT_TIME', number_format((microtime(true) - SYSTEM_START_TIME), 4));
define('APPLICATION_START_TIME', microtime(true));