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
 * CommandLine class
 * Command Line Interface (CLI) utility class.
 */
class CLIParser
{
    protected $arguments;

    /**
     * PARSE ARGUMENTS
     * 
     * This command line option parser supports any combination of three types
     * of options (switches, flags and arguments) and returns a simple array.
     *
     * @author              Patrick Fisher <patrick@pwfisher.com>
     * @since               August 21, 2009
     * @see                 https://github.com/pwfisher/CommandLine.php
     * @see                 http://www.php.net/manual/en/features.commandline.php
     *                      #81042 function arguments($argv) by technorati at gmail dot com, 12-Feb-2008
     *                      #78651 function getArgs($args) by B Crawford, 22-Oct-2007
     * @usage               $args = CommandLine::parseArgs();
     */
    public function __construct($argv = null)
    {
        /**
         * Get an array of what we are parsing
         * @var array
         */
        $argv                           = $argv ? $argv : $_SERVER['argv'];

        /**
         * Shift
         */
        array_shift($argv);

        /**
         * Start looping over the arguments
         */
        for ($i = 0, $j = count($argv); $i < $j; $i++)
        {
            /**
             * Current Argument
             * @var string
             */
            $arg                        = $argv[$i];

            /**
             * Check for --key or --key=value
             * the defualt value would be true
             */
            if (substr($arg, 0, 2) === '--')
            {
                $eqPos                  = strpos($arg, '=');

                /**
                 * Check for the value
                 */
                if ($eqPos === false)
                {
                    $key                = substr($arg, 2);

                    // --foo value
                    if ($i + 1 < $j && $argv[$i + 1][0] !== '-')
                    {
                        $value          = $argv[$i + 1];
                        $i++;
                    }
                    else
                    {
                        $value          = isset($this->arguments[$key]) ? $this->arguments[$key] : true;
                    }
                    $this->arguments[$key]          = $value;
                }

                // --bar=baz
                else
                {
                    $key                = substr($arg, 2, $eqPos - 2);
                    $value              = substr($arg, $eqPos + 1);
                    $this->arguments[$key]          = $value;
                }
            }

            // -k=value -abc
            else if (substr($arg, 0, 1) === '-')
            {
                // -k=value
                if (substr($arg, 2, 1) === '=')
                {
                    $key                = substr($arg, 1, 1);
                    $value              = substr($arg, 3);
                    $this->arguments[$key]          = $value;
                }
                // -abc
                else
                {
                    $chars              = str_split(substr($arg, 1));
                    foreach ($chars as $char)
                    {
                        $key            = $char;
                        $value          = isset($this->arguments[$key]) ? $this->arguments[$key] : true;
                        $this->arguments[$key]      = $value;
                    }
                    // -a value1 -abc value2
                    if ($i + 1 < $j && $argv[$i + 1][0] !== '-')
                    {
                        $this->arguments[$key]      = $argv[$i + 1];
                        $i++;
                    }
                }
            }

            // plain-arg
            else
            {
                $this->arguments[] = $arg;
            }
        }
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * get an argument at a possition
     */
    public function getArgument($key)
    {
        return $this->arguments[$key];
    }

    /**
     * GET BOOLEAN
     */
    public function getBoolean($key, $default = false)
    {
        if (!isset($this->arguments[$key]))
        {
            return $default;
        }

        $value = $this->arguments[$key];

        if (is_bool($value))
        {
            return $value;
        }

        if (is_int($value))
        {
            return (bool)$value;
        }

        if (is_string($value))
        {
            $value                      = strtolower($value);
            $map = array(
                'y'                     => true,
                'n'                     => false,
                'yes'                   => true,
                'no'                    => false,
                'true'                  => true,
                'false'                 => false,
                '1'                     => true,
                '0'                     => false,
                'on'                    => true,
                'off'                   => false,
            );
            if (isset($map[$value]))
            {
                return $map[$value];
            }
        }

        return $default;
    }
}