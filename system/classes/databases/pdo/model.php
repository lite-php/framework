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
 * PDO_Model - MySQL database library
 *
 * @author Sasa
 *
 */
class PDO_Model extends PDO
{

    const FETCH_FROM_NEXT_ROW = 0;
    const FETCH_FROM_LAST_ROW = 1;

    const INSERT = "INSERT INTO";
    const UPDATE = "UPDATE";
    const REPLACE = "REPLACE";

    private static $config = array();

    /**
     * @var PDO_Model[]
     */
    private static $instances = array();

    /**
     * Previous prepared statements
     * @var Statement[]
     */
    private $prev_stmt = array();

    /**
     * Previous column used
     * ONLY if $prev_stmt is used
     *
     * @var array
     */
    private $prev_columns = array();

    /**
     * Check if columns exists - on/off
     *
     * @var bool
     */
    private $check_columns = false;


    /**
     * Positive if PDO::FETCH_TABLE_NAMES is used
     * @var int
     */
    public $fetch_table_names = 0;

    /**
     * @param string $instance
     * @return PDO_Model
     * @throws Exception
     */
    static function getInstance($instance = 'default')
    {
        if(!array_key_exists($instance, self::$instances)) {
            // check if configuration exists
            if(!array_key_exists($instance, self::$config)) {
                throw new Exception("Configuration is not set. Use PDO_Model::setConfig(options, [instance]) to set");
            }

            self::$instances[$instance] = new self(
                self::$config[$instance]["dsn"],
                self::$config[$instance]["username"],
                self::$config[$instance]["password"],
                self::$config[$instance]["options"]
            );
        }

        return self::$instances[$instance];
    }

    /**
     * Set database config params
     * config param should contains dsn, username, password and options
     *
     * @param array $config
     * @param string $instance
     */
    static function setConfig($config, $instance = 'default')
    {
        self::$config[$instance]['dsn'] = array_key_exists('dsn', $config) ? $config['dsn'] : "";
        self::$config[$instance]['username'] = array_key_exists('username', $config) ? $config['username'] : null;
        self::$config[$instance]['password'] = array_key_exists('password', $config) ? $config['password'] : null;
        self::$config[$instance]['options'] = array_key_exists('options', $config) ? $config['options'] : array();
    }

