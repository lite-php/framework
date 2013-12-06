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
 * Require the HTTP Handlers
 */
require_once('input.php');
require_once('output.php');

/**
 * Set the Enviroment IO stuff
 */
Registry::set('Input',		new HTTPInput());
Registry::set('Output',		new HTTPOutput());