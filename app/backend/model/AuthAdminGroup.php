<?php
declare (strict_types = 1);

namespace app\backend\model;

use think\model\Pivot;
use think\model\concern\SoftDelete;

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
