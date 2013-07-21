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
 * Load CLI Componants
 */
require('parser.php');

/**
 * Load the cli input and output handlers
 */
require('input.php');
require('output.php');

/**
 * Instantiate the componants
 */
Registry::set("CLIParser", new CLIParser());

/**
 * Instantiate the I/O
 */
Registry::set("Input", new CLIInput());
Registry::set("Output", new CLIOutput());