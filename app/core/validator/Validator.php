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

    public function handle($request, \Closure $next)
    {
        $appName = parse_name(app('http')->getName());
        $moduleName = $request->controller();
        $moduleClass = 'app\\' . $appName . '\\facade\\' . $moduleName;
        if (!class_exists($moduleClass)) {
            throw new SystemException('invalid module class: ' . $moduleClass);
        }
        $module = new $moduleClass();

        Event::trigger('ExtendValidateRules', $this);

        $this->initCoreRules();

        $validateClass = new class() extends Validate {
            public function __construct()
            {
                parent::__construct();

                $this->rule['admin_name'] = 'Integer';

                $this->scene['index'] = ['admin_name'];

                $this->message['admin_name.length'] = 'octopus-length';
                $this->message['admin_name.Integer'] = 'octopus-Integer';
            }
        };

        (new $validateClass($module))->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
