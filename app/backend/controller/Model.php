<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Model as ModelService;
use think\facade\Db;
use think\facade\Console;
use app\backend\service\AuthRule as RuleService;
use app\backend\service\Menu as MenuService;

class Model extends Common
{
    public function initialize()
    {
        $this->model = new ModelService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->model->paginatedListAPI($this->request->only($this->model->allowHome));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->model->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->model->saveAPI($this->request->only($this->model->allowSave));
        [ $httpBody ] = $result;
        
        if ($httpBody['success'] === true) {
            $tableName = $this->request->param('name');
            $tableTitle = $this->request->param('title');
            $currentTime = date("Y-m-d H:i:s");

            // Create Files
            Console::call('make:buildModel', [$tableTitle]);

            Db::startTrans();
            try {
                // Create Table
                Db::execute("CREATE TABLE `$tableName` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `create_time` DATETIME NOT NULL , `update_time` DATETIME NOT NULL , `delete_time` DATETIME NOT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // Add Rules
                $parentRule = RuleService::create([
                    'parent_id' => 0,
                    'name' => $tableTitle,
                    'create_time' => $currentTime,
                    'update_time' => $currentTime,
                ]);
                $parentRuleId = $parentRule->id;
                $rule = new RuleService();
                $initRules = [
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Home', 'rule' => 'backend/' . $tableName . '/home', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Add', 'rule' => 'backend/' . $tableName . '/add', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Save', 'rule' => 'backend/' . $tableName . '/save', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Read', 'rule' => 'backend/' . $tableName . '/read', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Update', 'rule' => 'backend/' . $tableName . '/update', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Delete', 'rule' => 'backend/' . $tableName . '/delete', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Restore', 'rule' => 'backend/' . $tableName . '/restore', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ];
                $rule->saveAll($initRules);

                // Add Menus
                $parentMenu = MenuService::create([
                    'parent_id' => 0,
                    'name' => $tableTitle,
                    'icon' => 'icon-project',
                    'path' => '/basic-list/backend/' . $tableName . 's',
                    'create_time' => $currentTime,
                    'update_time' => $currentTime,
                ]);
                $parentMenuId = $parentMenu->id;
                $menu = new MenuService();
                $initMenus = [
                    ['parent_id' => $parentMenuId, 'name' => 'add', 'path' => '/basic-list/backend/' . $tableName . 's/add', 'hideInMenu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentMenuId, 'name' => 'edit', 'path' => '/basic-list/backend/' . $tableName . 's/:id', 'hideInMenu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
                ];
                $menu->saveAll($initMenus);
            } catch (\Exception $e) {
                Db::rollback();
            }
        }

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->model->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->allowUpdate));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        [ $httpBody ] = $result;

        if ($httpBody['success'] === true && isset($httpBody['data']) && count($httpBody['data']) === 1) {
            $tableTitle = $httpBody['data'][0]['title'];
            $tableName = $httpBody['data'][0]['name'];

            Console::call('make:removeModel', [$tableTitle]);

            // Drop Table
            Db::execute("DROP TABLE IF EXISTS `$tableName`");

            // Delete Parent Rule
            $parentRule = RuleService::where('name', $tableTitle)->find();
            $parentRuleId = $parentRule->id;
            $parentRule->force()->delete();
            // Delete Children Rule
            $childrenRule = new RuleService();
            $childrenRuleDataSet = $childrenRule->where('parent_id', $parentRuleId)->select();
            if (!$childrenRuleDataSet->isEmpty()) {
                foreach ($childrenRuleDataSet as $item) {
                    $item->force()->delete();
                }
            }

            // Delete Parent Menu
            $parentMenu = MenuService::where('name', $tableName)->find();
            $parentMenuId = $parentMenu->id;
            $parentMenu->force()->delete();
            // Delete Children Menu
            $childrenMenu = new MenuService();
            $childrenMenuDataSet = $childrenMenu->where('parent_id', $parentMenuId)->select();
            if (!$childrenMenuDataSet->isEmpty()) {
                foreach ($childrenMenuDataSet as $item) {
                    $item->force()->delete();
                }
            }
        }
        
        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->model->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
    
    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }
    
    public function designUpdate($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->allowUpdate));

        return $this->json(...$result);
    }
}
