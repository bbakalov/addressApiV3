<?php

class DbModel
{
    private $host = ''; //your host
    private $dbname = ''; //your DB name
    private $user = ''; //your DB user
    private $pass = ''; //your DB password

    private $db;
    private $stmt;
    private $error;

    /**
     * Creates a PDO instance representing a connection to a database.
     */
    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        try {
            $this->db = new PDO($dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Prepares a statement for execution and returns a statement object
     * @param $query
     */
    public function query($query)
    {
        $this->stmt = $this->db->prepare($query);
    }

    /**
     * Binds a value to a parameter
     * @param $param
     * @param $value
     * @param null $type
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Executes a prepared statement
     * @return bool
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Returns an array containing all of the result set rows
     * @return array
     */
    public function fetchAll()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches the next row from a result set
     * @return mixed
     */
    public function fetch()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a single column from the next row of a result set
     * @return string
     */
    public function fetchColumn()
    {
        $this->execute();
        return $this->stmt->fetchColumn();
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     * @return string
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}