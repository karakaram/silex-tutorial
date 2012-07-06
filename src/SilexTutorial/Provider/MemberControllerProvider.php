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
            $data = $app['request']->get('member');

            $app['member']->register($data);

            return $app['twig']->render('member/finish.html.twig', array(
                'member' => $data
            ));
        });

        return $controllers;
    }
}
