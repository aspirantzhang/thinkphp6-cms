<?php

declare(strict_types=1);

namespace app\backend\traits;

trait ModelService
{
    public function saveAPI($data)
    {
        $result = $this->saveNew($data);
        if ($result) {
            return resSuccess('Add successfully.');
        } else {
            return resError($this->error);
        }
    }
}
