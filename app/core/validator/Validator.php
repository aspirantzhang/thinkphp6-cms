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
    private $model;

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
            if (isset($parents[BaseRule::class])) {
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

    private function initModel($request)
    {
        $appName = parse_name(app('http')->getName());
        $modelName = $request->controller();
        $modelClass = 'app\\' . $appName . '\\model\\' . $modelName;
        if (!class_exists($modelClass)) {
            throw new SystemException('invalid model class: ' . $modelClass);
        }
        $this->model = new $modelClass();
    }

    public function handle($request, \Closure $next)
    {
        $this->initModel($request);

        Event::trigger('ValidateRules', $this);

        $this->initRules();

        (new ValidateBuilder($this->model))->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
