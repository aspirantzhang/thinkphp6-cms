<?php

declare(strict_types=1);

namespace app\core\domain\Login;

interface LoginVisitor
{
    public function visitLogin(Login $login);
}
