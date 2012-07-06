<?php
require_once __DIR__.'/../vendor/autoload.php'; 

$app = new Silex\Application(); 
$app['debug'] = true;

// twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));
// doctrine
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'silex',
        'host' => 'localhost',
        'user' => 'admin',
        'password' => 'admin'
    )
));
// member
$app->register(new SilexTutorial\Provider\MemberServiceProvider());
$app->mount('/member', new SilexTutorial\Provider\MemberControllerProvider());

$app->get('/', function() use($app) {
})
->bind('homepage');

$app->run(); 
