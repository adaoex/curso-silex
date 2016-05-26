<?php

namespace App\Mapper;

use App\Entity\Produto;

class ProdutoMapper
{

    public function insert(Produto $produto)
    {
        return ["nome"=>"Produto ABC","descricao"=>"Desc. produto 01","valor"=> 20.50];
    }

    public function fetchAll()
    {
        $produtos = [
            ["nome"=>"Produto ABC","descricao"=>"Desc. produto 01","valor"=> 20.50],
            ["nome"=>"Produto 055","descricao"=>"Desc. produto 02","valor"=>500.8],
            ["nome"=>"Produto 808","descricao"=>"Desc. produto 03","valor"=>5800.90],
        ];
        return $produtos;
    }

}
