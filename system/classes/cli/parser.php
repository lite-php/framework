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
 * Command line parser.
 */
class CLIParser
{
    /**
     * Positional type
     * These type is usually a single word wor some quouted/escaped text
     */
    const TYPE_POSITIONAL = 0;

    /**
     * Boolean flags
     * Tese are words that start with a - or -- and does not contain a =
     */
    const TYPE_FLAG_BOOL = 1;

    /**
     * Named Arguemnts / Flags with Values
     * This are the same as {@link TYPE_FLAG_BOOL} but have a value
     * assigned with the = sign
     */
    const TYPE_FLAG_VALUE = 2;

    /**
     * Positionals container
     * @var Array
     */
    protected $positionals = array();

    /**
     * Flags container
     * @var Array
     */
    protected $flags = array();

    /**
     * [$arguments description]
     * @var Array
     */
    protected $arguments = array();

    /**
     * Constructor
     */
    public function __construct($argv = null)
    {
        /**
         * Parse the arguments
         */
        $this->parse((is_array($argv) ? $argv : $_SERVER['argv']));
    }

    /**
     * parse the arguments from an arguments array
     * @param  array  $arguments input arguments
     */
    protected function parse($arguments)
    {
        /**
         * Remove the first argument as it's the script name
         */
        array_shift($arguments);

        /**
         * Loop over the arguments
         */
        foreach ($arguments as $idx => $value)
        {
            /**
             * Detect the type of argument
             */
            switch($this->_detect_arg_type($value))
            {
                case self::TYPE_POSITIONAL:
                    /**
                     * Positionals should not have an - or = chars
                     */
                    $this->positionals[] = $value;
                break;
                case self::TYPE_FLAG_BOOL:
                    /**
                     * Flags should be set to true always
                     */
                    $this->flags[ltrim($value, "-")] = true;
                break;
                case self::TYPE_FLAG_VALUE:

                    /**
                     * trim the value
                     */
                    $value = ltrim($value, "-");

                    /**
                     * Explode the parts
                     */
                    $kv = explode("=", $value);

                    /**
                     * Flags should be set to true always
                     */
                    $this->arguments[$kv[0]] = $kv[1];
                break;
            }
        }
    }

    /**
     * Detect the type of argument we that we are tryinging to parse
     *
     * foo       : foo          : Positional
     * -foo      : foo = true   : Boolean Flag
     * --foo     : foo = true   : Boolean Flag
     * --foo=bar : foo = "bar"  : Flag with value
     * 
     * @param  string $value value used to detect a type from
     * @return int           type of argument detected
     */
    protected function _detect_arg_type($value)
    {
        /**
         * First attempt to dectect the possitionals
         */
        if(substr($value, 0, 1) != '-' && strpos($value, "=") === false)
        {
            return self::TYPE_POSITIONAL;
        }

        /**
         * If we have a single - at the start, we treat as a flag
         */
        if(substr($value, 0, 1) == '-' && substr($value, 1, 1) != '-')
        {
            return self::TYPE_FLAG_BOOL;
        }

        /**
         * If we have a double opt
         */
        /**
         * If we have a single - at the start, we treat as a flag
         */
        if(substr($value, 0, 2) == '--')
        {
            /**
             * If we have a = sign, it's a valie
             */
            if(strpos($value, "=") !== FALSE)
            {
                return self::TYPE_FLAG_VALUE;
            }

            /**
             * Should be treated as a bool
             */
            return self::TYPE_FLAG_BOOL;
        }
    }

    /**
     * Returna positional based value
     * @param  int  $offset     index of the positional
     * @param  *    $default    value to return if the positional does not exist
     * @return *                string value from the positional or the default value
     */
    public function getPositional($offset, $default = null)
    {
        return isset($this->positionals[$offset]) ? $this->positionals[$offset] : $default;
    }

    /**
     * Return all the positionals
     * @return Array array of positional values, in roder from ltr
     */
    public function getPositionals()
    {
        return $this->positionals;
    }

    /**
     * Returna flag based value
     * @param  string  $key     index of the flag
     * @param  *       $default    value to return if the positional does not exist
     * @return *                   string value from the positional or the default value
     */
    public function getFlag($key, $default = null)
    {
        return isset($this->flags[$key]) ? $this->flags[$offset] : $default;
    }

    /**
     * Returna all the flags
     * @return Array Array of flags
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Returna an argument value from it's key.
     * @param  string  $key     index of the flag
     * @param  *       $default    value to return if the positional does not exist
     * @return *                   string value from the positional or the default value
     */
    public function getArgument($key, $default = null)
    {
        return isset($this->arguments[$key]) ? $this->arguments[$key] : $default;
    }

    /**
     * Return all the arguments
     * @return Array Array of arguments
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}