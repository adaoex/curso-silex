<?php

namespace App\Mapper;

use App\Entity\Cliente;

/**
 * Mapper ClienteMapper
 *
 * @author adao.goncalves
 */
class ClienteMapper
{

    public function insert(Cliente $cliente)
    {
        return [
            'nome' => 'Cliente 01',
            'email' => 'cliente01@gmail.com',
            'cpf' => null,
            'cnpj' => 5050505,
        ];
    }

    public function fetchAll()
    {
        $clientes = [
            ["nome"=>"Industria ABC","email"=>"abc@abc.com","cnpj"=>"001212"],
            ["nome"=>"João da Silva","email"=>"joao@abc.com","cpf"=>"0033"],
            ["nome"=>"José Maria","email"=>"jose@abc.com","cpf"=>"0055"],
        ];
        return $clientes;
    }

}
