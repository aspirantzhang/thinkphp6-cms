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

    public function getTitleField(): string
    {
        $this->loadModelConfig();
        return Config::get($this->getModelName() . '.titleField') ?: '';
    }

    public function getUniqueField()
    {
        $this->loadModelConfig();
        return Config::get($this->getModelName() . '.uniqueValue') ?: [];
    }

    public function getIgnoreFilter()
    {
        $this->loadModelConfig();
        return Config::get($this->getModelName() . '.ignoreFilter') ?: [];
    }

    public function getAllowHome()
    {
        $this->loadModelConfig();
        $builtIn = Config::get('field.allowHome') ?: [];
        $custom = Config::get($this->getModelName() . '.allowHome') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        $this->loadModelConfig();
        $builtIn = Config::get('field.allowSort') ?: [];
        $custom = Config::get($this->getModelName() . '.allowSort') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        $this->loadModelConfig();
        $builtIn = Config::get('field.allowRead') ?: [];
        $custom = Config::get($this->getModelName() . '.allowRead') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        $this->loadModelConfig();
        $builtIn = Config::get('field.allowSave') ?: [];
        $custom = Config::get($this->getModelName() . '.allowSave') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        $this->loadModelConfig();
        $builtIn = Config::get('field.allowUpdate') ?: [];
        $custom = Config::get($this->getModelName() . '.allowUpdate') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowTranslate()
    {
        $this->loadModelConfig();
        $builtIn = Config::get('field.allowTranslate') ?: [];
        $custom = Config::get($this->getModelName() . '.allowTranslate') ?: [];

        return array_merge($builtIn, $custom);
    }
}
