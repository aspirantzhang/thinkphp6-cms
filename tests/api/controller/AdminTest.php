<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\Admin as AdminController;

require_once('./app/api/common.php');

class AdminTest extends \PHPUnit\Framework\TestCase
{
    public function testAdminHome()
    {
        $request = new \app\Request();
        $request->setMethod('GET');
        $request->setUrl('/api/admins');
        $request->withHeader(['HTTP_ACCEPT' => 'application/json']);
        $app = (new \think\App());
        $response = $app->http->run($request);

        $adminController = new AdminController($app);
        $response = $adminController->home();

        $this->assertEquals(200, $response->getCode());
        $app->http->end($response);
    }
}
