<?php

namespace App\Service;

use App\Entity\Cliente;
use App\Mapper\ClienteMapper;

/**
 * Description of ClienteService
 *
 * @author adao.goncalves
 */
class ClienteService
{

    private $cliente;
    private $mapper;

    public function __construct(Cliente $cliente, ClienteMapper $mapper)
    {
        $this->cliente = $cliente;
        $this->mapper = $mapper;
    }

    public function insert(array $dados)
    {
        $res = $this->mapper->insert($dados);

        return [
            'success' =>  true, 
            'msg' => "Cliente inserido com sucesso "
        ];
    }
    
    public function update(array $dados)
    {
        
        $res = $this->mapper->update($dados);

        return [
            'success' =>  true, 
            'msg' => "Cliente atualizado com sucesso "
        ];
    }

    public function fetchAll()
    {
        return $this->mapper->fetchAll();
    }

    public function delete($id)
    {
        return $this->mapper->delete($id);
    }
    
    public function find($id)
    {
        return $this->mapper->find($id);
    }
    
}
