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
        $this->startRequest('POST', ['table_name' => 'unit_test', 'route_name' => 'unit-tests', 'title' => 'Unit Test', 'create_time' => '2021-04-16T13:25:32+08:00']);
        $modelController = new ModelController($this->app);
        $response = $modelController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->endRequest();

        // invalid
        $this->startRequest('POST', ['table_name' => 'admin', 'route_name' => 'admin', 'title' => 'Admin']);
        $modelController = new ModelController($this->app);
        $response = $modelController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":false', $response->getContent());

        // exist tablename
        $this->startRequest('POST', ['table_name' => 'unit_test', 'route_name' => 'unit-tests', 'title' => 'Unit Test']);
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
        $this->startRequest('PUT', json_decode('{"data":{"routeName":"unit-tests","fields":[{"name":"nickname","title":"Nick Name","type":"text","editDisabled":true},{"name":"gender","title":"Gender","type":"radio","data":[{"title":"Mx","value":"mx"},{"title":"Mr","value":"mr"},{"title":"Ms","value":"ms"}]},{"name":"married","title":"Married","type":"switch","hideInColumn":true,"data":[{"title":"Enabled","value":1},{"title":"Disabled","value":0}]}],"listAction":[{"name":"edit","title":"Edit","type":"primary","call":"modal","uri":"/api/unit-tests/:id","method":"get"},{"name":"page-edit","title":"Page Edit","type":"default","call":"page","uri":"/api/unit-tests/:id","method":"get"},{"name":"delete","method":"post","title":"Delete","type":"default","call":"delete","uri":"/api/unit-tests/delete"}],"addAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit-tests","method":"post"}],"editAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit-tests/:id","method":"put"}],"tableToolbar":[{"name":"add","title":"Add","type":"primary","call":"modal","uri":"/api/unit-tests/add","method":"get"},{"name":"page-add","title":"Page Add","type":"default","call":"page","uri":"/api/unit-tests/add","method":"get"},{"name":"reload","title":"Reload","type":"default","call":"reload","method":"get"}],"batchToolbar":[{"name":"delete","title":"Delete","type":"danger","call":"delete","uri":"/api/unit-tests/delete","method":"post"},{"name":"disabled","title":"Disabled","type":"default","call":"disabled","uri":"/api/unit-tests/disable","method":"post"}],"batchToolbarTrashed":[{"name":"delete-permanently","title":"Delete Permanently","type":"danger","call":"deletePermanently","method":"post","uri":"/api/unit-tests/delete"},{"name":"restore","title":"Restore","type":"default","call":"restore","uri":"/api/unit-tests/restore","method":"post"}]}}', true));
        $modelController = new ModelController($this->app);
        $response = $modelController->designUpdate(1);
        $responseNotExist = $modelController->designUpdate(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());

        $this->startRequest('PUT', json_decode('{"data":{"routeName":"unit-tests","fields":[{"name":"nickname","title":"Nick Name","type":"text","editDisabled":true},{"name":"number","title":"Number","type":"number","listHideInColumn":1},{"name":"datetime","title":"DateTime","type":"datetime"},{"name":"longtext","title":"LongText","type":"longtext"},{"name":"gender","title":"Gender","type":"radio","data":[{"title":"Mx","value":"mx"},{"title":"Mr","value":"mr"},{"title":"Ms","value":"ms"}]},{"name":"married","title":"Married","type":"switch","hideInColumn":true,"data":[{"title":"Enabled","value":1},{"title":"Disabled","value":0}]}],"listAction":[{"name":"edit","title":"Edit","type":"primary","call":"modal","uri":"/api/unit-tests/:id","method":"get"},{"name":"page-edit","title":"Page Edit","type":"default","call":"page","uri":"/api/unit-tests/:id","method":"get"},{"name":"delete","method":"post","title":"Delete","type":"default","call":"delete","uri":"/api/unit-tests/delete"}],"addAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit-tests","method":"post"}],"editAction":[{"name":"reset","title":"Reset","type":"dashed","call":"reset","method":"get"},{"name":"cancel","title":"Cancel","type":"default","call":"cancel","method":"get"},{"name":"submit","title":"Submit","type":"primary","call":"submit","uri":"/api/unit-tests/:id","method":"put"}],"tableToolbar":[{"name":"add","title":"Add","type":"primary","call":"modal","uri":"/api/unit-tests/add","method":"get"},{"name":"page-add","title":"Page Add","type":"default","call":"page","uri":"/api/unit-tests/add","method":"get"},{"name":"reload","title":"Reload","type":"default","call":"reload","method":"get"}],"batchToolbar":[{"name":"delete","title":"Delete","type":"danger","call":"delete","uri":"/api/unit-tests/delete","method":"post"},{"name":"disabled","title":"Disabled","type":"default","call":"disabled","uri":"/api/unit-tests/disable","method":"post"}],"batchToolbarTrashed":[{"name":"delete-permanently","title":"Delete Permanently","type":"danger","call":"deletePermanently","method":"post","uri":"/api/unit-tests/delete"},{"name":"restore","title":"Restore","type":"default","call":"restore","uri":"/api/unit-tests/restore","method":"post"}]}}', true));
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
