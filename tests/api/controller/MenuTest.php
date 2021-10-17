<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\Menu as MenuController;

class MenuTest extends \tests\api\TestCase
{

    protected function tearDown(): void
    {
        $this->endRequest();
    }

    public function testMenuHome()
    {
        $this->startRequest();
        $menuController = new MenuController($this->app);
        $response = $menuController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // trash
        $this->startRequest('GET', ['trash' => 'onlyTrashed']);
        $menuController = new MenuController($this->app);
        $response = $menuController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // search
        $this->startRequest('GET', ['menu_title' => 'Admin List']);
        $menuController = new MenuController($this->app);
        $response = $menuController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testMenuAdd()
    {
        $this->startRequest();
        $menuController = new MenuController($this->app);
        $response = $menuController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testMenuSave()
    {
        $validData = ['parent_id' => 0, 'path' => 'UnitTest', 'menu_title' => 'UnitTest', 'create_time' => (new \DateTime('NOW'))->format('Y-m-d\TH:i:sP'), 'status' => true];
        $this->startRequest('POST', $validData);
        $menuController = new MenuController($this->app);
        $response = $menuController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testMenuRead()
    {
        $this->startRequest();
        // valid
        $menuController = new MenuController($this->app);
        $response = $menuController->read(17);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringContainsString('"title":"UnitTest"', $response->getContent());
        // not exist
        $responseNotExist = $menuController->read(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testMenuUpdate()
    {
        $this->startRequest('PUT', ['menu_title' => 'UnitTest2']);
        // valid
        $menuController = new MenuController($this->app);
        $response = $menuController->update(17);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // not exist
        $responseNotExist = $menuController->update(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testMenuDelete()
    {
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [17]]);
        $menuController = new MenuController($this->app);
        $response = $menuController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testMenuRestore()
    {
        $this->startRequest('POST', ['ids' => [17]]);
        $menuController = new MenuController($this->app);
        $response = $menuController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testMenuBackend()
    {
        $this->startRequest();
        $menuController = new MenuController($this->app);
        $response = $menuController->backend();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('[{"id":', $response->getContent());
    }
}
