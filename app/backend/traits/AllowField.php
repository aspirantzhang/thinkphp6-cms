<?php

declare(strict_types=1);

namespace app\backend\traits;

use think\facade\Config;

trait AllowField
{
    protected function getAddonData($params = [])
    {
        $custom = [];
        if (method_exists($this, 'setAddonData')) {
            $custom = $this->setAddonData($params);
        }
        $builtIn = [
            'status' => getSingleChoiceValue(),
        ];
        return array_merge($builtIn, $custom);
    }

    public function getAllowHome()
    {
        $builtIn = Config::get('field.allowHome');
        $custom = $this->allowHome ?? [];
        return array_merge($builtIn, $custom);
    }

    public function getAllowList()
    {
        $builtIn = Config::get('field.allowList');
        $custom = $this->allowList ?? [];
        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        $builtIn = Config::get('field.allowSort');
        $custom = $this->allowSort ?? [];
        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        $builtIn = Config::get('field.allowRead');
        $custom = $this->allowRead ?? [];
        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        $builtIn = Config::get('field.allowSave');
        $custom = $this->allowSave ?? [];
        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        $builtIn = Config::get('field.allowUpdate');
        $custom = $this->allowUpdate ?? [];
        return array_merge($builtIn, $custom);
    }
    
    public function getAllowSearch()
    {
        $builtIn = Config::get('field.allowSearch');
        $custom = $this->allowSearch ?? [];
        return array_merge($builtIn, $custom);
    }
}
