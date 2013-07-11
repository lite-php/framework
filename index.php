<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */

/**
 * Require the framework
 */
require_once('system/framework.php');

/**
 * Fetch the application out of the registry
 */
$Application = Registry::get('Application');

/**
 * Configure the application
 */
$Application->setApplicationPath(__DIR__ . '/applications/todo');

/**
 * Run the applciation
 */
$Application->run();