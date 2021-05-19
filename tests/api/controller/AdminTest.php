<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\Admin as AdminController;

require_once('./app/api/common.php');

class AdminTest extends \tests\api\TestCase
{
    protected function tearDown(): void
    {
        $this->endRequest();
    }

    public function testAdminHome()
    {
        $this->startRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // trash
        $this->startRequest('GET', ['trash' => 'onlyTrashed']);
        $adminController = new AdminController($this->app);
        $response = $adminController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // search
        $this->startRequest('GET', ['admin_name' => 'unit', 'display_name' => 'unit', 'groups' => 1, 'create_time' => '2021-04-07T23:31:51+08:00,2021-04-14T23:31:51+08:00']);
        $adminController = new AdminController($this->app);
        $response = $adminController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminAdd()
    {
        $this->startRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminSave()
    {
        $validData = ['admin_name' => 'UnitTest', 'password' => 'UnitTest', 'display_name' => 'UnitTest'];
        $this->startRequest('POST', $validData);
        $adminController = new AdminController($this->app);
        $response = $adminController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // already exists
        $this->startRequest('POST', ['admin_name' => 'UnitTest']);
        $adminController = new AdminController($this->app);
        $responseExists = $adminController->save();
        $this->assertEquals(200, $responseExists->getCode());
        $this->assertStringStartsWith('{"success":false', $responseExists->getContent());
    }

    public function testAdminRead()
    {
        $this->startRequest();
        // valid
        $adminController = new AdminController($this->app);
        $response = $adminController->read(2);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringContainsString('"id":2,"admin_name":"UnitTest"', $response->getContent());
        // not exist
        $responseNotExist = $adminController->read(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testAdminUpdate()
    {
        $this->startRequest('PUT', ['display_name' => 'UnitTest2']);
        // valid
        $adminController = new AdminController($this->app);
        $response = $adminController->update(2);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // not exist
        $responseNotExist = $adminController->update(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testAdminDelete()
    {
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [2]]);
        $adminController = new AdminController($this->app);
        $response = $adminController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminRestore()
    {
        $this->startRequest('POST', ['ids' => [2]]);
        $adminController = new AdminController($this->app);
        $response = $adminController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminLogin()
    {
        // valid
        $this->startRequest('POST', ['username' => 'UnitTest', 'password' => 'UnitTest']);
        $adminController = new AdminController($this->app);
        $response = $adminController->login();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // invalid
        $this->startRequest('POST', ['username' => 'UnitTest', 'password' => 'WrongPassword']);
        $adminController = new AdminController($this->app);
        $response = $adminController->login();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":false', $response->getContent());
    }

    public function testAdminLogout()
    {
        $this->startRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->logout();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testAdminInfo()
    {
        // not login
        $this->startRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->info();
        $this->assertEquals(401, $response->getCode());
        $this->assertStringStartsWith('{"data":{"isLogin":false}', $response->getContent());

        // login
        $this->startRequest('POST', ['username' => 'UnitTest', 'password' => 'UnitTest']);
        $adminController = new AdminController($this->app);
        $adminController->login();
        $response = $adminController->info();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"name":"UnitTest"', $response->getContent());
    }

    public function testAdminDeletePermanently()
    {
        $this->startRequest('POST', ['type' => 'deletePermanently', 'ids' => [2]]);
        $adminController = new AdminController($this->app);
        $response = $adminController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
