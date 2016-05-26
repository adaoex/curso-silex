<?php

namespace App\Mapper;

use App\Entity\Produto;

class ProdutoMapper extends MapperAbstract
{

    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }
    
    public function insert(Produto $p)
    {
        $this->stmt = $this->db->prepare(
            "insert into produtos (nome, descricao, valor) 
             values (:nome, :descricao, :valor)"
        );
        $nome = $p->getNome();
        $desc = $p->getDescricao();
        $valor = $p->getValor();
        $this->stmt->bindParam(':nome',$nome);
        $this->stmt->bindParam(':descricao',$desc);
        $this->stmt->bindParam(':valor',$valor);
        
        $id = $this->flush();
        
        $p->setId( $id );
        
        return $p;
    }

    public function fetchAll()
    {
        
        $sql = "SELECT id, nome, descricao, valor FROM produtos";
        $produtos = [];
        foreach ( $this->db->query($sql) as $r) {
            $produtos[] = [
                'id' => $r['id'],
                'nome' => $r['nome'],
                'descricao' => $r['descricao'],
                'valor' => $r['valor'],
            ];
        }
        
        return $produtos;
    }
    
    public function find($id)
    {
        $sql = "SELECT * FROM produtos where id = $id";
        $p = null;
        foreach ( $this->db->query($sql) as $r) {
            $p = [
                'id' => $r['id'],
                'nome' => $r['nome'],
                'descricao' => $r['descricao'],
                'valor' => $r['valor'],
            ];
        }
        return $p;
    }

    public function delete($id)
    {
        return $this->db->exec("delete from produtos where id = $id");
    }

    public function update(Produto $p)
    {
        $this->stmt = $this->db->prepare(
            "update produtos 
                set nome = :nome, 
                descricao = :descricao, 
                valor = :valor, 
            where id = :id "
        );
        $this->stmt->bindParam(':id',$p->getId());
        $this->stmt->bindParam(':nome',$p->getNome());
        $this->stmt->bindParam(':descricao',$p->getDescricao());
        $this->stmt->bindParam(':valor',$p->getValor());
        
        $this->flush();
        
        return $p;
    }


}
