<?php

namespace App\Entity;

/**
 * Entity Cliente
 *
 * @author adao.goncalves
 */
class Cliente
{

    private $id;
    private $nome;
    private $email;
    private $cnpj;
    private $cpf;

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getCnpj()
    {
        return $this->cnpj;
    }

    function getCpf()
    {
        return $this->cpf;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    function setCpf($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }

}
