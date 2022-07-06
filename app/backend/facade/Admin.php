<?php

declare(strict_types=1);

namespace app\backend\facade;

use app\core\BaseFacade;
use app\core\domain\Layout\ListLayout;
use app\core\domain\Login\JwtVisitor;
use app\core\domain\Login\Login;
use app\core\mapper\ListData;
use app\jwt\exception\TokenExpiredException;
use app\jwt\exception\TokenInvalidException;

class Admin extends BaseFacade
{
    public function getPaginatedList(array $option = [], array $input = null)
    {
        $input ??= $this->request->only($this->model->getAllow('index'));
        // get data from mapper
        $data = (new ListData($this->model))->setInput($input)
            ->setOption($option)
            ->setType(ListData::PAGINATED)
            ->toArray();

        // inject data into the layout
        $result = (new ListLayout($this->model))->setInput($input)
            ->setOption($option)
            ->setData($data)
            ->toArray();

        return success(data: $result);

        /*
        TODO: Add i18n data
        if (!empty($data) && isset($data['dataSource']) && isset($data['pagination'])) {
            $layout['dataSource'] = $this->addI18nStatus($data['dataSource']);
            $layout['meta'] = $data['pagination'];
        } */
    }

    public function login()
    {
        $input = $this->request->only(['admin_name', 'password']);

        $login = new Login($this->model);
        $loginSuccess = $login->setInput($input)->check();

        if ($loginSuccess === false) {
            return error('login failed');
        }

        $jwtVisitor = new JwtVisitor();
        $login->accept($jwtVisitor);

        $userProps = ['uid' => 'id', 'adminName' => 'admin_name', 'displayName' => 'display_name'];
        $result = $jwtVisitor->withUserProps($userProps)->getResult();

        return success(data: $result);
    }

    public function refreshToken()
    {
        try {
            $newToken = app('jwt')->setStateful(true)->refreshToken($this->request);
        } catch (TokenExpiredException) {
            return error(message: 're-login required', code: 401);
        } catch (TokenInvalidException) {
            return error(message: 'invalid refresh token', code: 401);
        }

        $result = ['accessToken' => $newToken];

        return success(data: $result);
    }

    public function store($input = null)
    {
        $input ??= $this->request->only($this->model->getAllow('store'));

        $this->model->checkUniqueValues($input);

        $this->model->startTrans();
        try {
            $this->model->allowField($this->model->getAllow('store'))->save($input);

            $id = (int) $this->model->getAttr('id');

            $this->model->commit();

            return success('add successfully', ['id' => $id]);
        } catch (\Exception $e) {
            $this->model->rollback();

            return error('operation failed');
        }
    }
}
