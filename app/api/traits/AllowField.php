<?php

declare(strict_types=1);

namespace app\api\traits;

use think\facade\Config;
use aspirantzhang\TPAntdBuilder\Builder;

trait AllowField
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

    public function getAllowHome()
    {
        $builtIn = Config::get('field.allowHome') ?: [];
        $custom = Config::get($this->getName() . '.allowHome') ?: [];
        
        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        $builtIn = Config::get('field.allowSort') ?: [];
        $custom = Config::get($this->getName() . '.allowSort') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        $builtIn = Config::get('field.allowRead') ?: [];
        $custom = Config::get($this->getName() . '.allowRead') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        // dump($this->getName(), Config::get('field.allowSave'), Config::get($this->getName() . '.allowSave'));
        $builtIn = Config::get('field.allowSave') ?: [];
        $custom = Config::get($this->getName() . '.allowSave') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        $builtIn = Config::get('field.allowUpdate') ?: [];
        $custom = Config::get($this->getName() . '.allowUpdate') ?: [];

        return array_merge($builtIn, $custom);
    }

    public function getAllowTranslate()
    {
        return Config::get($this->getName() . '.allowTranslate') ?: [];
    }

    protected function isReservedTable()
    {
        return in_array($this->getTableName(), Config::get('model.reserved_table'));
    }
}
