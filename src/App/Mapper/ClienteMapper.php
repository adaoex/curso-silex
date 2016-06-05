<?php

namespace App\Mapper;

use App\Entity\Cliente;

/**
 * Mapper ClienteMapper
 *
 * @author adao.goncalves
 */
class ClienteMapper extends MapperAbstract
{
    
    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }
    
    public function insert(array $arr)
    {
        $this->stmt = $this->db->prepare(
            "insert into clientes (nome, cpf, rg, email) 
             values (:nome, :cpf, :rg, :email )"
        );
        $this->stmt->bindParam(':nome',$arr['nome']);
        $this->stmt->bindParam(':cpf',$arr['cpf']);
        $this->stmt->bindParam(':rg',$arr['rg']);
        $this->stmt->bindParam(':email',$arr['email']);
        
        $id = $this->flush();
        
        return $id;
    }

    public function fetchAll()
    {
        $sql = "SELECT * FROM clientes";
        $clientes = [];
        foreach ( $this->db->query($sql) as $r) {
            $clientes[] = [
                'id' => $r['id'],
                'cpf' => $r['cpf'],
                'rg' => $r['rg'],
                'email' => $r['email'],
                'nome' => $r['nome'],
            ];
        }
        return $clientes;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM clientes where id = $id";
        $cliente = null;
        foreach ( $this->db->query($sql) as $r) {
            $cliente = [
                'id' => $r['id'],
                'cpf' => $r['cpf'],
                'rg' => $r['rg'],
                'email' => $r['email'],
                'nome' => $r['nome'],
            ];
        }
        return $cliente;
    }
    
    public function delete($id)
    {
        $this->db->exec("delete from clientes where id = $id");
    }

    public function update(array $arr)
    {
        $this->stmt = $this->db->prepare(
            "update clientes 
                set nome = :nome, 
                cpf = :cpf, 
                rg = :rg, 
                email = :email
            where id = :id "
        );
        
        $this->stmt->bindParam(':id',$arr['id'], \PDO::PARAM_INT);
        $this->stmt->bindParam(':nome',$arr['nome']);
        $this->stmt->bindParam(':cpf',$arr['cpf']);
        $this->stmt->bindParam(':rg',$arr['rg']);
        $this->stmt->bindParam(':email',$arr['email']);
        
        $id = $this->flush();
        
        return $id;
    }

}
