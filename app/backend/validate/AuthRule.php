<?php

namespace app\backend\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule =   [
        'id'            =>  'require|number',
        'parent_id'     =>  'number',
        'rule'          =>  'require|length:4,32',
        'name'          =>  'require|length:4,32',
        'type'          =>  'in:0,1',
        'status'        =>  'in:0,1',
        'page'          =>  'number',
        'per_page'      =>  'number',
        'create_time'   =>  'checkDateTimeRange',
    ];

    protected $message  =   [
        'id.require'                => 'ID field is empty.',
        'id.number'                 => 'ID must be numbers only.',
        'parent_id.number'          => 'ParentID must be numbers only.',
        'rule.require'              => 'The rule field is empty.',
        'rule.length'               => 'Rule length should be between 4 and 32.',
        'name.require'              => 'The name field is empty.',
        'name.length'               => 'Name length should be between 4 and 32.',
        'type.in'                   => 'Type value should be 0 or 1.',
        'status.in'                 => 'Status value should be 0 or 1.',
        'page.number'               => 'Page must be numbers only.',
        'per_page.number'           => 'Per_page must be numbers only.',
        'create_time.checkDateTimeRange'    => 'Invalid create time format.'
    ];

    protected $scene = [
        'index'         =>  ['parent_id', 'create_time', 'page', 'per_page'],
        'save'          =>  ['parent_id', 'rule', 'name', 'type', 'status'],
        'read'          =>  ['id'],
        'edit'          =>  ['id'],
        'update'        =>  ['id', 'parent_id', 'rule', 'name', 'type', 'status'],
        'delete'        =>  ['id'],
        'create'        =>  [''],
        'menus'          =>  [''],
    ];

    protected function checkDateTimeRange($value, $rule, $data=[])
    {
        if (strlen($value) == 39 && strpos($value, 'T') == 19) {
            // check length & symbol postion
            $timeArray = explode('T', $value);
            if (validateDateTime($timeArray[0]) && validateDateTime($timeArray[1])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
