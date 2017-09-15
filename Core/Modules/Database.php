<?php

namespace Core\Modules;

use \PDO;

use Core\Config\Database as dbConfig;

class Database
{

    /**
    * \PDO connection
    */
    protected $_conn;

    /**
    * parameters
    */
    protected $_params = [];

    /**
    * check if is executed
    */
    protected $_is_executed = false;

    /**
    * execute after
    */
    protected $_execute = false;

    /**
    * sql
    */
    protected $_stmt = [];

    /**
    * calls
    */
    private $_calls = [];

    /**
    * make class callable statically
    */
    public static function getInstance() {

        return new Database;
    }

    /**
    * constructor
    */
    public function __construct()
    {

        $config = dbConfig::Config();

        $connection = $config->connection;

        $hostname = $connection->hostname;
        
        $username = $connection->username;
        
        $password = $connection->password;
        
        $database = $connection->database;

        $driver = $config->driver;

        $dsn = "{$driver}:host={$hostname};dbname={$database}";

        $opts = array(
            PDO::ATTR_ERRMODE => $config->error_mode,
            PDO::ATTR_DEFAULT_FETCH_MODE =>  $config->fetch_method
        );

        $this->_conn = new PDO($dsn, $username, $password, $opts);
    }

    /**
    * bind values to direct sql statement
    *
    * @param {array} $values
    */
    public function bind(array $values)
    {
        $this->_params = $values;
        $this->execute();
        return $this->_conn;
    }

    /**
    * sql delete
    * @param {string} $table_name Table name
    * @param {array} $params
    */
    public function deleteFrom($table_name)
    {
        $this->addStmt('DELETE FROM '.$table_name);
        $this->_execute = true;
        return $this;
    }

    /**
    * drop table
    *
    * @param {string} $table_name Table name
    */
    public function dropTable(string $table_name)
    {
        $this->addStmt('DROP table ' . $table_name);
        $this->execute();
        return true;
    }

    /**
    * execute statement
    */
    public function exec()
    {
        $this->execute();
        return $this;
    }

    /**
    * fetch
    */
    public function fetch()
    {
        $args = func_get_args();

        $offset = (count($args) == 0) ? 0 : $args[0];
        $limit = (count($args) == 0) ? $args[0] : $args[1];

        $this->addStmt('LIMIT ?, ?');
        $this->_params = array_merge($this->_params, [$offset, $limit]);

        return $this->execute()->fetchAll();
    }

    /**
    * return all results
    */
    public function fetchAll()
    {
        $stmt = $this->execute();
        return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : null;
    }

    /**
    * fetch only one result
    */
    public function fetchOne()
    {
        $stmt = $this->execute();
        return ($stmt->rowCount() > 0) ? $stmt->fetch() : null;
    }

    /**
    * sql find
    *
    * @param {string} $field Field to search
    * @param {string} $kewyord Keyword to search for
    */
    public function find(string $field, string $keyword)
    {
        $word = '%' . $keyword . '%';
        $this->addStmt('WHERE '.$field.' LIKE ?');
        array_push($this->_params, $word);

        return $this;
    }

    /**
    * sql FROM clause
    * @param {string} $table_name Table name to select from
    */
    public function from(string $table_name)
    {
        $this->addStmt('FROM ' . $table_name);
        return $this;
    }

    /**
    * sql INSERT clause
    *
    * @param {string} $table_name Table name to insert into
    */
    public function insertInto(string $table_name) 
    {
        $this->addStmt('INSERT INTO ' . $table_name);
        return $this;
    }

    /**
    * set statement limit
    *
    * @param {int} $number Limits
    */
    public function limit(int $number)
    {
        $this->addStmt('LIMIT ?');
        array_push($this->_params, $number);

        return $this;
    }

    /**
    * sql SELECT clause
    * @param {array} $values Rows
    */
    public function select()
    {
        $this->addStmt('SELECT ' . implode(', ', func_get_args()));
        return $this;
    }

