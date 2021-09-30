<?php

declare(strict_types=1);

namespace app\api\traits;

use think\facade\Config;
use aspirantzhang\octopusPageBuilder\Builder;

trait Filter
{
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

    private function loadFilterConfig()
    {
        $modelName = $this->getModelName();
        Config::load(createPath('api', 'filter', $modelName), $modelName);
    }

    protected function getUniqueField()
    {
        $this->loadFilterConfig();
        return Config::get($this->getModelName() . '.uniqueValue') ?: [];
    }

    protected function getIgnoreFilter()
    {
        $this->loadFilterConfig();
        return Config::get($this->getModelName() . '.ignoreFilter') ?: [];
    }

    public function getAllowHome()
    {
        $this->loadFilterConfig();
        $builtIn = Config::get('field.allowHome') ?: [];
        $custom = Config::get($this->getModelName() . '.allowHome') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        $this->loadFilterConfig();
        $builtIn = Config::get('field.allowSort') ?: [];
        $custom = Config::get($this->getModelName() . '.allowSort') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        $this->loadFilterConfig();
        $builtIn = Config::get('field.allowRead') ?: [];
        $custom = Config::get($this->getModelName() . '.allowRead') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        $this->loadFilterConfig();
        $builtIn = Config::get('field.allowSave') ?: [];
        $custom = Config::get($this->getModelName() . '.allowSave') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        $this->loadFilterConfig();
        $builtIn = Config::get('field.allowUpdate') ?: [];
        $custom = Config::get($this->getModelName() . '.allowUpdate') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowTranslate()
    {
        $this->loadFilterConfig();
        return Config::get($this->getModelName() . '.allowTranslate') ?: [];
    }
}
