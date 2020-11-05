<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Model as ModelService;
use think\facade\Db;
use think\facade\Config;
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
    /** @OA\Schema(
    *    schema="model-list",
    *    @OA\Property(
    *        property="success",
    *        type="boolean",
    *    ),
    *    @OA\Property(
    *        property="message",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="data",
    *        type="object",
    *        @OA\Property(
    *            property="page",
    *            type="object",
    *            allOf={
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="title",
    *                       type="string",
    *                   ),
    *                   @OA\Property(
    *                       property="type",
    *                       type="string",
    *                   ),
    *                   @OA\Property(
    *                       property="searchBar",
    *                       type="boolean",
    *                   ),
    *                   @OA\Property(
    *                       property="trash",
    *                       type="boolean",
    *                   ),
    *               ),
    *            },
    *        ),
    *        @OA\Property(
    *            property="layout",
    *            type="object",
    *            allOf={
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="tableColumn",
    *                       type="object",
    *                   ),
    *                   @OA\Property(
    *                       property="tableToolBar",
    *                       type="object",
    *                   ),
    *                   @OA\Property(
    *                       property="batchToolBar",
    *                       type="object",
    *                   ),
    *               ),
    *            },
    *        ),
    *        @OA\Property(
    *            property="dataSource",
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/model"),
    *        ),
    *        @OA\Property(
    *            property="meta",
    *            type="object",
    *            allOf={
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="total",
    *                       type="number",
    *                   ),
    *                   @OA\Property(
    *                       property="per_page",
    *                       type="number",
    *                   ),
    *                   @OA\Property(
    *                       property="page",
    *                       type="number",
    *                   ),
    *               ),
    *            },
    *        ),
    *    ),
    * )
    */
 
    /** @OA\Schema(
    *    schema="model",
    *    @OA\Property(
    *        property="id",
    *        type="integer",
    *        format="int32",
    *    ),
    *    @OA\Property(
    *        property="title",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="name",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="data",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="create_time",
    *        type="string",
    *    ),
    *    @OA\Property(
    *        property="status",
    *        type="integer",
    *        example=1,
    *        @OA\Items(
    *            type="integer",
    *            enum = {0, 1},
    *        )
    *    ),
    * )
    */

    /**
    * @OA\Get(
    *     path="/backend/models",
    *     summary="Get the list of models.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="sort",
    *         in="query",
    *         description="The field used for sort.",
    *         @OA\Schema(
    *             type="array",
    *             @OA\Items(
    *                 type="string",
    *                 enum = {"id", "create_time"},
    *             )
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="order",
    *         in="query",
    *         description="The method used for sorting.",
    *         @OA\Schema(
    *             type="array",
    *             @OA\Items(
    *                 type="string",
    *                 enum = {"descent", "ascent"},
    *             )
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="status",
    *         in="query",
    *         description="0 = Disabled, 1 = Enabled",
    *         @OA\Schema(
    *             type="integer",
    *             example=1,
    *             @OA\Items(
    *                 type="integer",
    *                 enum = {0, 1},
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/model-list"),
    *        )
    *     ),
    *     @OA\Response(
    *        response="200.1",
    *        description="Need Login",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/error"),
    *        )
    *     ),
    * )
    */
    public function home()
    {
        $result = $this->model->paginatedListAPI($this->request->only($this->model->getAllowHome()));

        return $this->json(...$result);
    }
    /**
    * @OA\Get(
    *     path="/backend/models/add",
    *     summary="Get the page for adding a new model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Response(
    *         response=200,
    *         description="Success"
    *     ),
    * )
    */
    public function add()
    {
        $result = $this->model->addAPI();

        return $this->json(...$result);
    }
    /**
    * @OA\Post(
    *     path="/backend/models",
    *     summary="Used for adding a new model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "title",
    *                     "name",
    *                 },
    *                 example={
    *                     "title": "User Title",
    *                     "name": "user",
    *                     "create_time": "2020-11-02T16:11:24+08:00",
    *                     "status": true,
    *                 },
    *                 @OA\Property(
    *                     property="title",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="create_time",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="boolean",
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(
    *                type="object",
    *                @OA\Property(
    *                    property="success",
    *                    type="boolean",
    *                ),
    *                @OA\Property(
    *                    property="message",
    *                    type="string",
    *                ),
    *                @OA\Property(
    *                    property="data",
    *                    type="object",
    *                )
    *            )
    *        )
    *     ),
    * )
    */
    public function save()
    {
        $tableName = strtolower($this->request->param('name'));
        $tableTitle = $this->request->param('title');
        $currentTime = date("Y-m-d H:i:s");

        if (in_array($tableName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved table name.');
        }

        if ($this->existsTable($tableName)) {
            return $this->error('Table already exists.');
        }

        $result = $this->model->saveAPI($this->request->only($this->model->getAllowSave()));
        [ $httpBody ] = $result;
        
        if ($httpBody['success'] === true) {
            // Create Files
            Console::call('make:buildModel', [$tableTitle]);

            Db::startTrans();
            try {
                // Create Table
                Db::execute("CREATE TABLE `$tableName` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `create_time` DATETIME NOT NULL , `update_time` DATETIME NOT NULL , `delete_time` DATETIME NULL DEFAULT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");

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
                'name' => $tableName . '-list',
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
    /**
    * @OA\Get(
    *     path="/backend/models/{id}",
    *     summary="Get information of a specific model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */
    public function read($id)
    {
        $result = $this->model->readAPI($id);

        return $this->json(...$result);
    }
    /**
    * @OA\Put(
    *     path="/backend/models/{id}",
    *     summary="Update the information of a specific model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "title",
    *                     "name",
    *                 },
    *                 example={
    *                     "title": "User Title",
    *                     "name": "user",
    *                     "create_time": "2020-11-02T16:11:24+08:00",
    *                     "status": true,
    *                 },
    *                 @OA\Property(
    *                     property="title",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="create_time",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="status",
    *                     type="boolean",
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(
    *                type="object",
    *                @OA\Property(
    *                    property="success",
    *                    type="boolean",
    *                ),
    *                @OA\Property(
    *                    property="message",
    *                    type="string",
    *                ),
    *                @OA\Property(
    *                    property="data",
    *                    type="object",
    *                )
    *            )
    *        )
    *     ),
    * )
    */
    public function update($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));

        return $this->json(...$result);
    }
    /**
    * @OA\Delete(
    *     path="/backend/models",
    *     summary="Delete a model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "type",
    *                     "ids",
    *                 },
    *                 example={
    *                     "type": "delete",
    *                     "ids": {53, 54, 55},
    *                 },
    *                 @OA\Property(
    *                     property="type",
    *                     type="string",
    *                     enum={"delete","deletePermanently"}
    *                 ),
    *                 @OA\Property(
    *                     property="ids",
    *                     type="array",
    *                     @OA\Items(),
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */
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
            $parentMenu = MenuService::where('name', $tableName . '-list')->find();
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
    /**
    * @OA\Post(
    *     path="/backend/models/restore",
    *     summary="Restore a model from the trash can.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "ids",
    *                 },
    *                 example={
    *                     "ids": {53, 54, 55},
    *                 },
    *                 @OA\Property(
    *                     property="ids",
    *                     type="array",
    *                     @OA\Items(),
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */
    public function restore()
    {
        $result = $this->model->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
    /**
    * @OA\Get(
    *     path="/backend/models/design/{id}",
    *     summary="Get design data of a specific model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *     ),
    * )
    */
    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }
    /**
    * @OA\Put(
    *     path="/backend/models/design/{id}",
    *     summary="Update design data of a specific model.",
    *     tags={"models"},
    *     security={
    *       {"ApiKeyAuth": {}}
    *     },
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 type="object",
    *                 required={
    *                     "data",
    *                 },
    *                 example={
    *                     "data": {}
    *                 },
    *                 @OA\Property(
    *                     property="data",
    *                     type="object",
    *                     @OA\Items()
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *        response="200",
    *        description="Success",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(
    *                type="object",
    *                @OA\Property(
    *                    property="success",
    *                    type="boolean",
    *                ),
    *                @OA\Property(
    *                    property="message",
    *                    type="string",
    *                ),
    *                @OA\Property(
    *                    property="data",
    *                    type="object",
    *                )
    *            )
    *        )
    *     ),
    * )
    */
    public function designUpdate($id)
    {
        $tableName = ModelService::where('id', $id)->value('name');

        // Reserved model check
        if (in_array($tableName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved model, operation not allowed.');
        }

        // Check table exists
        if (!$this->existsTable($tableName)) {
            return $this->error($this->error);
        }

        // Build fields sql statement.
        $data = $this->request->param('data');
        if ($data) {
            // Get all exist fields
            $existingFields = [];
            $columnsQuery = Db::query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tableName';");
            if ($columnsQuery) {
                $existingFields = extractValues($columnsQuery, 'COLUMN_NAME');
            }
            // Get this fields
            $thisFields = extractValues($data['fields'], 'name');
            // Exclude reserved fields
            $thisFields = array_diff($thisFields, Config::get('model.reserved_field'));

            // Get fields group by types
            $delete = array_diff($existingFields, $thisFields);
            $add = array_diff($thisFields, $existingFields);
            $change = array_intersect($thisFields, $existingFields);

            $fieldSqlArray = [];
            foreach ($data['fields'] as $field) {
                $type = 'VARCHAR';
                $typeAddon = '(255)';
                $default = '';
                switch ($field['type']) {
                    case 'number':
                        $type = 'INT';
                        $typeAddon = ' UNSIGNED';
                        break;
                    case 'datetime':
                        $type = 'DATETIME';
                        $typeAddon = '';
                        break;
                    case 'tag':
                    case 'switch':
                        $type = 'TINYINT';
                        $typeAddon = '(1)';
                        $default = 'DEFAULT 1';
                        break;
                    case 'longtext':
                        $type = 'LONGTEXT';
                        $typeAddon = '';
                        break;
                    default:
                        break;
                }

                if (in_array($field['name'], $add)) {
                    $method = 'ADD';
                    $fieldSqlArray[] = " $method `${field['name']}` $type$typeAddon NOT NULL $default";
                }
                
                if (in_array($field['name'], $change)) {
                    $method = 'CHANGE';
                    $fieldSqlArray[] = " $method `${field['name']}` `${field['name']}` $type$typeAddon NOT NULL $default";
                }
            }

            foreach ($delete as $field) {
                $method = 'DROP IF EXISTS';
                if (!in_array($field, Config::get('model.reserved_field'))) {
                    $fieldSqlArray[] = " $method `$field`";
                }
            }

            $alterTableSql = 'ALTER TABLE `' . $tableName . '` ' . implode(',', $fieldSqlArray) . '; ';

            Db::query($alterTableSql);

            $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));

            return $this->json(...$result);
        }
        return $this->error('Nothing to do.');
    }
}
