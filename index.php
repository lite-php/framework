<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */

/**
 * Declare the secure constant before framework to
 */
define('SECURE', true);

/**
 * Require the framework
 */
require_once('system/framework.php');

/**
 * Fetch the Application Object out of the registry.
 * @var Application
 */
$Application = Registry::get('Application');

/**
 * Set the application path
 */
$Application->setApplicationPath(__DIR__ . '/applications/todo');

/**
 * Run the applciation
 */
$Application->run();