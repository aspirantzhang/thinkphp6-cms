<?php

declare(strict_types=1);

namespace app\core\validator;

use app\core\BaseModel;
use think\File;
use think\Validate;

// TODO: 3 methods may need overridden: parseErrorMsg, filter, length
class ValidateBuilder extends Validate
{
    private string $sceneName;
    private array $allowedFields;
    private array $requiredFields;
    private array $modelFields;

    public function __construct(private BaseModel $model)
    {
        parent::__construct();

        $this->getSceneName();
        $this->getModelFields();
        $this->getAllowedFields();
        $this->getRequiredFields();

        $this->buildScene();
        $this->initBuiltInRulesAndMessages();
        $this->buildRequiredRules();
        $this->buildRuleAndMessage();

        // halt($this->sceneName, $this->rule, $this->scene, $this->message);
    }

    private function getSceneName()
    {
        $this->sceneName = parse_name($this->request->action());
    }

    private function getAllowedFields()
    {
        $this->allowedFields = $this->model->getAllow($this->sceneName);
    }

    private function getRequiredFields()
    {
        $this->requiredFields = $this->model->getRequire($this->sceneName);
    }

    private function getModelFields()
    {
        $this->modelFields = $this->model->getModule('field');
    }

    private function clearRequiredFields(array $rules)
    {
        $result = [];
        foreach ($rules as $ruleName => $ruleString) {
            if (str_contains($ruleString, 'require')) {
                $withoutRequire = strtr($ruleString, ['require|' => '', 'require' => '']);
                $result[$ruleName] = $withoutRequire;
            } else {
                $result[$ruleName] = $ruleString;
            }
        }

        return $result;
    }

    private function getBuiltInRule()
    {
        $builtInRuleFile = createPath(dirname(__DIR__), 'config', 'rule') . '.php';
        if (file_exists($builtInRuleFile)) {
            $builtInRules = require $builtInRuleFile;
        }

        if ($this->sceneName === 'index') {
            return $this->clearRequiredFields($builtInRules ?? []);
        }

        return $builtInRules ?? [];
    }

    private function getBuiltInMessage()
    {
        $builtInRuleFile = createPath(dirname(__DIR__), 'config', 'message') . '.php';
        if (file_exists($builtInRuleFile)) {
            $builtInRules = require $builtInRuleFile;
        }

        return $builtInRules ?? [];
    }

    private function initBuiltInRulesAndMessages()
    {
        $this->rule = $this->getBuiltInRule();
        $this->message = $this->getBuiltInMessage();
    }

    private function buildRequiredRules()
    {
        foreach ($this->modelFields as $field) {
            if (!in_array($field['name'], $this->requiredFields)) {
                continue;
            }

            $this->buildRequiredRule($field);
            $this->buildRequiredMessage($field);
        }
    }

    private function buildRuleAndMessage()
    {
        foreach ($this->modelFields as $field) {
            if (!in_array($field['name'], $this->allowedFields)) {
                continue;
            }

            $this->buildRule($field);
            $this->buildMessage($field);
        }
    }

    private function buildRequiredRule(array $field)
    {
        if ($this->rule[$field['name']] ?? '') {
            $this->rule[$field['name']] = 'require|' . $this->getRuleString($field);
        } else {
            $this->rule[$field['name']] = 'require';
        }
    }

    private function buildRule(array $field)
    {
        if ($this->rule[$field['name']] ?? '') {
            $this->rule[$field['name']] = $this->rule[$field['name']] . '|' . $this->getRuleString($field);
        } else {
            $this->rule[$field['name']] = $this->getRuleString($field);
        }
    }

    private function buildRequiredMessage(array $field)
    {
        $messageKey = $field['name'] . '.require';
        $this->message[$messageKey] = 'require';
    }

    private function buildMessage(array $field)
    {
        if (isset($field['rule'])) {
            foreach ($field['rule'] as $ruleName => $ruleAttr) {
                $messageKey = $field['name'] . '.' . $ruleName;
                $this->message[$messageKey] = $ruleName;
            }
        }
    }

    private function buildScene()
    {
        $this->scene = [
            $this->sceneName => $this->allowedFields,
        ];
    }

    private function getRuleString($field)
    {
        $ruleArray = [];
        if (isset($field['rule'])) {
            foreach ($field['rule'] as $ruleName => $ruleAttr) {
                if ($this->sceneName === 'index') {
                    continue;
                }

                $ruleString = $ruleName;
                if (is_array($ruleAttr)) {
                    $ruleString .= ':' . implode('-', array_values($ruleAttr));
                }
                $ruleArray[] = $ruleString;
            }
        }

        return implode('|', $ruleArray);
    }

    // overwrite parent method, change delimiter to "-"
    public function length($value, $rule): bool
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string) $value);
        }

        if (is_string($rule) && strpos($rule, '-')) {
            // 长度区间
            [$min, $max] = explode('-', $rule);

            return $length >= $min && $length <= $max;
        }

        // 指定长度
        return $length == $rule;
    }
}
