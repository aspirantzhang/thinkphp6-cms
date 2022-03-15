<?php

 declare(strict_types=1);

namespace app\index;

class TwigRuntimeExtension
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function model($tableName, $apiName = 'list', $params = [])
    {
        $modelName = parse_name($tableName, 1);
        $modelPath = 'app\\api\\service\\' . $modelName;
        if (class_exists($modelPath)) {
            $model = new $modelPath();
            if (method_exists($model, 'list' . 'API')) {
                $method = $apiName . 'API';
                dump($model->$method(...$params));
            }
        }

        return '[runtime] model name is: ' . $tableName;
    }
}
