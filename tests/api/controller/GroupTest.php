<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\AuthGroup as GroupController;

require_once('./app/api/common.php');

class GroupTest extends \PHPUnit\Framework\TestCase
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

    public function testGroupHome()
    {
        $this->setUpRequest();
        
        $groupController = new GroupController($this->app);

        $response = $groupController->home();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupAdd()
    {
        $this->setUpRequest();
        $adminController = new GroupController($this->app);
        $response = $adminController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupSave()
    {
        $this->setUpRequest();
        $adminController = new GroupController($this->app);
        $response = $adminController->save();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupRead()
    {
        $this->setUpRequest();
        $adminController = new GroupController($this->app);
        $response = $adminController->read(53);
        $responseNotExist = $adminController->read(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testGroupUpdate()
    {
        $this->setUpRequest('PUT', ['group_name' => 'Admin Group']);
        $adminController = new GroupController($this->app);
        $response = $adminController->update(53);
        $responseNotExist = $adminController->update(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testGroupDelete()
    {
        $this->setUpRequest('POST', ['type' => 'delete', 'ids' => [53]]);
        $adminController = new GroupController($this->app);
        $response = $adminController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupRestore()
    {
        $this->setUpRequest('POST', ['ids' => [53]]);
        $adminController = new GroupController($this->app);
        $response = $adminController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
