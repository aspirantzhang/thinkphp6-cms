<?php

declare(strict_types=1);

namespace app\common;

use app\api\service\Admin;
use app\api\service\AuthGroup;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;

class Auth
{
    protected static $instance;

    /**
     * 默认配置
     * 优先级低于 config/auth.php.
     */
    protected $config = [
        'auth_on' => 1, // 权限开关
        'auth_type' => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group' => 'auth_group', // 用户组数据表名
        'auth_group_access' => 'auth_admin_group', // 用户-用户组关系表
        'auth_rule' => 'auth_rule', // 权限规则表
        'auth_user' => 'admin', // 用户信息表
    ];

    /**
     * 构造方法
     * Auth constructor.
     */
    private function __construct()
    {
        // 可设置配置项 auth, 此配置项为数组。
        if ($auth = Config::get('auth')) {
            $this->config = array_merge($this->config, $auth);
        }
    }

    private function __clone()
    {
    }

    /**
     * 初始化.
     *
     * @param array $options 参数
     *
     * @return \think\Request
     */
    public static function getInstance(array $options = []): Auth
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 检查权限.
     *
     * @param $name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $admin_id  int           认证用户的id
     * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @param int    $type     认证类型
     * @param string $mode     执行check的模式
     *
     * @return bool 通过验证返回true;失败返回false
     */
    public function check($name, int $admin_id, string $relation = 'or', int $type = 1, $mode = 'url'): bool
    {
        if (!$this->config['auth_on']) {
            return true;
        }
        // 获取用户需要验证的所有有效规则列表
        $authList = $this->getAuthList($admin_id, $type);
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = [];
        // 保存验证通过的规则名
        if ('url' == $mode) {
            $REQUEST = unserialize(strtolower(serialize(Request::param())));
        }
        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ('url' == $mode && $query != $auth) {
                parse_str($query, $param);
                // 解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {
                    // 如果节点相符且url参数满足
                    $list[] = $auth;
                }
            } else {
                if (in_array($auth, $name)) {
                    $list[] = $auth;
                }
            }
        }
        if ('or' == $relation && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation && empty($diff)) {
            return true;
        }
        $not = array_intersect($name, $list);
        if ('not in' == $relation && !empty($not)) {
            return true;
        }

        return false;
    }

    /**
     * 根据用户id获取用户组,返回值为数组.
     *
     * @param $admin_id int     用户id
     *
     * @return array 用户所属的用户组 array(
     *               array('admin_id'=>'用户id','group_id'=>'用户组id','name'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *               ...)
     */
    public function getGroups(int $admin_id)
    {
        static $groups = [];
        if (isset($groups[$admin_id])) {
            return $groups[$admin_id];
        }
        // 转换表名
        $type = Config::get('database.prefix') ? 1 : 0;
        $auth_group_access = parse_name($this->config['auth_group_access'], $type);
        $auth_group = parse_name($this->config['auth_group'], $type);
        // 执行查询
        // $user_groups = Db::view($auth_group_access, 'admin_id,group_id')
        //     ->view($auth_group, 'name,rules', "{$auth_group_access}.group_id={$auth_group}.id", 'LEFT')
        //     ->where("{$auth_group_access}.admin_id='{$admin_id}' and {$auth_group}.status='1'")
        //     ->select();
        $user = new Admin();
        $group = new AuthGroup();

        // Get all user groups
        $userGroups = $user->getAllRelationByField('id', $admin_id, 'groups');
        $userGroupIds = extractValues($userGroups, 'id');

        // Get all group rules
        $result = [];
        if ($userGroupIds) {
            foreach ($userGroupIds as $groupId) {
                $rules = $group->getAllRelationByField('id', $groupId, 'rules');
                $result = array_merge($result, extractValues($rules, 'id'));
            }
        }

        return $result;
    }

    /**
     * 获得权限列表.
     *
     * @param int $admin_id 用户id
     */
    protected function getAuthList(int $admin_id, int $type): array
    {
        static $_authList = [];
        // 保存用户验证通过的权限列表
        $t = implode(',', (array) $type);
        if (isset($_authList[$admin_id . $t])) {
            return $_authList[$admin_id . $t];
        }
        if (2 == $this->config['auth_type'] && Session::has('_auth_list_' . $admin_id . $t)) {
            return Session::get('_auth_list_' . $admin_id . $t);
        }
        // 读取用户所属用户组
        $ids = $this->getGroups($admin_id);
//         $ids = [];
        // //保存用户所属用户组设置的所有权限规则id
//         foreach ($ids as $g) {
//             $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
//         }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$admin_id . $t] = [];

            return [];
        }
        $map = [
            'id' => $ids,
            'type' => $type,
            'status' => 1,
        ];
        // 读取用户组所有权限规则
        $rules = Db::name($this->config['auth_rule'])->where($map)->field('condition,rule_path')->select();
        // 循环规则，判断结果。
        $authList = [];

        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) {
                // 根据condition进行验证
                $user = $this->getUserInfo($admin_id);
                // 获取用户信息,一维数组
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                // dump($command); //debug
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule['rule_path']);
                }
            } else {
                // 只要存在就记录
                $authList[] = strtolower($rule['rule_path']);
            }
        }
        $_authList[$admin_id . $t] = $authList;
        if (2 == $this->config['auth_type']) {
            // 规则列表结果保存到session
            Session::set('_auth_list_' . $admin_id . $t, $authList);
        }

        return array_unique($authList);
    }

    /**
     * 获得用户资料,根据自己的情况读取数据库.
     */
    protected function getUserInfo(int $admin_id): array
    {
        static $userinfo = [];
        $user = $this->db->name($this->config['auth_user']);
        // 获取用户表主键
        $_pk = is_string($user->getPk()) ? $user->getPk() : 'admin_id';
        if (!isset($userinfo[$admin_id])) {
            $userinfo[$admin_id] = $user->where($_pk, $admin_id)->find();
        }

        return $userinfo[$admin_id];
    }
}
