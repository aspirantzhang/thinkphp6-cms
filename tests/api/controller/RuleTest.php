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
        // trash
        $this->startRequest('GET', ['trash' => 'onlyTrashed']);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->home();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // search
        $this->startRequest('GET', ['rule_title' => 'Admin Home']);
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
        $validData = ['parent_id' => 0, 'rule_path' => 'UnitTest', 'rule_title' => 'UnitTest'];
        $this->startRequest('POST', $validData);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->save();
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleRead()
    {
        $this->startRequest();
        $ruleController = new RuleController($this->app);
        $response = $ruleController->read(51);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        $this->assertStringContainsString('"rule_title":"UnitTest"', $response->getContent());
        // not exist
        $responseNotExist = $ruleController->read(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testRuleUpdate()
    {
        $this->startRequest('PUT', ['rule_title' => 'UnitTest2']);
        // valid
        $ruleController = new RuleController($this->app);
        $response = $ruleController->update(51);
        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
        // not exist
        $responseNotExist = $ruleController->update(0);
        $this->assertEquals(200, $responseNotExist->getCode());
        $this->assertStringStartsWith('{"success":false', $responseNotExist->getContent());
    }

    public function testRuleDelete()
    {
        // include have children -> id:33
        $this->startRequest('POST', ['type' => 'delete', 'ids' => [43, 33]]);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->delete();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }

    public function testRuleRestore()
    {
        // include have children -> id:33
        $this->startRequest('POST', ['ids' => [43, 33]]);
        $ruleController = new RuleController($this->app);
        $response = $ruleController->restore();

        $this->assertEquals(200, $response->getCode());
        $this->assertStringStartsWith('{"success":true', $response->getContent());
    }
}
