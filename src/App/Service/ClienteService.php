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
        $this->cliente->setNome($dados['nome']);
        $this->cliente->setCnpj(isset($dados['cnpj']) ? $dados['cnpj'] : null);
        $this->cliente->setCpf(isset($dados['cpf']) ? $dados['cpf'] : null);
        $this->cliente->setEmail($dados['email']);

        $res = $this->mapper->insert($this->cliente);

        return [
            'success' =>  true, 
            'msg' => "Produto '{$dados['nome']}' inserido com sucesso "
        ];
    }
    
    public function update(array $dados)
    {
        $this->cliente->setNome($dados['nome']);
        $this->cliente->setCnpj(isset($dados['cnpj']) ? $dados['cnpj'] : null);
        $this->cliente->setCpf(isset($dados['cpf']) ? $dados['cpf'] : null);
        $this->cliente->setEmail($dados['email']);

        $res = $this->mapper->update($this->cliente);

        return [
            'success' =>  true, 
            'msg' => "Cliente '{$dados['nome']}' atualizado com sucesso "
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
