<?php 
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();
$app['debug'] = true;

$response = new Response;

$app->get("/", function() use ($response) {
    $response->setContent("Primeira Rota Silex /");
    return $response;
});

$clientes = [
    ["nome"=>"Industria ABC","email"=>"abc@abc.com","cpf_cnpj"=>"001212"],
    ["nome"=>"João da Silva","email"=>"joao@abc.com","cpf_cnpj"=>"0033"],
    ["nome"=>"José Maria","email"=>"jose@abc.com","cpf_cnpj"=>"0055"],
];

$app->get("/clientes", function() use ($clientes) {
    return new JsonResponse($clientes);
});

$app->run();