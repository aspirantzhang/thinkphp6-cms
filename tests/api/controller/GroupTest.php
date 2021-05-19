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
        // trash
        $this->startRequest('GET', ['trash' => 'onlyTrashed']);
        $groupController = new GroupController($this->app);
        $response = $groupController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // search
        $this->startRequest('GET', ['group_title' => 'unit']);
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
        $validData = ['group_title' => 'UnitTest'];
        $this->startRequest('POST', $validData);
        $groupController = new GroupController($this->app);
        $response = $groupController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // already exists
        $validData2 = ['group_title' => 'UnitTest'];
        $this->startRequest('POST', $validData2);
        $groupController2 = new GroupController($this->app);
        $response2 = $groupController2->save();
        $this->assertEquals(200, $response2->getCode());
        $this->assertStringStartsWith('{"success":false', $response2->getContent());
    }

    public function testGroupRead()
    {
        $this->startRequest();
        // valid
        $groupController = new GroupController($this->app);
        $response = $groupController->read(2);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringContainsString('"group_title":"UnitTest"', $response->getContent());
        // not exist
        $responseNotExist = $groupController->read(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testGroupUpdate()
    {
        $this->startRequest('PUT', ['group_title' => 'UnitTest2']);
        // valid
        $groupController = new GroupController($this->app);
        $response = $groupController->update(2);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // not exist
        $responseNotExist = $groupController->update(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testGroupDelete()
    {
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [2]]);
        $groupController = new GroupController($this->app);
        $response = $groupController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testGroupRestore()
    {
        $this->startRequest('POST', ['ids' => [2]]);
        $groupController = new GroupController($this->app);
        $response = $groupController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
