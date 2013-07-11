<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */

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
 * Require the route and MVC Classes
 */
require_once('classes/route.php');
require_once('classes/mvc/controller.php');
require_once('classes/mvc/view.php');
require_once('classes/view/template.php');

/**
 * Instantiate objects into the registry
 */
Registry::set('View', new View());
Registry::set('HTTPInput', new HTTPInput());
Registry::set('HTTPOutput', new HTTPOutput());
Registry::set('Route', new Route());
Registry::set('Application', new Application());