    /**
    * bind sql values
    *
    */
    public function set(array $values)
    {
        $placeholders = [];
        $keys = array_keys($values);
        $vals = array_values($values);

        foreach($keys as $key) $placeholders[] = "{$key} = ?";

        $this->addStmt('SET ' . implode(', ', $placeholders));
        $this->_params = array_merge($this->_params, $vals);
        
        return $this;
    }

    /**
    * run sql query
    * @param {string} $sql Sql query
    */
    public function stmt($sql)
    {
        array_push($this->_stmt, $sql);
        return $this;
    }

    /**
    * truncate table
    *
    */
    public function truncate(string $table_name)
    {
        $this->addStmt('TRUNCATE table ' . $table_name);
        $this->execute();
        return true;
    }

    /**
    * sql UPDATE clause
    */
    public function update(string $table_name)
    {
        $this->addStmt('UPDATE ' . $table_name);
        return $this;
    }

    /**
    * add values to query
    *
    * @param {array} $values Data values
    */
    public function values(array $values)
    {    
        $placeholders = [];
        $keys = array_keys($values);
        $vals = array_values($values);

        foreach($keys as $key) $placeholders[] = "{$key} = ?";

        $this->addStmt('SET ' . implode(', ', $placeholders));
        $this->_params = array_merge($this->_params, $vals);
        
        $stmt = $this->execute();
        return $this->_conn->lastInsertId();
    }

    /**
    * sql Where clause
    */
    public function where()
    {

        $where = [];
        $args = func_get_args();
        
        if(!is_array($args[0])) {

            $eq = (count($args) == 3) ? $args[1] : '=';
            $val = (count($args) == 3) ? $args[2] : $args[1];
            
            $where[] = "{$args[0]} {$eq} ?";
            array_push($this->_params, $val);
    
            if($this->_execute) {
                $this->execute();
                return true;
            }

        } else {

            foreach($args as $arg)
            {
                $eq = (count($arg) == 3) ? $arg[1] : '=';
                $val = (count($arg) == 3) ? $arg[2] : $arg[1];
                
                $where[] = "{$arg[0]} {$eq} ?";
                array_push($this->_params, $val);
        
                if($this->_execute) {
                    $this->execute();
                    return true;
                }
            }
        }

        $this->addStmt('WHERE '. implode(' AND ', $where));
        return $this;
    }

    public function __destruct()
    {
        unset($this->_params, $this->_stmt);
        return $this;
    }

    protected function addCall()
    {
        $this->_calls[] = __FUNCTION__;
    }

    /**
    * Add parameters to statement
    */
    protected function addParams(\PDOStatement $stmt)
    {

        if(count($this->_params)) {
            
            for($i = 0; $i < count($this->_params); $i++) {

                $val = $this->_params[$i];
                $index = (int) ($i + 1);
                $type = is_numeric($val) ? PDO::PARAM_INT : PDO::PARAM_STR;

                $stmt->bindParam($index, $val, $type);
            } 
        }
        return $stmt;
    }

    /**
    * execute sql statement
    */
    protected function execute()
    {
        $sql = implode(' ', $this->_stmt);
        $stmt = $this->_conn->prepare($sql);
        
        if(count($this->_params)) {
            
            foreach($this->_params as $key => &$val) {

                $index = (int) ($key + 1);
                $type = is_numeric($val) ? PDO::PARAM_INT : PDO::PARAM_STR;

                $stmt->bindParam($index, $val, $type);
            } 
        }
        
        $stmt->execute();

        $this->_is_executed = true;
        return $stmt;
    }

    /**
    * Check if a method is called
    */
    protected function isCalled($method)
    {
        $method = strtolower($method);
        return isset( $this->_calls[$method] );
    }

    /**
    * Query builder
    * 
    * @param {string} $sql Query query
    */
    private function addStmt($sql)
    {
        array_push($this->_stmt, $sql);
    }
}