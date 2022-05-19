<?php

declare(strict_types=1);

namespace app\core\validator;

use app\core\exception\SystemException;
use think\File;
use think\Validate;

// TODO: 3 methods may need overridden: parseErrorMsg, filter, length
class ValidateBuilder extends Validate
{
    private string $sceneName;
    private array $allowedFields;
    private array $moduleFields;

    public function __construct(private $module)
    {
        parent::__construct();

        $this->getSceneName();
        $this->getAllowedFields();
        $this->getModuleFields();

        $this->buildScene();
        $this->buildRuleAndMessage();

        // halt($this->rule, $this->scene, $this->message);
    }

    private function getSceneName()
    {
        $action = parse_name($this->request->action());
        $this->sceneName = $this->parseScene($action);
    }

    private function getAllowedFields()
    {
        $this->allowedFields = $this->module->model->getAllow($this->sceneName);
    }

    private function getModuleFields()
    {
        $this->moduleFields = $this->module->model->getModule('field');
    }

    private function buildRuleAndMessage()
    {
        foreach ($this->moduleFields as $field) {
            if (!in_array($field['name'], $this->allowedFields)) {
                continue;
            }

            $this->buildRule($field);
            $this->buildMessage($field);
        }
    }

    private function buildRule(array $field)
    {
        $this->rule[$field['name']] = $this->getRuleString($field);
    }

    private function buildMessage(array $field)
    {
        if (isset($field['validate'])) {
            foreach ($field['validate'] as $ruleName => $ruleAttr) {
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
        if (isset($field['validate'])) {
            foreach ($field['validate'] as $ruleName => $ruleAttr) {
                if ($this->sceneName === 'index' && $ruleName === 'require') {
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

    private function parseScene(string $action): string
    {
        $result = null;

        $defaultConfigFilePath = dirname(__DIR__) . '/config/validator.php';
        if (file_exists($defaultConfigFilePath)) {
            $defaultConfig = require_once dirname(__DIR__) . '/config/validator.php';

            $result = $this->findSceneInConfig($action, $defaultConfig['scenes']);
        }

        if (!empty($result)) {
            return $result;
        }

        throw new SystemException('cannot find scene for action: ' . $action);
    }

    private function findSceneInConfig(string $needle, array $haystack): string
    {
        foreach ($haystack as $key => $value) {
            if (in_array($needle, $value)) {
                return $key;
            }
        }

        return '';
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
