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
    
    public function insert(Cliente $cliente)
    {
        $this->stmt = $this->db->prepare(
            "insert into clientes (nome, cpf, cnpj, rg, email) 
             values (:nome, :cpf, :cnpj, :rg, :email )"
        );
        $this->stmt->bindParam(':nome',$cliente->getNome());
        $this->stmt->bindParam(':cpf',$cliente->getCpf());
        $this->stmt->bindParam(':cnpj',$cliente->getCnpj());
        $this->stmt->bindParam(':rg',$cliente->getRg());
        $this->stmt->bindParam(':email',$cliente->getEmail());
        
        $id = $this->flush();
        
        $cliente->setId( $id );
        
        return $cliente;
    }

    public function fetchAll()
    {
        $sql = "SELECT * FROM clientes";
        $clientes = [];
        foreach ( $this->db->query($sql) as $r) {
            $clientes[] = [
                'id' => $r['id'],
                'cpf' => $r['cpf'],
                'cnpj' => $r['cnpj'],
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
                'cnpj' => $r['cnpj'],
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

    public function update(Cliente $cliente)
    {
        $this->stmt = $this->db->prepare(
            "update clientes 
                set nome = :nome, 
                cpf = :cpf, 
                cnpj = :cnpj, 
                rg = :rg, 
                email = :email
            where id = :id "
        );
        $this->stmt->bindParam(':id',$cliente->getId());
        $this->stmt->bindParam(':nome',$cliente->getNome());
        $this->stmt->bindParam(':cpf',$cliente->getCpf());
        $this->stmt->bindParam(':cnpj',$cliente->getCnpj());
        $this->stmt->bindParam(':rg',$cliente->getRg());
        $this->stmt->bindParam(':email',$cliente->getEmail());
        
        $this->flush();
        
        return $cliente;
    }

}
