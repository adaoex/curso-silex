<?php 
require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

$response = new Response;

/* $app definida em bootstrap.php */
$app['clienteProvider'] = function(){
    $cliente = new App\Entity\Cliente();
    $mapper = new App\Mapper\ClienteMapper();
    return new App\Service\ClienteService($cliente, $mapper);
};

$app['produtoProvider'] = function(){
    $entity = new App\Entity\Produto();
    $mapper = new App\Mapper\ProdutoMapper();
    return new App\Service\ProdutoService($entity, $mapper);
};

$app->get("/", function() use ($response) {
    $response->setContent("Primeira Rota Silex /");
    return $response;
})->bind("index");

/* serviço clientes */
$app->get("/clientes", function() use ($app) {
    $service = $service = $app['clienteProvider'];
    return new JsonResponse( $service->fetchAll() );
})->bind("clientes");

/* post */
$app->post("/cliente", function() use ($app){
    $dados = ["nome"=>"Cliente 01","email"=>"cli01@gmail.com", "cpf"=> 0123];
    $service = $app['clienteProvider'];
    $result = $service->insert($dados);
    return new JsonResponse($result);
})->bind("novo_cliente");

$app->get('/cliente/{id}', function (Silex\Application $app, $id) {
    $service = $service = $app['clienteProvider'];
    $clientes  = $service->fetchAll();
    if ( !isset($clientes[$id])){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return $app['twig']->render(
        'detalhe.twig', 
        ["cliente" => $clientes[$id]] 
    );
})
->bind("cliente")
->convert('id', function ($id) { return (int) $id; })
->assert('id', '\d+')
->value('id', 0); /* default values */


/* serviço produto */
$app->get("/produtos", function() use ($app) {
    $service = $service = $app['produtoProvider'];
    return new JsonResponse( $service->fetchAll() );
})->bind("produtos");

/* post */
$app->post("/produto", function() use ($app){
    $dados = [
        "nome"=>"Produto 01",
        "descricao"=>"Descrição do produto 01",
        "valor"=> 123];
    $service = $app['clienteProvider'];
    $result = $service->insert($dados);
    return new JsonResponse($result);
})->bind("novo_cliente");

$app->run();