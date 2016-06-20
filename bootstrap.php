<?php
require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;

$app = new Application();
$app['debug'] = true;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ),
));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\AssetServiceProvider());