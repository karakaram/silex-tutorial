<?php
require_once __DIR__.'/../vendor/autoload.php'; 

$app = new Silex\Application(); 
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/../views',
));

$app->get('/member/register', function() use($app) { 
    return $app['twig']->render('member/register.html.twig');
}); 

$app->run(); 
