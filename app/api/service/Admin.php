<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Admin as AdminLogic;
use think\facade\Config;
use think\facade\Db;

class Admin extends AdminLogic
{
    public function loginAPI($params = [])
    {
        $admin = $this->where('admin_name', $params['username'])->find();
        if ($admin) {
            if (password_verify($params['password'], $admin->password)) {
                $data = $admin->visible(['id', 'admin_name'])->toArray();
                $addition = [
                    'currentAuthority' => 'admin',
                    'type' => $params['type'] ?? null
                ];
                return $this->success('', $data, [], $addition);
            }
        }

        return $this->error(__('incorrect username or password'));
    }

    public function i18nAPI($id)
    {
        $originalRecord = $this->where('id', $id)->find();
        if ($originalRecord) {
            $layout = $this->i18nBuilder($id);

            $languages = Config::get('lang.allow_lang_list');
    
            $i18nRecords = DB::name($this->getLangTableName())
                ->where('original_id', $id)
                ->whereIn('lang_code', implode(',', $languages))
                ->select()->toArray();
            
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

    public function i18nUpdateAPI($id, $data)
    {
        $currentTime = date("Y-m-d H:i:s");

        foreach ($data as $langCode => $fieldsData) {
            // validator check
            $modelValidator = '\app\api\validate\\' . $this->getName();
            $validate = new $modelValidator();
            $result = $validate->only($this->getAllowTranslate())->check($fieldsData);
            if (!$result) {
                return $this->error($validate->getError());
            }
            // handle update
            if ($this->updateI18nData($fieldsData, $id, $langCode, $currentTime) === false) {
                return $this->error($this->getError());
            }
        }
        return $this->success(__('update successfully'));
    }
}
