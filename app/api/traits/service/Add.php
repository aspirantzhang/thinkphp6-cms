<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Add
{
    public function addAPI()
    {
        $page = $this->addBuilder($this->getAddonData());
        
        if ($page) {
            return $this->success('', $page);
        } else {
            return $this->error('Get page data failed.');
        }
    }
}
