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

    public static array $rules = [
        Integer::class,
        DateTimeRange::class,
        ParentId::class,
    ];

    private function initRules()
    {
        Validate::maker(function ($validate) {
            foreach (self::$rules as $ruleClass) {
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

        Event::trigger('ValidateRules', $this);

        $this->initRules();

        (new ValidateBuilder($this->module))->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
