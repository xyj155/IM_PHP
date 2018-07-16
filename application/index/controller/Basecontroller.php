<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 2:15
 */

namespace app\index\controller;


use function MongoDB\BSON\toJSON;
use think\Controller;

class Basecontroller extends Controller
{

    public function json($boolean, $code, $data)
    {
        $json = array('issuccess' => $boolean, 'code' => $code,
            'data' => $data);
        return json_encode($json);
    }


}