<?php

declare(strict_types=1);

namespace tests\api\controller;

use app\api\controller\AuthRule as RuleController;

require_once('./app/api/common.php');

class RuleTest extends \tests\api\TestCase
{

    protected function tearDown(): void
    {
        $this->endRequest();
    }

    public function testRuleHome()
    {
        $this->startRequest();
        
        $ruleController = new RuleController($this->app);

        $response = $ruleController->home();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleAdd()
    {
        $this->startRequest();
        $ruleController = new RuleController($this->app);
        $response = $ruleController->add();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleSave()
    {
        $this->startRequest();
        $ruleController = new RuleController($this->app);
        $response = $ruleController->save();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleRead()
    {
        $this->startRequest();
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
        $this->startRequest('PUT', ['rule_title' => 'Admin Login']);
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
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [85]]);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleRestore()
    {
        $this->startRequest('POST', ['ids' => [85,86,87,88,89,90,91,92]]);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
