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
 * Define the current version of the framework.
 */
!defined('VERSION') && define('VERSION', '1.0.0');

/**
 * Define the absolute path to the system folder
 */
!defined('SYSTEM_PATH') && define('SYSTEM_PATH', __DIR__);

/**
 * Base URL Path
 */
!defined('BASE_URL') && define('BASE_URL', rtrim(dirname($_SERVER['PHP_SELF']), '/'));

/**
 * Start time
 */
!defined('SYSTEM_START_TIME') && define('SYSTEM_START_TIME', microtime(true));

/**
 * Get the memory usage as early on as possible
 */
!defined('INITIAL_MEMORY_USAGE') && define('INITIAL_MEMORY_USAGE', memory_get_usage());

/**
 * Username of the script executor
 */
!defined('CURRENT_USER') && define('CURRENT_USER', get_current_user());

/**
 * Check for windows enviroment
 */
!defined('IS_WIN') && define('IS_WIN', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');

/**
 * Define true if we are runnning on a sapi enviroment
 */
!defined('IS_CLI') && define('IS_CLI', php_sapi_name() == 'cli');

/**
 * Define true if we are runnning opn php4
 */
!defined('IS_PHP4') && define('IS_PHP4', version_compare(PHP_VERSION, '5.0.0', '<'));

/**
 * Define true if we are runnning opn php5
 */
!defined('IS_PHP5') && define('IS_PHP5', version_compare(PHP_VERSION, '5.0.0', '>='));

/**
 * Define true if we are runnning opn php5.3
 */
!defined('IS_PHP5_3') && define('IS_PHP5_3', version_compare(PHP_VERSION, '5.3.0', '>=') >= 0);

/**
 * Define true if we are runnning on php6
 */
!defined('IS_PHP6') && define('IS_PHP6', version_compare(PHP_VERSION, '6.0.0') >= 0);