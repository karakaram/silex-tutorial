<?php
require_once __DIR__.'/../vendor/autoload.php'; 

$app = new Silex\Application(); 
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'silex',
        'host' => 'localhost',
        'user' => '',
        'password' => ''
    )
));

$app->get('/member/register', function() use($app) { 
    return $app['twig']->render('member/register.html.twig');
}); 

$app->post('/member/register', function() use($app){
    $request = $app['request'];
    $member = $request->get('member');

    $stmt = $app['db']->prepare("
       INSERT INTO member SET
         email = :email
        ,password = :password
    ");
    $stmt->bindParam(':email', $member['email']);
    $password = md5($member['password']);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    return $app['twig']->render('member/finish.html.twig', array(
        'member' => $member
    ));
});

$app->run(); 
