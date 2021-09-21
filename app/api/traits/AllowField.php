<?php

declare(strict_types=1);

namespace app\api\traits;

use think\facade\Config;
use aspirantzhang\TPAntdBuilder\Builder;

trait AllowField
{
    protected function getUniqueField(): array
    {
        return $this->uniqueField ?? [];
    }

    protected function getAddonData($params = [])
    {
        $custom = [];
        if (method_exists($this, 'setAddonData')) {
            $custom = $this->setAddonData($params);
        }
        $builtIn = [
            'status' => Builder::element()->singleChoice(),
        ];

        return array_merge($builtIn, $custom);
    }

    private function loadAllowFieldsConfig()
    {
        $modelName = $this->getModelName();
        Config::load(createPath('api', 'allowFields', $modelName), $modelName);
    }

    public function getAllowHome()
    {
        $this->loadAllowFieldsConfig();
        $builtIn = Config::get('field.allowHome') ?: [];
        $custom = Config::get($this->getModelName() . '.allowHome') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        $this->loadAllowFieldsConfig();
        $builtIn = Config::get('field.allowSort') ?: [];
        $custom = Config::get($this->getModelName() . '.allowSort') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        $this->loadAllowFieldsConfig();
        $builtIn = Config::get('field.allowRead') ?: [];
        $custom = Config::get($this->getModelName() . '.allowRead') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        $this->loadAllowFieldsConfig();
        $builtIn = Config::get('field.allowSave') ?: [];
        $custom = Config::get($this->getModelName() . '.allowSave') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        $this->loadAllowFieldsConfig();
        $builtIn = Config::get('field.allowUpdate') ?: [];
        $custom = Config::get($this->getModelName() . '.allowUpdate') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowTranslate()
    {
        $this->loadAllowFieldsConfig();
        return Config::get($this->getModelName() . '.allowTranslate') ?: [];
    }
}
