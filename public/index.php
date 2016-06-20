<?php 
$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}
require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

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
    $constraint = new Assert\Collection(array(
        'nome' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3, 'max' => 255))
        ),
        'email' => array(
            new Assert\NotBlank(), 
            new Assert\Email(),
        ),
        'cpf' => array(
            new Assert\NotBlank(), 
            new App\Validator\Cpf(),
        ),
        'rg' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3))
        ),
    ));
    
    $errors = $app['validator']->validate($dados, $constraint);
    if (count($errors) > 0) {
        $arr_erros = [];
        foreach ($errors as $error) {
            $arr_erros[] = $error->getPropertyPath().' '.$error->getMessage();
        }
        return new JsonResponse(array(
            'errors' => $arr_erros
        ));
    } 
    
    $service = $app['clienteProvider'];
    $result = $service->insert($dados);
    return new JsonResponse($result);
})->bind("novo_cliente");

$app->get('/api/clientes/{id}', function ($id) use ($app) {
    $service = $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        return $app->json(array(
            'errors' => ["Erro: Registro com ID = $id não existe!"]
        ));
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
        return $app->json(array(
            'errors' => ["Erro: Registro com ID = $id não existe!"]
        ));
    }
    $dados =  [
        "nome"=> $request->get('nome'),
        "email"=> $request->get('email'),
        "cpf"=> $request->get('cpf'),
        "rg"=> $request->get('rg'),
    ];
    $constraint = new Assert\Collection(array(
        'nome' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3, 'max' => 255))
        ),
        'email' => array(
            new Assert\NotBlank(), 
            new Assert\Email(),
        ),
        'cpf' => array(
            new Assert\NotBlank(), 
            new App\Validator\Cpf(),
        ),
        'rg' => array(
            new Assert\NotBlank(), 
            new Assert\Length(array('min' => 3))
        ),
    ));
    
    $errors = $app['validator']->validate($dados, $constraint);
    if (count($errors) > 0) {
        $arr_erros = [];
        foreach ($errors as $error) {
            $arr_erros[] = $error->getPropertyPath().' '.$error->getMessage();
        }
        return new JsonResponse(array(
            'errors' => $arr_erros
        ));
    } 
    
    $dados['id'] = $id;
    
    $ret = $service->update( $dados );
    return $app->json($ret);
})
->bind("cliente_editar")
->assert('id', '\d+')
->method('PUT|POST');

$app->delete('/api/clientes/{id}', function ( $id) use ($app){
    $service = $app['clienteProvider'];
    $cliente  = $service->find($id);
    if (is_null($cliente) ){
        return $app->json(array(
            'errors' => ["Erro: Registro com ID = $id não existe!"]
        ));
    }
    $ret = $service->delete($id);
    return $app->json($ret);
})
->bind("cliente_delete")
->assert('id', '\d+')
->method('DELETE|POST');

$app->run();