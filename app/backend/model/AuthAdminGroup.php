<?php

declare(strict_types=1);

namespace app\backend\model;

use think\model\concern\SoftDelete;
use think\model\Pivot;

class AuthAdminGroup extends Pivot
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp = true;
    protected $readonly = ['id'];

    // Accessor

    // Mutator

    // Searcher
}
