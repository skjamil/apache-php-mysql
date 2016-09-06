<?php

class DB extends mysqli {
    
    protected static $host      = null;
    protected static $user      = null;
    protected static $pass      = null;
    protected static $dbname    = null;
    protected static $port      = null;
    protected static $socket    = null;
    
    protected static $conn = null;
    
    protected $table = null;
    protected $sql = null;
    protected $resource = null;
    
    public function __construct($host = null, $user = null, $pass = null, $dbname = null, $port = null, $socket = null) {
        
        self::$host     = $host;
        self::$user     = $user;
        self::$pass     = $pass;
        self::$dbname   = $dbname;
        self::$port     = $port;
        self::$socket   = $socket;
        
        parent::__construct(self::$host, self::$user, self::$pass, self::$dbname);
        
    }
    
    public function get($table_name = '', $where_clause = '') {
        
        $table = (isset($table_name) && !empty($table_name)) ? $table_name : (isset($this->table) && !empty($this->table) ? $this->table : '');
        
        $where = ' WHERE 1 ';
        
        if(isset($where_clause) && !empty($where_clause)) {
            $where .= ' AND ( '.$where_clause.' ) ';    
        }
        
        if(empty($table)) return null;
        
        $this->sql = "SELECT * FROM `{$table}` {$where}";
        
        $query = $this->query($this->sql);
        
        if($query->num_rows > 0) {
            $this->resource = $query->fetch_all(MYSQLI_ASSOC);
            return $this->resource;
        }
        
    }
    
    public function add_row($data = null, $table_name = null) {
        
        if(!isset($data) || empty($data)) {
            return null;
        }
        
        $table = (isset($table_name) && !empty($table_name)) ? $table_name : (isset($this->table) && !empty($this->table) ? $this->table : '');
        
        if(empty($table)) return null;
        
        $keys_string = '';
        $values_string = '';
        
        $i = 1;
        foreach($data as $key=>$value) {
            $keys_string   .= '`'.$key.'`';
            $values_string .= "'".$value."'";
            $count = count($data);
            if($i < $count) {
                $keys_string .= ',';
                $values_string .= ',';
            }
            $i++;
        }
        
        $this->sql = "INSERT INTO `{$table}`({$keys_string}) VALUES({$values_string})";
        $query = $this->query($this->sql);
        
        if($query) {
            echo 'Row has been inserted successfully!';
        }
    }
    
    public function edit_row($data = null, $table_name = null, $condition = null) {
        if(!isset($data) || empty($data)) {
            return null;
        }
        
        $table = (isset($table_name) && !empty($table_name)) ? $table_name : (isset($this->table) && !empty($this->table) ? $this->table : '');
        
        if(empty($table)) return null;

        if(!isset($condition) || empty($condition)) return null;
        
        $update_string = ' SET ';
        
        $i = 1;
        foreach($data as $key=>$value) {
            $count = count($data);
            $update_string .= "`".$key."` = '".$value."'";
            if($i < $count) {
                $update_string .= ',';
            }
            $i++;
        }
        
        $this->sql = "UPDATE `{$table}` {$update_string}";
                
        $this->sql .= " WHERE {$condition} ";
        
        $query = $this->query($this->sql);
        if($query) {
            echo 'Row has been updated successfully!';
        }
        
    }
    
    public function delete_row($table_name, $condition) {
        if(!isset($condition) || empty($condition)) return null;
        $this->sql = "DELETE FROM {$table_name} WHERE {$condition}";
        
        $query = $this->query($this->sql);
        if($query) {
            echo 'Row has been deleted successfully!';
        }
    }
    
}

$db = new DB('localhost', 'root', 'root', 'test');



















