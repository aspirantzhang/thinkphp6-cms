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
<<<<<<< HEAD
=======




>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
