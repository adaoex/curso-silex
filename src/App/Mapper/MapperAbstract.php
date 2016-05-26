<?php

namespace App\Mapper;

abstract class MapperAbstract
{
    protected $db;

    protected $stmt;
    
    public function __construct( \PDO $db )
    {
        $this->db = $db;
    }
    
    public function flush( )
    {
        if ( $this->stmt ==! null) {
            $this->stmt->execute();
            return $this->db->lastInsertId();
        }
    }
    
}
