<?php 
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

$app->get("/", function() use ($response, $app) {
    $response->setContent("API Silex");
    return $app['twig']->render(
        'index.twig'
    );
})->bind("index");

/* serviço clientes */
$app->get("/api/clientes", function() use ($app) {
    $service = $service = $app['clienteProvider'];
    return new JsonResponse( $service->fetchAll() );
})->bind("clientes");

$app->post("/api/cliente", function() use ($app){
    $dados = ["nome"=>"Cliente 01","email"=>"cli01@gmail.com", "cpf"=> 0123];
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

$app->get('/api/cliente/{id}', function ($id) use ($app) {
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


/* Serviço Produtos */
$app->get("/api/produtos", function() use ($app) {
    $service = $app['produtoProvider'];
    return $app->json( $service->fetchAll() );
})->bind("produtos");

/* post */
$app->post("/api/produto", function( Request $request ) use ($app){
    
    $dados = [    
        "nome"=> $request->get('nome'),
        "descricao"=> $request->get('descricao'),
        "valor"=> $request->get('valor'),
    ];
    $service = $app['produtoProvider'];
    $result = $service->insert($dados);
    return $app->json($result);
})->bind("novo_produto");

$app->get('/api/produto/{id}', function ($id) use ($app){
    $service = $app['produtoProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    return $app->json($cliente);
})
->bind("produto");

$app->put('/api/produto/{id}', function ( Request $request, $id) use ($app){
    $service = $app['produtoProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $dados =  [    
        "nome"=> $request->get('nome'),
        "descricao"=> $request->get('descricao'),
        "valor"=> $request->get('valor'),
    ];
    $ret = $service->update( $dados );
    return $app->json($ret);
})
->bind("produto_editar");

$app->delete('/api/produto/{id}', function ( $id) use ($app){
    $service = $app['produtoProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        $app->abort(404, "Erro: ID $id não existe!");
    }
    $ret = $service->delete($id);
    return $app->json($ret);
})
->bind("produto_delete");

$app->run();