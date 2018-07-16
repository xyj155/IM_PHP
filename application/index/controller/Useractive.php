<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/15
 * Time: 14:03
 */

namespace app\index\controller;


use think\Db;

class Useractive extends Basecontroller
{
    public function AroundUserActive($location)
    {
        $user_location_list = Db::table('user')
            ->where('location', $location)
            ->select();//查询当地用户
        if ($user_location_list) {
            $all_user_location_list = array();
            foreach ($user_location_list as $key => $value) {//用户遍历
                $user_location_active = Db::table('active')
                    ->where('userid', $value['username'])
                    ->find();//查询单个
                $user_location_list[$key]['active'] = $user_location_active;//单个复合数组初始化
            }
//            array_push($all_user_location_list, $user_location_list);
            $arr = $all_user_location_list + $user_location_list;
            return $this->json(true, 200, $arr);
        } else {
            return $this->json(true, 201, $user_location_list);
        }
    }
}