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
     * Positionals
     */
    protected $positionals = array();

    /**
     * Flags
     */
    protected $flags = array();

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
     * [$arguments description]
     * @var [type]
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
     * Parser Arguments
     */
    protected function parse($arguments = array())
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
                    $this->arguements[$kv[0]] = $kv[1];
                break;
            }
        }
    }

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
     * Return a positional value
     */
    public function getPositional($offset, $default = null)
    {
        return isset($this->positionals[$offset]) ? $this->positionals[$offset] : $default;
    }

    /**
     * Return a positional value
     */
    public function getPositionals()
    {
        return $this->positionals;
    }

    /**
     * Return a flag is set
     */
    public function getFlag($key, $default = false)
    {
        return isset($this->flags[$key]) ? $this->flags[$key] : $default;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * get an argument at a possition
     */
    public function getArgument($key, $default = null)
    {
        return isset($this->arguments[$key]) ? $this->arguments[$key] : $default;
    }
}