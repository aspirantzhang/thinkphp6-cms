<?php

declare(strict_types=1);

namespace app\api\controller;

use think\facade\Config;
use app\api\service\AuthRule as AuthRuleService;

class AuthRule extends Common
{
    protected $authRule;

    public function initialize()
    {
        $this->authRule = new AuthRuleService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->authRule->treeListAPI($this->request->only($this->authRule->getAllowHome()));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->authRule->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->authRule->saveAPI($this->request->only($this->authRule->getAllowSave()));

        return $this->json(...$result);
    }

    public function read(int $id)
    {
        $result = $this->authRule->readAPI($id);

        return $this->json(...$result);
    }

    public function update(int $id)
    {
        $result = $this->authRule->updateAPI($id, $this->request->only($this->authRule->getAllowUpdate()), ['rules']);

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->authRule->deleteAPI($this->request->param('ids'), $this->request->param('type'));

        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->authRule->restoreAPI($this->request->param('ids'));

        return $this->json(...$result);
    }

    public function i18n(int $id)
    {
        $result = $this->authRule->i18nAPI($id);

        return $this->json(...$result);
    }

    public function i18nUpdate(int $id)
    {
        $result = $this->authRule->i18nUpdateAPI($id, $this->request->only(Config::get('lang.allow_lang_list')));

        return $this->json(...$result);
    }

    public function revision(int $id)
    {
        $result = $this->app->revision->listAPI($this->authRule->getTableName(), $id, (int)$this->request->param('page') ?: 1);

        return $this->json($result);
    }

    public function revisionRestore(int $id)
    {
        $result = $this->app->revision->restoreAPI($this->authRule->getTableName(), $id, (int)$this->request->param('revisionId'));

        return $this->json($result);
    }

    public function revisionRead(int $revisionId)
    {
        $result = $this->app->revision->readAPI((int)$revisionId);

        return $this->json($result);
    }
}
