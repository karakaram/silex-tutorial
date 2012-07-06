<?php

namespace SilexTutorial\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SilexTutorial\Service\Member;

class MemberServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['member'] = $app->share(function() use($app) {
            return new Member($app['db']);
        });
    }

    public function boot(Application $app)
    {
    }
}
