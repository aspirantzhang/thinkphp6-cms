<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\Model as ModelController;

require_once('./app/api/common.php');

class ModelTest extends \tests\api\TestCase
{

    protected function tearDown(): void
    {
        $this->endRequest();
    }

    public function testModelHome()
    {
        $this->startRequest();
        
        $modelController = new ModelController($this->app);

        $response = $modelController->home();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testModelAdd()
    {
        $this->startRequest();
        $modelController = new ModelController($this->app);
        $response = $modelController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testModelSave()
    {
        $this->startRequest('POST', ['table_name' => 'test', 'route_name' => 'tests', 'title' => 'User']);
        $modelController = new ModelController($this->app);
        $response = $modelController->save();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testModelRead()
    {
        $this->startRequest();
        $modelController = new ModelController($this->app);
        $response = $modelController->read(1);
        $responseNotExist = $modelController->read(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testModelUpdate()
    {
        $this->startRequest('PUT', ['var' => 'whatever']);
        $modelController = new ModelController($this->app);
        $response = $modelController->update(1);
        $responseNotExist = $modelController->update(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testModelDelete()
    {
        $this->startRequest('POST', ['type' => 'deletePermanently', 'ids' => [1]]);
        $modelController = new ModelController($this->app);
        $response = $modelController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
