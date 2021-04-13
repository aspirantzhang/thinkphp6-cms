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
        $this->app->http->end($this->response);
    }

    public function testAdminHome()
    {
        $this->setUpRequest();
        
        $adminController = new AdminController($this->app);

        $response = $adminController->home();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminAdd()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminSave()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->save();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminRead()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->read(1);
        $responseNotExist = $adminController->read(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testAdminUpdate()
    {
        $this->setUpRequest('PUT', ['display_name' => 'Admin']);
        $adminController = new AdminController($this->app);
        $response = $adminController->update(1);
        $responseNotExist = $adminController->update(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testAdminDelete()
    {
        $this->setUpRequest('POST', ['type' => 'delete', 'ids' => [1]]);
        $adminController = new AdminController($this->app);
        $response = $adminController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminRestore()
    {
        $this->setUpRequest('POST', ['ids' => [1]]);
        $adminController = new AdminController($this->app);
        $response = $adminController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminLogin()
    {
        $this->setUpRequest('POST', ['username' => 'admin', 'password' => 'admin']);
        $adminController = new AdminController($this->app);
        $response = $adminController->login();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminLogout()
    {
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->logout();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminInfo()
    {
        // not login
        $this->setUpRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->info();
        $this->assertEquals(401, $response->getCode());
        $this->assertStringStartsWith('{"data":{"isLogin":false}', $response->getContent());

        // login
        $this->setUpRequest('POST', ['username' => 'admin', 'password' => 'admin']);
        $adminController = new AdminController($this->app);
        $response = $adminController->login();
        $response = $adminController->info();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"name":"admin"', $response->getContent());
    }
}
