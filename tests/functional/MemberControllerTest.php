<?php
require_once __DIR__.'/../../vendor/autoload.php'; 

use Silex\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    private $db;
    private $member;

    public function __construct()
    {
        $app = new Silex\Application();
        $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_mysql',
                'dbname'   => 'silex',
                'host'     => 'localhost',
                'user'     => 'admin',
                'password' => 'admin'
            ),
        ));

        $this->db = $app['db'];
        $this->db->exec("TRUNCATE TABLE member");
    }

    public function createApplication()
    {
        require __DIR__ . '/../../web/index.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }

    public function testMemberRegistration()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/member/register');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertSame(1, $crawler->filter('title:contains("会員登録")')->count());

        $form = $crawler->filter('#register_submit')->form();
        $data = array(
            'member[email]' => 'sample@example.com',
            'member[password]' => 'sample',
        );
        $crawler = $client->submit($form, $data);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertSame(1, $crawler->filter('title:contains("会員登録完了")')->count());

    }
}
