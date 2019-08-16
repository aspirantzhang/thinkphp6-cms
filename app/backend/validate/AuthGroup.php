<?php

namespace app\backend\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule =   [
        'id'            =>  'require|number',
        'name'          =>  'require|length:4,32',
        'status'        =>  'in:0,1',
        'page'          =>  'number',
        'per_page'      =>  'number',
        'create_time'   =>  'checkDateTimeRange',
    ];

    protected $message  =   [
        'id.require'                => 'ID field is empty.',
        'id.number'                 => 'ID must be numbers only.',
        'name.require'              => 'The name field is empty.',
        'name.length'               => 'Name length should be between 4 and 32.',
        'status.in'                 => 'Status value should be 0 or 1.',
        'page.number'               => 'Page must be numbers only.',
        'per_page.number'           => 'Per_page must be numbers only.',
        'create_time.checkDateTimeRange'    => 'Invalid create time format.'
    ];

    protected $scene = [
        'index'         =>  ['create_time', 'page', 'per_page'],
        'save'          =>  ['name', 'status'],
        'read'          =>  ['id'],
        'update'        =>  ['id', 'name', 'status'],
        'delete'        =>  ['id'],
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
