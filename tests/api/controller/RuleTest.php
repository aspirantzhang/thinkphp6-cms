<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\AuthRule as RuleController;

require_once('./app/api/common.php');

class RuleTest extends \PHPUnit\Framework\TestCase
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

    public function testRuleHome()
    {
        $this->setUpRequest();
        
        $ruleController = new RuleController($this->app);

        $response = $ruleController->home();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleAdd()
    {
        $this->setUpRequest();
        $ruleController = new RuleController($this->app);
        $response = $ruleController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleSave()
    {
        $this->setUpRequest();
        $ruleController = new RuleController($this->app);
        $response = $ruleController->save();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleRead()
    {
        $this->setUpRequest();
        $ruleController = new RuleController($this->app);
        $response = $ruleController->read(293);
        $responseNotExist = $ruleController->read(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testRuleUpdate()
    {
        $this->setUpRequest('PUT', ['rule_title' => 'Admin Login']);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->update(293);
        $responseNotExist = $ruleController->update(0);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testRuleDelete()
    {
        $this->setUpRequest('POST', ['type' => 'delete', 'ids' => [293]]);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleRestore()
    {
        $this->setUpRequest('POST', ['ids' => [293]]);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
