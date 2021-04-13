<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\Admin as AdminController;

require_once('./app/api/common.php');

class AdminTest extends \PHPUnit\Framework\TestCase
{
    use \tests\BaseRequest;

    protected $request;
    protected $app;
    protected $response;

    protected function setUp(): void
    {
    }
    protected function tearDown(): void
    {
        // $this->app->http->end($this->response);
    }

    public function testAdminHome()
    {
        $this->setUpRequest();
        
        $adminController = new AdminController($this->app);

        $response = $adminController->home();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminAdd()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->add();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminSave()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->save();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminRead()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->read(206);
        $responseNotExist = $adminController->read(1);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
    }

    public function testAdminUpdate()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->update(206);
        $responseNotExist = $adminController->update(1);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
    }

    public function testAdminDelete()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->delete();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminRestore()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->restore();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminLogin()
    {
        $this->setUpRequest('POST', ['username' => 'admin0', 'password' => 'admin0']);
        $adminController = new AdminController($this->app);
        $response = $adminController->login();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminLogout()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->logout();

        $this->assertEquals(200, $response->getCode());
    }

    public function testAdminInfo()
    {
        // not login
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->info();
        $this->assertEquals(401, $response->getCode());

        // login
        $this->setUpRequest('POST', ['username' => 'admin', 'password' => 'admin']);
        $adminController = new AdminController($this->app);
        $response = $adminController->login();
        $response = $adminController->info();
        $this->assertEquals(200, $response->getCode());
    }
}
