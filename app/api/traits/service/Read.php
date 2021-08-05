<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\facade\Config;
use think\facade\Db;

trait Read
{
    public function readAPI(int $id, array $relationModel = [])
    {
        $relationArray = [];

        if (!empty($relationModel)) {
            foreach ($relationModel as $relation) {
                $relationArray[$relation] = function ($query) {
                    $query->scope('status')->visible(['id']);
                };
            }
        }

        $model = $this->withI18n($this)
            ->where('o.id', $id)
            ->with($relationArray)
            ->visible($this->getAllowRead())
            ->find();

        if ($model) {
            $model = $model->toArray();

            if ($relationModel) {
                foreach ($relationModel as $relation) {
                    $model[$relation] = extractValues($model[$relation], 'id');
                }
            }

            $layout = $this->editBuilder($id, $this->getAddonData(['id' => $id]));
            $layout['dataSource'] = $model;

            return $this->success('', $layout);
        } else {
            return $this->error(__('no target'));
        }
    }

    public function i18nAPI(int $id)
    {
        $originalRecord = $this->where('id', $id)->find();
        if ($originalRecord) {
            $layout = $this->i18nBuilder();

            $languages = Config::get('lang.allow_lang_list');

            $i18nRecords = DB::name($this->getLangTableName())
                ->where('original_id', $id)
                ->whereIn('lang_code', implode(',', $languages))
                ->select()
                ->toArray();

            $dataSource = [];
            foreach ($i18nRecords as $record) {
                $langCode = $record['lang_code'];
                unset($record['_id']);
                unset($record['original_id']);
                unset($record['lang_code']);
                $dataSource[$langCode] = $record;
            }

            $layout['dataSource'] = $dataSource;

            return $this->success('', $layout);
        } else {
            return $this->error(__('no target'));
        }
    }
}
