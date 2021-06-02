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
        // valid
        $this->startRequest('POST', ['model_name' => 'unit_test', 'model_title' => 'Unit Test', 'create_time' => '2021-04-16T13:25:32+08:00']);
        $modelController = new ModelController($this->app);
        $response = $modelController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->endRequest();
        // invalid
        $this->startRequest('POST', ['model_name' => 'admin', 'model_title' => 'Admin']);
        $modelController = new ModelController($this->app);
        $response = $modelController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":false', $response->getContent());
        // exist modelName
        $this->startRequest('POST', ['model_name' => 'unit_test', 'model_title' => 'Unit Test']);
        $modelController = new ModelController($this->app);
        $response = $modelController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":false', $response->getContent());
        $this->endRequest();
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

    public function testModelDesignRead()
    {
        $this->startRequest();
        $modelController = new ModelController($this->app);
        $response = $modelController->design(1);
        $responseNotExist = $modelController->design(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testModelDesignUpdate()
    {
        $this->startRequest('PUT', json_decode('{"type":"field","data":{"fields":[{"name":"nickname","title":"Nick Name","type":"input","settings":{"validate":["require","length"],"options":{"length":{"min":"0","max":"32"}}},"allowHome":"1","allowRead":"1","allowSave":"1","allowUpdate":"1","allowTranslate":"1"},{"name":"gender","title":"Gender","type":"radio","data":[{"title":"Mx","value":"mx"},{"title":"Mr","value":"mr"},{"title":"Ms","value":"ms"}],"settings":{"validate":["require"]},"allowHome":"1","allowRead":"1","allowSave":"1","allowUpdate":"","allowTranslate":""},{"name":"married","title":"Married","type":"switch","hideInColumn":"1","data":[{"title":"Yes","value":"1"},{"title":"No","value":"0"}],"settings":{"display":["listSorter"],"validate":["require"]},"allowHome":"1","allowRead":"1","allowSave":"1","allowUpdate":"1"}],"listAction":[{"name":"edit","title":"Edit","type":"primary","call":"modal","uri":"/api/unit_test/:id","method":"get"},{"name":"page-edit","title":"Page Edit","type":"default","call":"page","uri":"/api/unit_test/:id","method":"get"},{"name":"delete","title":"Delete","type":"default","call":"delete","uri":"/api/unit_test/delete","method":"post"}],"addAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit_test","method":"post"}],"editAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit_test/:id","method":"put"}],"tableToolbar":[{"name":"add","title":"Add","type":"primary","call":"modal","uri":"/api/unit_test/add","method":"get"},{"name":"page-add","title":"Page Add","type":"default","call":"page","uri":"/api/unit_test/add","method":"get"},{"name":"reload","title":"Reload","type":"default","call":"reload","method":"get"}],"batchToolbar":[{"name":"delete","title":"Delete","type":"danger","call":"delete","uri":"/api/unit_test/delete","method":"post"},{"name":"disabled","title":"Disabled","type":"default","call":"disabled","uri":"/api/unit_test/disable","method":"post"}],"batchToolbarTrashed":[{"name":"delete-permanently","title":"Delete Permanently","type":"danger","call":"deletePermanently","method":"post","uri":"/api/unit_test/delete"},{"name":"restore","title":"Restore","type":"default","call":"restore","uri":"/api/unit_test/restore","method":"post"}],"modelName":"unit_test"}}', true));
        $modelController = new ModelController($this->app);
        $response = $modelController->designUpdate(1);
        $responseNotExist = $modelController->designUpdate(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());

        $this->startRequest('PUT', json_decode('{"type":"field","data":{"fields":[{"name":"nickname","title":"Nick Name","type":"input","settings":{"validate":["require","length"],"options":{"length":{"min":"0","max":"32"}}},"allowHome":"1","allowRead":"1","allowSave":"1","allowUpdate":"1","allowTranslate":"1"},{"name":"gender","title":"Gender","type":"radio","data":[{"title":"Mx","value":"mx"},{"title":"Mr","value":"mr"},{"title":"Ms","value":"ms"}],"settings":{"validate":["require"]},"allowHome":"1","allowRead":"1","allowSave":"1","allowUpdate":"","allowTranslate":""},{"name":"married","title":"Married","type":"switch","hideInColumn":"1","data":[{"title":"Yes","value":"1"},{"title":"No","value":"0"}],"settings":{"display":["listSorter"],"validate":["require"]},"allowHome":"1","allowRead":"1","allowSave":"1","allowUpdate":"1"},{"name":"number","title":"Number","type":"number","settings":{"validate":["number"],"display":["listSorter"]}}],"listAction":[{"name":"edit","title":"Edit","type":"primary","call":"modal","uri":"/api/unit_test/:id","method":"get"},{"name":"page-edit","title":"Page Edit","type":"default","call":"page","uri":"/api/unit_test/:id","method":"get"},{"name":"delete","title":"Delete","type":"default","call":"delete","uri":"/api/unit_test/delete","method":"post"}],"addAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit_test","method":"post"}],"editAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit_test/:id","method":"put"}],"tableToolbar":[{"name":"add","title":"Add","type":"primary","call":"modal","uri":"/api/unit_test/add","method":"get"},{"name":"page-add","title":"Page Add","type":"default","call":"page","uri":"/api/unit_test/add","method":"get"},{"name":"reload","title":"Reload","type":"default","call":"reload","method":"get"}],"batchToolbar":[{"name":"delete","title":"Delete","type":"danger","call":"delete","uri":"/api/unit_test/delete","method":"post"},{"name":"disabled","title":"Disabled","type":"default","call":"disabled","uri":"/api/unit_test/disable","method":"post"}],"batchToolbarTrashed":[{"name":"delete-permanently","title":"Delete Permanently","type":"danger","call":"deletePermanently","method":"post","uri":"/api/unit_test/delete"},{"name":"restore","title":"Restore","type":"default","call":"restore","uri":"/api/unit_test/restore","method":"post"}],"modelName":"unit_test"}}', true));
        $modelController = new ModelController($this->app);
        $response = $modelController->designUpdate(1);
        $this->assertStringStartsWith('{"success":true', $response->getContent());
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

    /* New Model */
    public function testNewModelHome()
    {
        $this->startRequest();
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // trash
        $this->startRequest('GET', ['trash' => 'onlyTrashed']);
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // search
        $this->startRequest('GET', ['nickname' => 'test', 'id' => '1']);
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testNewModelAdd()
    {
        $this->startRequest();
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testNewModelSave()
    {
        // valid
        $this->startRequest('POST', ['nickname' => 'unit_test']);
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testNewModelRead()
    {
        $this->startRequest();
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->read(1);
        $responseNotExist = $newController->read(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testNewModelDelete()
    {
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [1]]);
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testNewModelDeletePermanently()
    {
        $this->startRequest('POST', ['type' => 'deletePermanently', 'ids' => [0]]);
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":false', $response->getContent());
    }

    public function testNewModelRestore()
    {
        $this->startRequest('POST', ['ids' => [1]]);
        $newController = new \app\api\controller\UnitTest($this->app);
        $response = $newController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
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
