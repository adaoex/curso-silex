<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$response = new Response;

$db = new \PDO('sqlite:'.__DIR__.'/../api.db');

$db->exec(
    "create table if not exists clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        nome text   not null, 
        cpf text    null,
        rg text     null,
        email text not null );
    create table if not exists produtos (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        nome text   not null, 
        descricao text    null,
        valor decimal(5,2) null );"
);

/* $app definida em bootstrap.php */
$app['clienteProvider'] = function() use ($db){
    $cliente = new App\Entity\Cliente();
    $mapper = new App\Mapper\ClienteMapper($db);
    return new App\Service\ClienteService($cliente, $mapper);
};

$app['produtoProvider'] = function() use ($db){
    $entity = new App\Entity\Produto();
    $mapper = new App\Mapper\ProdutoMapper($db);
    return new App\Service\ProdutoService($entity, $mapper);
};

/* formulários */
$app->get("/", function() use ($response, $app) {
    $response->setContent("API Silex");
    return $app['twig']->render(
        'index.twig'
    );
})->bind("index");

$app->get("/clientes", function() use ($app) {
    $service = $service = $app['clienteProvider'];
    return $app['twig']->render(
            'cliente/lista.twig', 
            ['clientes' => $service->fetchAll()]
    );
})->bind("lista_clientes");

$app->get("/cliente/novo", function() use ($app) {
    $service = $service = $app['clienteProvider'];
    return $app['twig']->render(
            'cliente/form-cliente.twig',
            ['cliente' => new App\Entity\Cliente()]
    );
})->bind("form_novo_cliente");

$app->get("/cliente/editar/{id}", function($id) use ($app) {
    $service = $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    return $app['twig']->render(
            'cliente/form-cliente.twig',
            ['cliente' => $cliente]
    );
})->bind("form_editar_cliente");


/* API Clientes */
$app->get("/api/clientes", function() use ($app) {
    $service = $service = $app['clienteProvider'];
    return new JsonResponse( $service->fetchAll() );
})->bind("clientes");

$app->post("/api/clientes", function(Request $request) use ($app){
    $dados = [    
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    $service = $app['clienteProvider'];
    $result = $service->insert($dados);
    return new JsonResponse($result);
})->bind("novo_cliente");

$app->get('/api/clientes/{id}', function ($id) use ($app) {
    $service = $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if ( is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return $app->json($cliente);
})
->bind("cliente")
->convert('id', function ($id) { return (int) $id; })
->assert('id', '\d+')
->value('id', 0); /* default values */


$app->put('/api/clientes/{id}', function ( Request $request, $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $dados =  [
        "id"=> $id,
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    $ret = $service->update( $dados );
    return $app->json($ret);
})
->bind("cliente_editar")
->method('PUT|POST');

$app->delete('/api/clientes/{id}', function ( $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $ret = $service->delete($id);
    return $app->json($ret);
})
->bind("cliente_delete")
->method('DELETE|POST');


/* Serviço Produtos */
$app->get("/api/produtos", function() use ($app) {
    $service = $app['produtoProvider'];
    return $app->json( $service->fetchAll() );
})->bind("produtos");

/* post */
$app->post("/api/produtos", function( Request $request ) use ($app){
    
    $dados = [    
        "nome"=> $request->get('nome'),
        "descricao"=> $request->get('descricao'),
        "valor"=> $request->get('valor'),
    ];
    $service = $app['produtoProvider'];
    $result = $service->insert($dados);
    return $app->json($result);
})->bind("novo_produto");

$app->get('/api/produtos/{id}', function ($id) use ($app){
    $service = $app['produtoProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return $app->json($cliente);
})
->bind("produto");

$app->put('/api/produtos/{id}', function ( Request $request, $id) use ($app){
    $service = $app['produtoProvider'];
    $produto  = $service->find($id);
    if (is_null($produto) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $dados =  [    
        "id"=> $id,
        "nome"=> $request->get('nome'),
        "descricao"=> $request->get('descricao'),
        "valor"=> $request->get('valor'),
    ];
    $ret = $service->update( $dados );
    return $app->json($ret);
})
->bind("produto_editar");

$app->delete('/api/produtos/{id}', function ( $id) use ($app){
    $service = $app['produtoProvider'];
    $produto  = $service->find($id);
    if (is_null($produto) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $ret = $service->delete($id);
    return $app->json($ret);
})
->bind("produto_delete");

$app->run();