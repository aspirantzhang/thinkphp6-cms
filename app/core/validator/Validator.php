<?php

declare(strict_types=1);

namespace app\core\validator;

use app\core\exception\SystemException;
use app\core\validator\rule\DateTimeRange;
use app\core\validator\rule\NumberArray;
use app\core\validator\rule\ParentId;
use think\Validate;

class Validator
{
    public function handle($request, \Closure $next)
    {
        $appName = parse_name(app('http')->getName());
        $moduleName = $request->controller();
        $moduleClass = 'app\\' . $appName . '\\facade\\' . $moduleName;
        if (!class_exists($moduleClass)) {
            throw new SystemException('invalid module class: ' . $moduleClass);
        }
        $module = new $moduleClass();

        $extendRules = new ExtendedRules();
        $extendRules->attach(new NumberArray());
        $extendRules->attach(new DateTimeRange());
        $extendRules->attach(new ParentId());
        $extendRules->boot();

        $validateClass = new class() extends Validate {
            public function __construct()
            {
                parent::__construct();
                // $this->setLang(app()->lang);

                $this->rule['admin_name'] = 'numberArray';

                $this->scene['index'] = ['admin_name'];

                $this->message['admin_name.length'] = 'octopus-length';
                $this->message['admin_name.numberArray'] = 'octopus-numberArray';
            }
        };

        (new $validateClass($module))->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
