<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Define the absolute path to the system folder
 */
!defined('SYSTEM_PATH') && define('SYSTEM_PATH', __DIR__);

/**
 * Base URL Path
 */
!defined('BASE_URL') && define('BASE_URL', dirname($_SERVER['PHP_SELF']));

/**
 * Start time
 */
!defined('START_TIME') && define('START_TIME', time());

/**
 * Start microtime
 */
!defined('START_MTIME') && define('START_MTIME', microtime());

/**
 * Get the memory usage as early on as possible
 */
!defined('INITIAL_MEMORY_USAGE') && define('INITIAL_MEMORY_USAGE', memory_get_usage());

/**
 * Username of the script executor
 */
!defined('CURRENT_USER') && define('CURRENT_USER', get_current_user());

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