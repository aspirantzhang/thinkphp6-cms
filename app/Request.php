<?php

namespace app;

class Request extends \think\Request
{
    protected $filter = ['trim', 'strip_tags', 'htmlspecialchars'];
}
