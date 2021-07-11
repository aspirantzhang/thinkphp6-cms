<?php

declare(strict_types=1);

namespace app\api\traits;

use think\facade\Config;
use think\helper\Str;
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

    public function getAllowHome()
    {
        Config::load('api/allowFields/' . Str::studly($this->getName()), $this->getName());
        $builtIn = Config::get('field.allowHome') ?: [];
        $custom = Config::get($this->getName() . '.allowHome') ?: [];
        
        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        Config::load('api/allowFields/' . Str::studly($this->getName()), $this->getName());
        $builtIn = Config::get('field.allowSort') ?: [];
        $custom = Config::get($this->getName() . '.allowSort') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        Config::load('api/allowFields/' . Str::studly($this->getName()), $this->getName());
        $builtIn = Config::get('field.allowRead') ?: [];
        $custom = Config::get($this->getName() . '.allowRead') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        Config::load('api/allowFields/' . Str::studly($this->getName()), $this->getName());
        $builtIn = Config::get('field.allowSave') ?: [];
        $custom = Config::get($this->getName() . '.allowSave') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        Config::load('api/allowFields/' . Str::studly($this->getName()), $this->getName());
        $builtIn = Config::get('field.allowUpdate') ?: [];
        $custom = Config::get($this->getName() . '.allowUpdate') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowTranslate()
    {
        Config::load('api/allowFields/' . Str::studly($this->getName()), $this->getName());
        return Config::get($this->getName() . '.allowTranslate') ?: [];
    }
}
