<?php

namespace App\Entity;

class Produto 
{
    private $id;
    private $nome;
    private $descricao;
    private $valor;
    
    function getId()
    {
        return $this->id;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getDescricao()
    {
        return $this->descricao;
    }

    function getValor()
    {
        return $this->valor;
    }

    function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }
    
}
