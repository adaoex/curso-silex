<?php

namespace App\Service;

use App\Entity\Produto;
use App\Mapper\ProdutoMapper;

class ProdutoService
{

    private $entity;
    private $mapper;

    public function __construct(Produto $entity, ProdutoMapper $mapper)
    {
        $this->entity = $entity;
        $this->mapper = $mapper;
    }

    public function insert(array $dados)
    {
        $this->entity->setNome($dados['nome']);
        $this->entity->setDescricao( $dados['descricao'] );
        $this->entity->setValor( $dados['valor'] );
        
        $res = $this->mapper->insert($this->entity);

        return [
            'success' =>  true, 
            'msg' => "Cliente inserido com sucesso "
        ];
    }

    public function update(array $dados)
    {
        $this->entity->setNome($dados['nome']);
        $this->entity->setDescricao( $dados['descricao'] );
        $this->entity->setValor( $dados['valor'] );
        
        $res = $this->mapper->insert($this->entity);

        return [
            'success' =>  true, 
            'msg' => "Cliente  atualizado com sucesso "
        ];
    }
    
    public function delete($id)
    {
        return $this->mapper->delete($id);
    }
    
    public function fetchAll()
    {
        return $this->mapper->fetchAll();
    }

    public function find($id)
    {
        return $this->mapper->find($id);
    }
    
}
