<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\AuthGroup as GroupController;

require_once('./app/api/common.php');

class GroupTest extends \tests\api\TestCase
{

    protected function tearDown(): void
    {
        $this->endRequest();
    }

    public function testGroupHome()
    {
        $this->startRequest();
        
        $groupController = new GroupController($this->app);

        $response = $groupController->home();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupAdd()
    {
        $this->startRequest();
        $groupController = new GroupController($this->app);
        $response = $groupController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupSave()
    {
        $this->startRequest();
        $groupController = new GroupController($this->app);
        $response = $groupController->save();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupRead()
    {
        $this->startRequest();
        $groupController = new GroupController($this->app);
        $response = $groupController->read(53);
        $responseNotExist = $groupController->read(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testGroupUpdate()
    {
        $this->startRequest('PUT', ['group_name' => 'Admin Group']);
        $groupController = new GroupController($this->app);
        $response = $groupController->update(53);
        $responseNotExist = $groupController->update(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testGroupDelete()
    {
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [53]]);
        $groupController = new GroupController($this->app);
        $response = $groupController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupRestore()
    {
        $this->startRequest('POST', ['ids' => [53]]);
        $groupController = new GroupController($this->app);
        $response = $groupController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
