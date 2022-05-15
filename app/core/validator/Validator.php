<?php

declare(strict_types=1);

namespace app\core\validator;

use app\core\exception\SystemException;
use app\core\validator\rule\DateTimeRange;
use app\core\validator\rule\Integer;
use app\core\validator\rule\ParentId;
use think\facade\Event;
use think\Validate;

class Validator
{
    private $module;
    private array $field;
    private array $rule;
    private array $scene;
    private array $message;

    public static array $coreRules = [
        Integer::class,
        DateTimeRange::class,
        ParentId::class,
    ];

    private function initCoreRules()
    {
        Validate::maker(function ($validate) {
            foreach (self::$coreRules as $ruleClass) {
                $this->initRule($ruleClass, $validate);
            }
        });
    }

    private function isValidRuleClass(string $ruleClass)
    {
        if (class_exists($ruleClass)) {
            $parents = class_parents($ruleClass);
            if (isset($parents[CoreRule::class])) {
                return true;
            }
        }

        return false;
    }

    private function initRule(string $ruleClass, Validate $validate)
    {
        if ($this->isValidRuleClass($ruleClass)) {
            (new $ruleClass($validate))->check();
        }
    }

    private function initModule($request)
    {
        $appName = parse_name(app('http')->getName());
        $moduleName = $request->controller();
        $moduleClass = 'app\\' . $appName . '\\facade\\' . $moduleName;
        if (!class_exists($moduleClass)) {
            throw new SystemException('invalid module class: ' . $moduleClass);
        }
        $this->module = new $moduleClass();
    }

    public function handle($request, \Closure $next)
    {
        $this->initModule($request);

        $this->rule['admin_name'] = 'required';
        $this->scene['index'] = ['admin_name'];
        $this->message['admin_name.required'] = 'octopus-required';

        Event::trigger('ExtendValidateRules', $this);

        $this->initCoreRules();

        $validateClass = new class($this->rule, $this->scene, $this->message) extends Validate {
            public function __construct($rule, $scene, $message)
            {
                parent::__construct();
                $this->rule = $rule;
                $this->scene = $scene;
                $this->message = $message;
            }
        };

        $validateClass->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
