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
        $custom = [];
        if (isset($this->allowHome)) {
            $custom = $this->allowHome ?: [];
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('home');
            }
        }
        
        return array_merge($builtIn, $custom);
    }

    public function getAllowList()
    {
        $builtIn = Config::get('field.allowList') ?: [];
        $custom = [];
        if (isset($this->allowList)) {
            $custom = $this->allowList ?: [];
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('list');
            }
        }

        return array_merge($builtIn, $custom);
    }

    public function getAllowSort()
    {
        $builtIn = Config::get('field.allowSort') ?: [];
        $custom = [];
        if (isset($this->allowSort)) {
            $custom = $this->allowSort ?: [];
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('sort');
            }
        }

        return array_merge($builtIn, $custom);
    }

    public function getAllowRead()
    {
        $builtIn = Config::get('field.allowRead') ?: [];
        $custom = [];
        if (isset($this->allowRead)) {
            $custom = $this->allowRead ?: [];
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('read');
            }
        }

        return array_merge($builtIn, $custom);
    }

    public function getAllowSave()
    {
        $builtIn = Config::get('field.allowSave') ?: [];
        $custom = [];
        if (!is_null($this->allowSave)) {
            $custom = (array)$this->allowSave;
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('save');
            }
        }

        return array_merge($builtIn, $custom);
    }

    public function getAllowUpdate()
    {
        $builtIn = Config::get('field.allowUpdate') ?: [];
        $custom = [];
        if (isset($this->allowUpdate)) {
            $custom = $this->allowUpdate ?: [];
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('update');
            }
        }

        return array_merge($builtIn, $custom);
    }

    public function getAllowSearch()
    {
        $builtIn = Config::get('field.allowSearch') ?: [];
        $custom = [];
        if (isset($this->allowSearch)) {
            $custom = $this->allowSearch ?: [];
        } else {
            if (!$this->isReservedTable()) {
                $custom = $this->getModelFields('search');
            }
        }

        return array_merge($builtIn, $custom);
    }

    public function getAllowTranslate()
    {
        return $this->allowTranslate ?? [];
    }

    protected function isReservedTable()
    {
        return in_array($this->getTableName(), Config::get('model.reserved_table'));
    }
}