    /**
     * @throws PDOException|Exception
     * @param string $dsn
     * @param null $username
     * @param null $password
     * @param array $options
     */
    function __construct()
    {
		/**
		 * Fetch the configuration object from the registry
		 */
		$config = Registry::get('Application')->getConfiguration('pdo');


        // Default options
        $options = $config->driver_options + array(
            PDO::ATTR_STATEMENT_CLASS => array("PDO_Statement", array($this)),
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );

        try
        {
			/**
			 * Call the parent constructor
			 */
			parent::__construct($config->dsn, $config->username, $config->password, $options);
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Check if columns exists for INSERT or UPDATE
     * To turn ON use checkColumns(true)
     * To turn OFF use checkColumns(false)
     *
     * @param bool|int $set
     * @return bool
     */
    function checkColumns($set = null)
    {
        if ($set !== null) {
            $this->check_columns = $set;
        }

        return $this->check_columns;
    }

    /**
     * Remove non-existed columns before insert/update
     *
     * @param string $table
     * @param array $data
     * @param mixed $stmt_key
     * @return array
     */
    private function removeNonExistentColumns($table, &$data, $stmt_key = null)
    {
        if ($this->check_columns) {

            // use previous columns or get new
            if (!empty($stmt_key) && empty($this->prev_columns[$stmt_key])) {
                $this->prev_columns[$stmt_key] = $this->getColumnsFromTable($table);
                $columns = $this->prev_columns[$stmt_key];
            } elseif (!empty($stmt_key) && !empty($this->prev_columns[$stmt_key])) {
                $columns = $this->prev_columns[$stmt_key];
            } else {
                $columns = $this->getColumnsFromTable($table);
            }

            $new_data = array();
            foreach ($columns as $column) {
                if (array_key_exists($column, $data)) {
                    $new_data[$column] = $data[$column];
                }
            }
            $data = $new_data;
        }
        return $data;
    }

    /**
     * Build and Get SET statement
     *
     * $this->getSetStmt(PDO_Model::INSERT, "mytable", array("name" => "John"));
     * will return:
     * INSERT INTO
     * @param string $syntax INSERT, UPDATE, REPLACE
     * @param string $table
     * @param array $data
     * @param null $where
     * @return PDOStatement
     */
    private function getSetStmt($syntax, $table, $data, $where = null)
    {
        $columns = array();

        foreach (array_keys($data) as $column) {
            $columns[] = "`" . $column . "` = ?";
        }
        $columns = implode(", ", $columns);

        $sql = "$syntax `$table` SET " . $columns . $this->buildWhere($where);

        return $this->prepare($sql);
    }

    /**
     * Perform INSERT, UPDATE, REPLACE
     *
     * @param string $syntax
     * @param string $table
     * @param array $data
     * @param null $where
     * @param array $where_params
     * @param null $stmt_key
     * @return Statement|PDOStatement
     */
    private function executeBySyntax($syntax, $table, $data, $where = null, $where_params = array(), $stmt_key = null)
    {
        if (!is_null($where) && !is_array($where)) {
            $where = array($where);
        }

        if (is_object($data)) {
            $data = (array) $data;
        }

        $data = $this->removeNonExistentColumns($table, $data, $stmt_key);

        // support for scalar param
        if (!is_array($where_params)) {
            $where_params = array($where_params);
        }

        if (empty($stmt_key)) {
            $stmt = $this->getSetStmt($syntax, $table, $data, $where);
        } elseif (empty($this->prev_stmt[$stmt_key])) {
            $stmt = $this->getSetStmt($syntax, $table, $data, $where);
            $this->prev_stmt[$stmt_key] = $stmt;
        } else {
            $stmt = $this->prev_stmt[$stmt_key];
        }

        $stmt->execute(array_merge(array_values($data), $where_params));

        return $stmt;
    }
    /**
     * Insert one row
     *
     * @throw PDOException
     *
     * @param string $table
     * @param array $data
     * @param int|string|null $stmt_key Unique key to use previous prepared stmt
     * @return Statement
     */
    function insert($table, $data, $stmt_key = null)
    {
        return $this->executeBySyntax(self::INSERT, $table, $data, null, array(), $stmt_key);
    }

    /**
     * Update row in table, optionally use previous prepared stmt by stmt_key
     *
     * @throw PDOException
     *
     * @param string $table
     * @param array $data
     * @param mixed $where
     * @param mixed|array $where_params
     * @param int|string|null $stmt_key Unique key to use previous prepared stmt
     * @return Statement
     */
    function update($table, $data, $where, $where_params = array(), $stmt_key = null)
    {
        return $this->executeBySyntax(self::UPDATE, $table, $data, $where, $where_params, $stmt_key);
    }

    /**
     * Insert or replace row in a table
     *
     * @throw PDOException
     *
     * @param string $table
     * @param array $data
     * @param int|string|null $stmt_key
     * @return Statement
     */
    function replace($table, $data, $stmt_key = null)
    {
        return $this->executeBySyntax(self::REPLACE, $table, $data, null, array(), $stmt_key);
    }

    /**
     * Delete rows from table
     *
     * @throw PDOException
     *
     * @param string $table
     * @param mixed $where
     * @param mixed $where_params
     * @return Statement
     */
    function delete($table, $where, $where_params)
    {
        $sql = "DELETE FROM " . $table . $this->buildWhere($where);
        $stmt = $this->executeQuery($sql, $where_params);

        return $stmt;
    }

    /**
     * Count rows in one table - very simple implementation
     *
     * @param string $table
     * @param mixed $where
     * @param array $where_params
     * @return int
     */
    function count($table, $where, $where_params = null)
    {
        $sql = "SELECT COUNT(*) FROM " . $table . $this->buildWhere($where);
        $stmt = $this->executeQuery($sql, $where_params);

        return $stmt->fetchColumn();
    }


    /**
     * Prepare & execute query with params
     *
     * @throw PDOException
     *
     * @param $sql
     * @param null $params
     * @return Statement
     */
    function executeQuery($sql, $params = null)
    {
        if (!is_array($params) && !is_null($params)) {
            $params = array($params);
        }

        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Build where statement for SQL query
     *
     * @param mixed $where
     * @param string $operand AND | OR
     * @return string
     */
    function buildWhere($where, $operand = "AND")
    {
        if (empty($where)) {
            return "";
        }

        if (is_array($where)) {
            $wheres = array();
            foreach ($where as $k => $w) {
                $wheres[] = "(" . $w . ")";
            }
            $where = implode(" $operand ", $wheres);
        }

        return " WHERE " . $where;
    }

    /**
     * Get Database Query Builder
     * @return Query
     */
    function createQuery()
    {
        return new PDO_Query($this);
    }

    /**
     * Shortcut for createQuery()->select
     *
     * @param string $statement
     * @return Query
     */
    function select($statement = "")
    {
        return self::createQuery()->select($statement);
    }

    /**
     * Get all columns from table
     *
     * @throw PDOException
     *
     * @param $table
     * @return array
     */
    function getColumnsFromTable($table)
    {
        $sql = "DESCRIBE $table";

        return $this->executeQuery($sql)
            ->fetchAll(self::FETCH_COLUMN);
    }

    /**
     * Save data to table
     *
     * @throw PDOException
     *
     * @param string $table
     * @param array $data
     * @param string $primary_key Name of primary key column
     * @param string|int $stmt_key
     * @return Statement
     */
    function save($table, $data, $primary_key, $stmt_key = null)
    {
        // Update if primary key exists in data set or insert new row
        if (!empty($data[$primary_key])) {
            return $this->update($table, $data, $primary_key . " = ?", $data[$primary_key], $stmt_key);
        } else {
            return $this->insert($table, $data, $stmt_key);
        }
    }


    /**
     * Set fetch table names attribute
     *
     * @param int $option 1 or 0
     */
    function setFetchTableNames($option = 1)
    {
        $this->setAttribute(self::ATTR_FETCH_TABLE_NAMES, $option);
        $this->fetch_table_names = $option;
    }

    /**
     * Returns an array with PDO named parameter keys, for use with bind in statements
     * @param  array  $array  Array ofvalues to bind
     * @param  string $prefix Optional prefi to unique the bind strings (if more
     *                        than on bind in is being used in the statement)
     * @return array          Associative array with named parameter keeys (:key)
     */
    function getBindInArray($array, $prefix = '') {
        $i = 1;
        $output = array();
        foreach ($array as $value) {
            $output[":{$prefix}_bindIn_" . $i] = $value;
            $i += 1;
        }
        return $output;
    }

    /**
     * Returns a string for the named parameter keys
     * @param  array  $array Array of key value pairs to bind
     * @return string        Comma delimited stringof all values
     */
    function getBindInString($array) {
        return implode(' , ', array_keys($array));
    }
}