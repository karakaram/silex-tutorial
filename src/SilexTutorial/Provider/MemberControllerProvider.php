<?php

namespace SilexTutorial\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class MemberControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/register', function(Application $app) {
            return $app['twig']->render('member/register.html.twig');
        });

        $controllers->post('/register', function(Application $app) {
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

        return $controllers;
    }
}
