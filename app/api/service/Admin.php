<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Admin as AdminLogic;
use think\facade\Config;

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

    public function i18nUpdateAPI($id, $data)
    {
        foreach ($data as $langCode => $fieldsData) {
            // validator check
            $modelValidator = '\app\api\validate\\' . $this->getName();
            $validate = new $modelValidator();
            $result = $validate->only($this->getAllowTranslate())->check($fieldsData);
            if (!$result) {
                return $this->error($validate->getError());
            }
            // handle update
            if ($this->updateI18nData($fieldsData, $id, $langCode) === false) {
                return $this->error($this->getError());
            }
        }
        return $this->success(__('update successfully'));
    }
}
