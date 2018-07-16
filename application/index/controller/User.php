<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 2:12
 */

namespace app\index\controller;


use think\Db;
use think\Exception;
use think\exception\ErrorException;
use think\Session;

class User extends Basecontroller
{
    public $login_arr = array(
        'id' => null,
        'username' => null,
        'password' => null
    );

    protected function _initialize()
    {
        Session::init([
            'prefix' => 'index',
            'expire' => 10,
        ]);
    }

    /**注册
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function Register()
    {
        $username = input('username');
        $password = input('password');
        $tel = input('tel');
        $user_register_already_db = Db::table('user')
            ->where('username', $username)
            ->find();
        if ($user_register_already_db) {
            $user_register_arr_error = ['username' => '', 'password' => '', 'msg' => '用户已存在'];
            return $this->json(false, 201, $user_register_arr_error);
        } else {
            $user_register_arr = ['username' => $username, 'password' => $password, 'tel' => $tel];
            $user_register_db = Db::table('user')
                ->insert($user_register_arr);
            if ($user_register_db) {
                $user_register_arr['msg'] = '注册成功';
                return $this->json(true, 200, $user_register_arr);
            } else {
                $user_register_arr['msg'] = '注册失败';
                return $this->json(false, 201, $user_register_arr);
            }
        }
    }

    /***登陆
     * @param $username
     * @param $password
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function Login($username, $password)
    {

        $user = Db::table('user')
            ->where('username', $username)
            ->where('password', $password)
            ->find();
        $user_arr_error = ['username' => $username, 'password' => $password, 'msg' => ''];
        if ($user) {
            Session::prefix('username' . $username);
            $user_arr = ['msg' => ''];
            $username_session = Session::get('user', 'username' . $username);
            if ($username_session == $username) {//判断是否是session同步登陆
                $user_arr['msg'] = '登陆成功';//登陆成功
                array_push($this->login_arr, $user_arr);
                return $this->json(true, 200, $user_arr + $user);
            } else {
                $user_arr['msg'] = '你已处于登陆状态';//已登陆
                Session::set('user', $username, 'username' . $username);
                return $this->json(true, 200, $user_arr + $user);
            }
        }
        $user_arr_error['msg'] = '401';
        return $this->json(false, '账号密码错误', $user_arr_error);
    }

    /***登出
     * @param $username
     * @param $password
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function Loginout($username, $password)
    {

        $user = Db::table('user')
            ->where('username', $username)
            ->where('password', $password)
            ->find();
        $user_arr = ['msg' => ''];
        if ($user) {
            Session::delete('user', 'username' . $username);
            $user_arr['msg'] = '登录成功';
            return $this->json(true, 200, $user_arr + $user);
        } else {
            $user_arr['msg'] = '登出失败';
            return $this->json(false, 201, $user_arr);
        }
    }
}