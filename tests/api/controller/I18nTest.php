<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\Admin as AdminController;

require_once('./app/api/common.php');

class I18nTest extends \tests\api\TestCase
{
    protected function tearDown(): void
    {
        $this->endRequest();
        $this->endMockLang();
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    public function testSaveI18n()
    {
        $this->mockLang('zh-cn');
        $validData = ['admin_name' => 'UnitTest2', 'password' => 'UnitTest2', 'display_name' => '单元测试'];
        $this->startRequest('POST', $validData);
        $adminController = new AdminController($this->app);
        $response = $adminController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    public function testReadI18n()
    {
        $this->mockLang('zh-cn');
        $this->startRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->read(3);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringContainsString('"display_name":"单元测试"', $response->getContent());
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    public function testReadI18nNotExist()
    {
        $this->mockLang('en-us');
        $this->startRequest();
        $adminController = new AdminController($this->app);
        $response = $adminController->read(3);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":false', $response->getContent());
    }
}
